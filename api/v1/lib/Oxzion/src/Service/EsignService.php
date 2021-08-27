<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Oxzion\Model\Esign\EsignDocument;
use Oxzion\Model\Esign\EsignDocumentTable;
use Oxzion\Model\Esign\EsignDocumentSigner;
use Oxzion\Model\Esign\EsignDocumentSignerTable;
use Oxzion\Messaging\MessageProducer;
use Oxzion\OxServiceException;
use Oxzion\Utils\RestClient;
use Oxzion\Utils\FileUtils;

class EsignService extends AbstractService
{
    private $table;
    private $signerTable;
    private $messageProducer;
    private $restClient;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, EsignDocumentTable $table, EsignDocumentSignerTable $signerTable, MessageProducer $messageProducer)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->signerTable = $signerTable;
        $this->messageProducer = $messageProducer;
        $this->restClient = new RestClient($this->config['esign']['url']);
    }

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }
    
    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
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
     *
     *  @return access token
     *
     */
    public function setupDocument($ref_id, $documentUrl, array $signers)
    {
        if (!isset($documentUrl) || !FileUtils::fileExists($documentUrl) || is_dir($documentUrl)) {
            throw new ServiceException("Document not found", 'doc.not.found', OxServiceException::ERR_CODE_PRECONDITION_FAILED);
        }
        if (!$signers || count($signers) == 0) {
            throw new ServiceException("signers not provided", 'signers.not.provided', OxServiceException::ERR_CODE_PRECONDITION_FAILED);
        }
        $data = array();
        $data['ref_id'] = $ref_id;
        $data['docPath'] = $documentUrl;
        $esignDocument = new EsignDocument($this->table);
        $esignDocument->assign($data);
        try {
            $this->beginTransaction();
            $esignDocument->save();
            $generated = $esignDocument->getGenerated(true);
            $data['uuid'] = $generated['uuid'];
            $id = $generated['id'];
            $path = $this->copySourceDocument($documentUrl, $data['uuid']);
            $this->setupSubscriptions();
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


    private function saveDocumentSigner($data, $documentId)
    {
        $signer = array();
        $signer['email'] = $data['participant']['email'];
        $signer['esign_document_id'] = $documentId;
        $signer['details'] = json_encode($data);
        $docSigner = new EsignDocumentSigner($this->signerTable);
        $docSigner->assign($signer);
        $docSigner->save();
    }

    /**
     * copySourceDocument
     *
     * coppyin the document from dource to destination
     *
     *  @param  $documentUrl
     *  @return destination adress
     */

    private function copySourceDocument($documentUrl, $uuid)
    {
        $destination = $this->config['APP_ESIGN_FOLDER'];
        $path = $destination.'/'.$uuid;
        if (!FileUtils::fileExists($path)) {
            FileUtils::createDirectory($path);
        }
        $filename = FileUtils::getFileName($documentUrl);
        FileUtils::copy($documentUrl, $filename, $path);
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
    private function uploadDocument($docUrl, array $signers)
    {
        $data = $this->assignData($docUrl, $signers);
        $fileData = array(FileUtils::getFileName($docUrl) => $docUrl );
        $headers = array( 'Authorization'=> 'Bearer '. $this->getAuthToken() );
        $response = $this->restClient->postMultiPart($this->config['esign']['docurl'].'documents', $data, $fileData, $headers);
        $returnDocId = json_decode($response, true);
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

    public function getAuthToken()
    {
        $clientid = $this->config['esign']['clientid'];
        $clientsecret = $this->config['esign']['clientsecret'];
        $senderemail = $this->config['esign']['email'];
        $username = $this->config['esign']['username'];
        $password = $this->config['esign']['password'];
        $post  = "grant_type=client_credentials&client_id=$clientid&client_secret=$clientsecret&username=$username&password=$password";
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded',
            'Content-Length' => strlen($post));
        $response = $this->restClient->postWithHeaderAsBody($this->config['esign']['url'], $post, $headers);
        $authToken = json_decode($response['body'], true);
        return $authToken['access_token'];
    }

    public function getDocumentStatus($docId)
    {
        $this->logger->info("status for doc -".$docId);
        $auth = $this->getAuthToken();
        $response = $this->restClient->get(
            $this->config['esign']['docurl'].'documents/'.$docId,
            array(),
            array( 'Authorization'=> 'Bearer '. $auth )
        );
        $data = json_decode($response, true);
        $this->logger->info("response doc -".$docId." is\n".print_r($data, true));
        if (isset($data['data']) && isset($data['data']['status']) && $data['data']['status'] == "FINALIZED") {
            $this->processFinalized($docId);
        }
        return $data['data']['status'];
    }

    private function assignData($docUrl, $data)
    {
        $fileName = FileUtils::getFileName($docUrl);
        $callbackUrl = $this->config['esign']['callbackUrl'];
        $returnArray = array(
            'name' => $data['name'],
            'message' => isset($data['message']) ? ($data['message']) : 'Please sign here',
            'action' => 'send',
            'callback[url]' => $callbackUrl,
            'callback.url' => $callbackUrl
        );

        if (isset($data['cc']) && is_array($data['cc'])) {
            $ccList = array();
            foreach ($data['cc'] as $cc) {
                $ccList[] = ['name' => $cc['name'],
                'email' => $cc['email']];
            }
            $data['cc'] = json_encode($ccList);
        }
        
        foreach ($data['signers'] as $signer) {
            foreach ($signer['fields'] as $key => $field) {
                $fields = array(
                    "fields[$key][name]" => $field['name'],
                    "fields[$key][height]" => $field['height'],
                    "fields[$key][width]" => $field['width'],
                    "fields[$key][pageNumber]" => $field['pageNumber'],
                    "fields[$key][x]" => $field['x'],
                    "fields[$key][y]" => $field['y'],
                    "fields[$key][type]" => 'SIGNATURE',
                    "fields[$key][required]" => true,
                    "fields[$key][assignedTo]" =>json_encode(array("name" => $signer['participant']['name'],"email" => $signer['participant']['email'])),
                    "participants[$key][name]" => $signer['participant']['name'],
                    "participants[$key][email]" => $signer['participant']['email']
                );
                $returnArray = array_merge($returnArray, $fields);
            }
        }

        $this->logger->info("Data of Doc -".json_encode($returnArray));
        return $returnArray;
    }

    private function setupSubscriptions()
    {
        $this->setCallbackUrl();
        $return = $this->restClient->get(
            $this->config['esign']['docurl']."integrations/VANTAGE/subscriptions",
            array(),
            array( 'Authorization'=> 'Bearer '. $this->getAuthToken() )
        );
        $response = json_decode($return, true);
        $this->logger->info("subscription Setup Response -".$return);
        if (isset($response)) {
            $subscribe = array(
                //"SIGNED" => false,
                "FINALIZED" => false
            );
            foreach ($response['data'] as $event) {
                if (isset($subscribe[$event['eventType']]) && $subscribe[$event['eventType']] == false) {
                    $subscribe[$event['eventType']] = true;
                } else {
                    $this->deleteSubscription($event['id']);
                }
            }
            foreach ($subscribe as $eventType => $value) {
                if ($value) {
                    $this->addSubcription($eventType);
                }
            }
            return true;
        }
    }

    private function addSubcription($hook)
    {
        $header = array( "Authorization"=>"Bearer ". $this->getAuthToken()
    );
        $post = array(
            "eventType" => $hook
        );
        $response = $this->restClient->postWithHeader($this->config['esign']['docurl']."subscriptions", $post, $header);
        // $result = json_decode($response,true);
        $this->logger->info("Add subscription Response -".json_encode($response));
        if (!isset($response)) {
            return false;
        } else {
            return true;
        }
    }

    private function deleteSubscription($subscriptionId)
    {
        $header = array( "Authorization"=>"Bearer ". $this->getAuthToken());
        $url = $this->config['esign']['docurl']."integrations/VANTAGE/subscriptions/".$subscriptionId;
        $response = $this->restClient->delete($url, array(), $header);
        $this->logger->info("remove subscription Response -".$response);
    }

    private function setCallbackUrl()
    {
        $header = array( "Authorization"=>" Bearer ". $this->getAuthToken(),
            "content-type"=>" application/json"
        );
        $putData = array(
            "callbackUrl" => $this->config['esign']['callbackUrl']
        );
        $return = $this->restClient->put($this->config['esign']['docurl']."integrations/VANTAGE", $putData, $header);
    }
    

    public function getDocumentSigningLink($docId)
    {
        $authToken = $this->getAuthToken();
        $this->logger->info("signing link for doc -".$docId);
        $response = $this->restClient->get($this->config['esign']['docurl'].'documents/'.$docId.'/signinglink', array(), array( 'Authorization'=> 'Bearer '. $authToken    ));
        $response = json_decode($response, true);
        return $response['signingLink'];
    }

    public function signEvent($docId, $event)
    {
        $this->logger->info("signing event called for doc -".$docId);
        if ($event == "FINALIZED") {
            $this->processFinalized($docId);
        }
    }
    private function processFinalized($docId)
    {
        $sql = $this->getSqlObject();
        $getID = $sql->select();
        $getID->from('ox_esign_document')
            ->columns(array("docPath","status","ref_id","uuid"))
            ->where(array('doc_id' => $docId));
        $responseID = $this->executeQuery($getID)->toArray();
        if (count($responseID) > 0 && $responseID[0]['status'] != EsignDocument::COMPLETED) {
            $fileName = $this->downloadFile($docId, $responseID[0]['uuid']);
            try {
                $this->beginTransaction();
                $query = "UPDATE ox_esign_document SET status=:status WHERE doc_id=:docId";
                $param = array('status' => EsignDocument::COMPLETED, 'docId' => $docId);
                $this->executeUpdateWithBindParameters($query, $param);
                $query = "UPDATE ox_esign_document_signer as ds INNER JOIN ox_esign_document as d ON d.id = ds.esign_document_id SET ds.status=:status WHERE d.doc_id=:docId";
                $param['status'] = EsignDocumentSigner::COMPLETED;
                $this->executeUpdateWithBindParameters($query, $param);
                $this->commit();
                $this->logger->info("get Document Info -".json_encode($responseID));
                if (!empty($responseID[0]["docPath"])) {
                    $destinationPath = $responseID[0]["docPath"];
                    if (FileUtils::fileExists($destinationPath)) {
                        FileUtils::deleteFile($destinationPath, null);
                    }
                    $this->logger->info("Move original File -".$fileName);
                    copy($fileName, $destinationPath);
                    $this->logger->info("Destination -".$destinationPath);
                }
                $refId = $responseID[0]['ref_id'];
                $fileRef = explode("_", $refId);
                $fileId = $fileRef[0];
                $data = json_encode(array('file'   => $fileName,
                                          'refId' => $refId));
                $this->messageProducer->sendTopic($data, 'DOCUMENT_SIGNED');
            } catch (Exception $e) {
                $this->logger->info("status for doc -".$e->getMessage());
                $this->rollback();
                throw $e;
            }
        } else {
            return;
        }
    }

    private function downloadFile($docId, $uuid=null)
    {
        $response = $this->restClient->get($this->config['esign']['docurl'].'documents/'.$docId.'/pdf', array(), array( 'Authorization'=> 'Bearer '. $this->getAuthToken()));
        $this->logger->info("Download Doc info -".$response);
        $returnData = json_decode($response, true);
        $destination = $this->config['APP_ESIGN_FOLDER'];
        $path = $destination.'/'.$uuid.'/signed/';
        if (!FileUtils::fileExists($path)) {
            FileUtils::createDirectory($path);
        }
        $file = $path.'signed.pdf';
        $this->logger->info("Signed File -".$file);
        FileUtils::downloadFile($returnData['downloadUrl'], $file);
        return $file;
    }

    private function getDataFromDocId(array $columns, $docId)
    {
        $sql = $this->getSqlObject();
        $getID = $sql->select();
        $getID->from('ox_esign_document')
            ->columns($columns)
            ->where(array('doc_id' => $docId));
        $responseID = $this->executeQuery($getID)->toArray();
        if ($responseID && isset($responseID[0]['uuid'])) {
            return $responseID[0]['uuid'];
        } elseif ($responseID && isset($responseID[0]['ref_id'])) {
            return $responseID[0]['ref_id'];
        } else {
            return 0;
        }
    }
}
