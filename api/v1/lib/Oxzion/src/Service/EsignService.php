<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Esign\Model\EsignDocument;
use Esign\Model\EsignDocumentTable;
use Esign\Model\EsignDocumentSigner;
use Esign\Model\EsignDocumentSignerTable;
use Oxzion\Messaging\MessageProducer;
use Oxzion\OxServiceException;
use Oxzion\Utils\RestClient;
use Oxzion\Utils\FileUtils;

class EsignService extends AbstractService
{
	private $table;
	private $signerTable;
    private $messageProducer;
    /**
     * @ignore __construct
     */
    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }
    public function __construct($config, $dbAdapter, EsignDocumentTable $table, EsignDocumentSignerTable $signerTable, MessageProducer $messageProducer)
    {
    	parent::__construct($config, $dbAdapter);
    	$this->table = $table;
    	$this->signerTable = $signerTable;
        $this->messageProducer = $messageProducer;
    	$this->restClient = new RestClient($this->config['esign']['url']);
    }

    /**
     * set up document
     *
     * setting up the document and uploading it
     *
     *  @param $ref_id, $documentUrl, $signers
     *  @return access token
     */

    public function setupDocument($ref_id, $documentUrl ,array $signers)
    {
    	if(!isset($documentUrl) || !FileUtils::fileExists($documentUrl)){
    		throw new ServiceException("Document not found", 'doc.not.found', OxServiceException::ERR_CODE_PRECONDITION_FAILED);
    	}
    	if (!$signers || count($signers) == 0) {
    		throw new ServiceException("signers not provided", 'signers.not.provided', OxServiceException::ERR_CODE_PRECONDITION_FAILED);	
    	}
    	$data = array();
    	$data['ref_id'] = $ref_id;
    	$esignDocument = new EsignDocument($this->table);
    	$esignDocument->assign($data);
    	try{
    		$this->beginTransaction();
    		$esignDocument->save();
    		$generated = $esignDocument->getGenerated(true);
    		$data['uuid'] = $generated['uuid'];
    		$data['id'] = $generated['id'];
    		$path = $this->copySourceDocument($documentUrl, $data['uuid']);
    		$docId = $this->uploadDocument($documentUrl, $signers);
    		// foreach ($signers as $value) {
    		// 	$this->saveDocumentSigner($value, $docId);
    		// }
    		$esignDocument->assign(['doc_id' => $docId]);
    		$esignDocument->save();
    		$this->commit();
    		return $docId;
    	} catch (Exception $e) {
    		$this->rollback();
    		throw $e;
    	}
    }

    /**
     * saveDocumentSigner
     *
     * storing the document signers
     *
     *  @param  $signers
     *  @return 
     */

    private function saveDocumentSigner($email, $document_id){
    	$signer = array();
    	$signer['email'] = $email;
    	$signer['esign_document_id'] = $document_id;
    	$signer['details'] = json_encode($signer);
    	try{
    		$docSigner = new EsignDocumentSigner($this->signerTable);
    		$docSigner->assign($signer);
    		$docSigner->save();
    	} catch (Exception $e) {
    		$this->rollback();
    		throw $e;
    	}
    }

    /**
     * copySourceDocument
     *
     * coppyin the document from dource to destination
     *
     *  @param  $documentUrl
     *  @return destination adress
     */

    private function copySourceDocument($documentUrl, $uuid){
    	$destination = $this->config['APP_ESIGN_FOLDER'];
    	$path = $destination.'/'.$uuid;
    	if (!FileUtils::fileExists($path)) {
    		FileUtils::createDirectory($path);
    	}
    	$filename = FileUtils::getFileName($documentUrl);
    	FileUtils::copy($documentUrl.$filename,$filename,$path);
    	return $documentUrl.'/'.$filename;
    }

    /**
     * copySourceDocument
     *
     * coppyin the document from dource to destination
     *
     *  @param  $docPath $signer $docId
     *  @return 
     */
    private function uploadDocument($docPath, array $signer){
    	$data = $this->assignData($docPath, $signer);
    	$response = $this->restClient->postMultiPart($this->config['esign']['docurl'].'documents', $data, array(FileUtils::getFileName($docPath) => $docPath ),  array( 'Authorization'=> 'Bearer '. $this->getAuthToken()
    ));
    	$returnDocId = json_decode($response,true);
    	return $returnDocId['data']['id'];
    }

	/**
     * get auth token
     *
     * Upload files from Front End and store it in temp Folder
     *
     *  @param 
     *  @return access token
     */

	public function getAuthToken(){
		$clientid = $this->config['esign']['clientid'];
		$clientsecret = $this->config['esign']['clientsecret'];
		$senderemail = $this->config['esign']['email'];
		$username = $this->config['esign']['username'];
		$password = $this->config['esign']['password'];
		$post  = "grant_type=client_credentials&client_id=$clientid&client_secret=$clientsecret&username=$username&password=$password&redirect_uri=http://eos.eoxvantage.com";

		$response = $this->restClient->postWithHeaderAsBody($this->config['esign']['url'],$post,array('Content-Type' => 'application/x-www-form-urlencoded',
			'Content-Length' => strlen($post)));
		$authToken = json_decode($response['body'], true);
		return $authToken['access_token'];
	}

	public function getDocumentStatus($docId ){
		$response = $this->restClient->get($this->config['esign']['docurl'].'documents/'.$docId,array() ,  array( 'Authorization'=> 'Bearer '. $this->getAuthToken()

	));
		$data = json_decode($response,true);
		return $data['data']['status'];

	}

	private function subscriptions(){
		$return = $this->restClient->get($this->config['esign']['docurl']."integrations/VANTAGE/subscriptions", array(), array( "Authorization: Bearer". $this->authToken
	));
		$response = json_decode($return,true);
		if(!isset($response)){
			$subscribe = array(
				"SIGNED" => false,
				"FINALIZED" => false
			);
			foreach ($response['data'] as $event) {
				if (isset($subscribe[$event['eventType']]) && $subscribe[$event['eventType']] == false)
					$subscribe[$event['eventType']] = true;
				else
					$this->deleteSubscribe($event['id']);
			}
			foreach ($subscribe as $eventType => $value) {
				if (!$value)
					$this->setupSubcription($eventType);
			}
			return true;
		}
	}

	private function setupSubcription($hook) {
		$header = array( "Authorization: Bearer". $this->authToken
	);
		$post = json_encode(array(
			"eventType" => $hook
		));
		$response = $this->restClient->postWithHeader($this->config['esign']['docurl']."subscriptions", $post,$header);

		if (!isset($response))
			return false;
		else
			return true;
	}

	private function deleteSubscribe($subscriptionId) {
		$header = array( "Authorization: Bearer". $this->authToken
	);
		$response = $this->restClient->delete($this->config['esign']['docurl']."integrations/VANTAGE/subscriptions/".$subscriptionId, array(),$header);
		return json_decode($response, true);
	}

	private function assignData($docPath, $data){
		$fileName = FileUtils::getFileName($docPath);
		$callbackUrl = $this->config['esign']['callbackUrl'];
		$returnArray = array(
			'name' => $fileName,
			'message' => ($data['message']) ? ($data['message']) : 'Please sign here',
			'action' => 'email',
			'file' => $docPath.$fileName,
			'message'=>'Please review and Sign the document.',
			'documentName'=>$fileName,
			'senderEmail' =>$this->config['esign']['email'],
			'senderName'=>$data['sendername'],
			'autoPutUrl'=>$callbackUrl,
			'autoPutDisplayName'=> $fileName,
            //'signers'=>$signer['recipientemailid'],
    		// 'file' => array("fileName" => $fileName,"fileContent" => file_get_contents($docPath.$fileName))
		);
		foreach ($data['fields'] as $key => $field) {
			$returnArray['fields'][$key] = array(
				'pageNumber' => $field['pageNumber'],
				'name' => $data['email'].$key,
				'width' => $field['fieldWidth'],
				'x' => $field['fieldX'],
				'y' => $field['fieldY'],
				'type' => ($field['type']) ? strtoupper($field['type']) : 'SIGNATURE',
				'required' => 1,
				'assignedTo' => array(
					'name' => $data['email'],
					'email' => $data['email']
				),
				'height' => $field['fieldHeight'],
			);
		}
		$returnArray['fields'] = json_encode($returnArray['fields']);
		foreach ($data['signers'] as $signer) {
			$returnArray['participants'][] = array(
				'name' => $signer, 
				'email' => $signer
			);
		}
		$returnArray['participants'] = json_encode($returnArray['participants']);
		return $returnArray;
	}

	public function getDocumentSigningLink($docId){
		$response = $this->restClient->get($this->config['esign']['docurl'].'documents/'.$docId.'/signinglink',array() ,  array( 'Authorization'=> 'Bearer '. $this->getAuthToken()

	));
		return json_decode($response,true);
	}

	public function callBack($uuid, $docId, $ref_id){
		$status = $this->getDocumentStatus($docId);
		if ($status == "FINISHED"){
			$response = $this->restClient->get($this->config['esign']['docurl'].'documents/'.$docId.'/pdf',array() ,  array( 'Authorization'=> 'Bearer '. $this->getAuthToken()

				));	
			$returnData = json_decode($response,true);
			$destination = $this->config['APP_ESIGN_FOLDER'];
			$path = $destination.'/'.$uuid.'/signed/';
			if (!FileUtils::fileExists($path)) {
				FileUtils::createDirectory($path);
			}
			$file = FileUtils::downloadFile($returnData['downloadUrl'],$path.'signed.pdf');
			$esignDocument = new EsignDocument($this->table);
    		$esignDocument->assign($data);

			$this->messageProducer->sendTopic($ref_id, 'DOCUMENT_SIGNED');
			return true;
		}
	}
}