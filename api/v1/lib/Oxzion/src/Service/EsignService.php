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
     *  @param $ref_id 
     *  @param $documentUrl 
     *  @param $signers array
     *                  name                                    string      - Name for the document
     *                  message                                 string      - Optional. Message from the sender intended for the each participant and will appear in the e-mail distribution
     *                  cc                                      array       - Optional. List of participants
     *                  cc[].name                               string      - cc participant name
     *                  cc[].email                              string      - cc participant email
     *                  signers                                 array       - The signers
     *                  signers[].fields                        array       - List of signature fields
     -                  signers[].fields[].name                 string      - Unique name given to the field for reference
     *                  signers[].fields[].height               decimal     - The rendering height of the field
     *                  signers[].fields[].width                decimal     - The rendering width of the field
     *                  signers[].fields[].pageNumber           integer     - The page number in which the field will appear on the document
     *                  signers[].fields[].x                    decimal     - The x-coordinate position
     *                  signers[].fields[].y                    decimal     - The y-coordinate position 
     *                  signers[].participant                   object      - Participants who will participate in signing
     *                  signers[].particpant.name               string      - Name of the signing participant
     *                  signers[].particpants.email             string      - Email of the signing participant
     *                  sendEmail                               boolean     - Optional. To indicate if email has to be sent. 
     *
     *  @return access token
     *
     */
    public function setupDocument($ref_id, $documentUrl ,array $signers)
    {
    	if(!isset($documentUrl) || !FileUtils::fileExists($documentUrl) || is_dir($documentUrl)){
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
    		$id = $generated['id'];
    		$path = $this->copySourceDocument($documentUrl, $data['uuid']);
    		$docId = $this->uploadDocument($documentUrl, $signers);
    		foreach ($signers['signers'] as $value) {
    			$this->saveDocumentSigner($value, $id);
    		}
    		$esignDocument->assign(['doc_id' => $docId]);
    		$esignDocument->save();
    		$this->commit();
    		return $docId;
    	} catch (Exception $e) {
    		$this->rollback();
    		throw $e;
    	}
    }

    
    private function saveDocumentSigner($data, $documentId){
    	$signer = array();
    	$signer['email'] = $data['participant']['email'];
    	$signer['esign_document_id'] = $documentId;
    	$signer['details'] = json_encode($data);
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
    	FileUtils::copy($documentUrl,$filename,$path);
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
    private function uploadDocument($docUrl, array $signers){
    	$data = $this->assignData($docUrl, $signers);
    	$response = $this->restClient->postMultiPart($this->config['esign']['docurl'].'documents', $data, array(FileUtils::getFileName($docUrl) => $docUrl ),  array( 'Authorization'=> 'Bearer '. $this->getAuthToken() ));
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
		$response = $this->restClient->get($this->config['esign']['docurl'].'documents/'.$docId,array() ,  
											array( 'Authorization'=> 'Bearer '. $this->getAuthToken() ));
		$data = json_decode($response,true);
		return $data['data']['status'];

	}

	private function assignData($docUrl, $data){
		$fileName = FileUtils::getFileName($docUrl);
		$callbackUrl = $this->config['esign']['callbackUrl'];
		$returnArray = array(
			'name' => $data['name'],
			'message' => isset($data['message']) ? ($data['message']) : 'Please sign here',
			'action' => isset($data['sendEmail']) && $data['sendEmail'] ? 'EMAIL' : 'SIGNINGLINK'
		);

		if(isset($data['cc']) && is_array($data['cc'])){
			$ccList = array();
			foreach ($data['cc'] as $cc) {
				$ccList[] = ['name' => $cc['name'],
										'email' => $cc['email']];
			}
			$data['cc'] = json_encode($ccList);
		}
		
		$fields = [];
		$participants = [];
		foreach ($data['signers'] as $signer) {
			foreach ($signer['fields'] as $field) {
				$fields[] = array(
					'name' => $field['name'],
					'height' => $field['height'],
					'width' => $field['width'],
					'pageNumber' => $field['pageNumber'],
					'x' => $field['x'],
					'y' => $field['y'],
					'type' => 'SIGNATURE',
					'required' => TRUE,
					'assignedTo' => array(
						'name' => $signer['participant']['name'],
						'email' => $signer['participant']['email']
					),
					
				);
			}
			$participants[] = array(
				'name' => $signer['participant']['name'], 
				'email' => $signer['participant']['email']
			);
		}
		
		$returnArray['fields'] = json_encode($fields);
		$returnArray['participants'] = json_encode($participants);
		print_r($returnArray);
		return $returnArray;
	}

	private function subscriptions(){
		$return = $this->restClient->get($this->config['esign']['docurl']."integrations/VANTAGE/subscriptions", array(), 
                                            array( "Authorization: Bearer". $this->getAuthToken() ));
		$response = json_decode($return,true);
		if(!isset($response)){
			$subscribe = array(
				//"SIGNED" => false,
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
		$header = array( "Authorization: Bearer". $this->getAuthToken()
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
		$header = array( "Authorization: Bearer". $this->getAuthToken()
	);
		$response = $this->restClient->delete($this->config['esign']['docurl']."integrations/VANTAGE/subscriptions/".$subscriptionId, array(),$header);
		return json_decode($response, true);
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
		}
	}
}