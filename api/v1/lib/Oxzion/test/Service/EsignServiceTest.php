<?php
namespace Oxzion\Service;

use Oxzion\Test\AbstractServiceTest;
use Oxzion\Service\EsignService;
use Exception;
use Mockery;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\ResultSet\ResultSet;
use Oxzion\EntityNotFoundException;
use Oxzion\Model\Esign\EsignDocument;
use Oxzion\Model\Esign\EsignDocumentSigner;
use Oxzion\ValidationException; 
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\UuidUtil;

class EsignServiceTest extends AbstractServiceTest
{

    private $adapter = null;
    private $esignService;

    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
        $this->esignService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\EsignService::class); 
        $this->adapter = $this->getDbAdapter();
        $this->adapter->getDriver()->getConnection()->setResource(static::$pdo);
        $this->config = $this->getApplicationConfig();
        $this->folderToClean = NULL;
        $this->authToken = "eyJraWQiOiJDQ08wNUYzQnp6NE03Mjh2eGxVYVVoWk9GdWsycFJ0dFVcL3Y0dVwvS29tdHc9IiwiYWxnIjoiUlMyNTYifQ.eyJzdWIiOiI2bDNscGdzYnJoYTEzdThyZGc5dDVocTNhdSIsInRva2VuX3VzZSI6ImFjY2VzcyIsInNjb3BlIjoiaHR0cHM6XC9cL2xhYi1hcGkuaW5zdXJlc2lnbi5pb1wvZG9jdW1lbnRzLnByZXBhcmUgaHR0cHM6XC9cL2xhYi1hcGkuaW5zdXJlc2lnbi5pb1wvZG9jdW1lbnRzLnNlbmQiLCJhdXRoX3RpbWUiOjE2MDkwNzE2MjQsImlzcyI6Imh0dHBzOlwvXC9jb2duaXRvLWlkcC51cy1lYXN0LTEuYW1hem9uYXdzLmNvbVwvdXMtZWFzdC0xX01ZRVJPSFRUQyIsImV4cCI6MTYwOTA3NTIyNCwiaWF0IjoxNjA5MDcxNjI0LCJ2ZXJzaW9uIjoyLCJqdGkiOiIyYmRjOWFjYi03MzY4LTQ0Y2UtYjE0ZS1iOWVhN2EyNmQ3YmMiLCJjbGllbnRfaWQiOiI2bDNscGdzYnJoYTEzdThyZGc5dDVocTNhdSJ9.IOj_01z-erKoOuyz_Iuj_9MwpgBOWN6nK9Gi2jUfBpO7j0nN8gfl0Pb_J2UI7Prx19DwyeABY-W2v8uwE5B6Lu5HKZh9lvHsoybOvHOn4ftUIHXpAPKtkGYXrUb4s0voTJfUjOI5GFHte8kS-bj6p118hT-bztbTE7hby_V_PLvjmFNT5I8J9f11QWebMbzqCVHCaaB6JHk90iCTlECT8150YkCymr4i8Dxnj8Qhzgxg1kd8c442ZrFatjHWwnHMceC5uSg1gPA70gVEYI2Env2K2mKjOObcVW_WxpiA4-bPQnbjNd601ns_B2_9aS68rO_HsMW9xNBzaF7ST53hoQ";
    }

    public function tearDown() : void {
        parent::tearDown();
        if($this->folderToClean){
            FileUtils::rmDir($this->folderToClean);
        }
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/Dataset/Esign.yml");
        return $dataset;
    }

    public function getMockMessageProducer()
    {
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $this->esignService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    private function getMockRestClient()
    {
        $mockRestClient = Mockery::mock('Oxzion\Utils\RestClient');
        $this->esignService->setRestClient($mockRestClient);
        return $mockRestClient;
    }

    private function getTestFile(){
        $targetFolder = sys_get_temp_dir(). DIRECTORY_SEPARATOR. "EsignServiceTest";
        FileUtils::createDirectory($targetFolder);
        $file = $targetFolder.DIRECTORY_SEPARATOR."temp.txt";
        
        return $file;
    }
    private function saveToFile(string $data){
        $testFile = $this->getTestFile();
        file_put_contents($testFile, $data);
        
        
    }

    private function readFromFile(){
        $testFile = $this->getTestFile();
        $data = file_get_contents($testFile);
        return $data;
    }
    private function mockAuthTokenCall(){
        $mockRestClient = $this->getMockRestClient();
        $clientid = $this->config['esign']['clientid'];
        $clientsecret = $this->config['esign']['clientsecret'];
        $senderemail = $this->config['esign']['email'];
        $username = $this->config['esign']['username'];
        $password = $this->config['esign']['password'];
        $post  = "grant_type=client_credentials&client_id=$clientid&client_secret=$clientsecret&username=$username&password=$password";
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded',
        'Content-Length' => strlen($post));
        $mockRestClient->expects('postWithHeaderAsBody')->with($this->config['esign']['url'], $post, $headers)->once()->andReturn(array("body" => '{"access_token": "'.$this->authToken.'","expires_in":3600,"token_type":"Bearer"}'));
        return $mockRestClient;
    }

    public function testgetAuthToken(){
        if (enableEsign == 0) {
            $mockRestClient = $this->mockAuthTokenCall();
        }
        
        $authToken = $this->esignService->getAuthToken();
        $this->assertEquals(isset($authToken),true);
    }

    //TODO negative test for get auth token

    
    public function testSetupDocumentWithDocument(){

        AuthContext::put(AuthConstants::USER_ID, 1);
        AuthContext::put(AuthConstants::ACCOUNT_ID, 1);
        $fileName = "mockpdf_with_field.pdf";
        $signers = array(
                "name"=>"Signature Document",
                "message"=>"Please sign",
                "signers"=>[['participant' => ["email"=>"eox_user_1@gmail.com", 'name' => 'eox_user_1'],
                            "fields"=> array(
                                array(
                                "name"=>"signature_field",
                                "height"=>50,
                                "width"=>50,
                                "x"=>10,
                                "y"=>84,
                                "pageNumber"=>0
                             )
                            ) ]]);
        $documentUrl = __DIR__."/Files/$fileName";
        if (enableEsign == 0) {
            $mockRestClient = $this->mockAuthTokenCall();
            $data = ["name" => $signers['name'],
                     "message" => $signers['message'],
                     "action" => "send",
                     "fields[0][name]" => $signers['signers'][0]['fields'][0]['name'],
                     "fields[0][height]" => $signers['signers'][0]['fields'][0]['height'],
                     "fields[0][width]" => $signers['signers'][0]['fields'][0]['width'],
                     "fields[0][pageNumber]" => $signers['signers'][0]['fields'][0]['pageNumber'],
                     "fields[0][x]" => $signers['signers'][0]['fields'][0]['x'],
                     "fields[0][y]" => $signers['signers'][0]['fields'][0]['y'],
                     "fields[0][type]" => 'SIGNATURE',
                     "fields[0][required]" => TRUE,
                     "fields[0][assignedTo]" => json_encode(["name" => $signers['signers'][0]['participant']['name'], "email" => $signers['signers'][0]['participant']['email']]),
                     "participants[0][name]" => $signers['signers'][0]['participant']['name'], 
                     "participants[0][email]" => $signers['signers'][0]['participant']['email']
                    ];
            $fields = $signers['signers'][0]['fields'];
            $fields['type'] = 'SIGNATURE';
            $fields['required'] = TRUE;
            $fields['assignedTo'] = json_encode($signers['signers'][0]['participant']);
            $returnData = ["data" => [ "id" => UuidUtil::uuid(),
                                        "name" => $data['name'],
                                        "message" => $data['message'],
                                        "participants" => json_encode([$signers['signers'][0]['participant']]),
                                        "fields" => json_encode($fields),
                                        "action" => "send",
                                        "callback" => "{}",
                                        "group" => UuidUtil::uuid(),
                                        "sender" => "0f3c3dd9-04fa-4e01-b6c2-e9c3340514c0",
                                        "created" => "2020-12-27T12:35:10.203Z"
                                    ]
                            ];
            $fileData = array(FileUtils::getFileName($documentUrl) => $documentUrl );
            $headers = ['Authorization'=> 'Bearer '. $this->authToken];
            $mockRestClient->expects('postMultiPart')->with($this->config['esign']['docurl']."documents", $data, $fileData, $headers)->once()->andReturn(json_encode($returnData));
            
            
        }
        
        $refId = '20';
        
        $docId = $this->esignService->setupDocument($refId, $documentUrl, $signers);
        $this->saveToFile($docId);
        if (enableEsign == 0) {
            $this->assertEquals($returnData['data']['id'], $docId);    
        }
        $this->assertEquals(isset($docId),true);
        $query = "SELECT * from ox_esign_document where doc_id = '".$docId."'";
        $result = $this->executeQueryTest($query);
        $destination = $this->config['APP_ESIGN_FOLDER'];
        $destination .= "/".$result[0]['uuid'];
        $this->folderToClean = $destination;
        $this->assertEquals(1, count($result));
        $this->assertEquals($refId, $result[0]['ref_id']);
        $this->assertEquals(EsignDocument::IN_PROGRESS, $result[0]['status']);
        $this->assertEquals(1, $result[0]['created_by']);
        $this->assertEquals(true, FileUtils::fileExists($destination."/$fileName"));
        $query = "SELECT * from ox_esign_document_signer where esign_document_id = ".$result[0]['id'];
        $result = $this->executeQueryTest($query);
        $this->assertEquals(1, count($result));
        $this->assertEquals(EsignDocumentSigner::IN_PROGRESS, $result[0]['status']);
        $this->assertEquals($signers['signers'][0]['participant']['email'], $result[0]['email']);
        $this->assertEquals($signers['signers'][0], json_decode($result[0]['details'], true) );
        
    }

     
    public function testGetStatus(){
        $docId = $this->readFromFile();
        if (enableEsign == 0) {
            $mockRestClient = $this->mockAuthTokenCall();
            $headers = ['Authorization'=> 'Bearer '. $this->authToken];
            $returnData = ['data' => ['status' => 'READY_FOR_SIGNATURE']];
            $mockRestClient->expects('get')->with($this->config['esign']['docurl']."documents/$docId", [], $headers)->once()->andReturn(json_encode($returnData));
        }
        $status = $this->esignService->getDocumentStatus($docId);
        $this->assertEquals(isset($status),true);
        $this->assertEquals('READY_FOR_SIGNATURE', $status);
    }

    public function testGetSigningLink(){
        $docId = $this->readFromFile();
        if (enableEsign == 0) {
            $mockRestClient = $this->mockAuthTokenCall();
            $headers = ['Authorization'=> 'Bearer '. $this->authToken];
            $returnData = ['signingLink' => " https://lab.insuresign.com?d=YjhiOTI4MDktNTQ5NC00MmExLWExNDktZDljNjg3ZDVkNjA5JmVveF91c2VyXzFAZ21haWwuY29t"];
            $mockRestClient->expects('get')->with($this->config['esign']['docurl']."documents/$docId/signinglink", [], $headers)->once()->andReturn(json_encode($returnData));
        }
        $data = $this->esignService->getDocumentSigningLink($docId);
        $this->assertEquals(isset($data),true);
        if(enableEsign == 0){
            $this->assertEquals($returnData['signingLink'], $data);
        }
    }

    public function testSignEvent(){
        $docId = $this->readFromFile();
        $query = "UPDATE ox_esign_document SET doc_id ='$docId' WHERE id=1 ";
        $result = $this->executeUpdate($query);
        $query = "SELECT * from ox_esign_document where doc_id = '$docId'";
        $docTable = $this->executeQueryTest($query);
        $destination = $this->config['APP_ESIGN_FOLDER'];
        $destination .= "/".$docTable[0]['uuid'];
        $this->folderToClean = $destination;
        $destination .= '/signed/signed.pdf';
        if (enableEsign == 0) {
            $mockRestClient = $this->mockAuthTokenCall();
            $headers = ['Authorization'=> 'Bearer '. $this->authToken];
            $returnData = ["downloadUrl"=> __DIR__."/Files/mockpdf_with_field.pdf"];
            $mockRestClient->expects('get')->with($this->config['esign']['docurl'].'documents/'.$docId.'/pdf', [], $headers)->once()->andReturn(json_encode($returnData));
            
        }
        $mockMessageProducer = $this->getMockMessageProducer();
        $payload = json_encode(['file'   => $destination,
                                      'refId' => $docTable[0]['ref_id']]);
        $mockMessageProducer->expects('sendTopic')->with($payload,'DOCUMENT_SIGNED')->once()->andReturn();
        $data = $this->esignService->signEvent($docId,'FINALIZED');
        $docTable = $this->executeQueryTest($query);

        $query = "SELECT * from ox_esign_document_signer where esign_document_id =1 ";
        $signerTable = $this->executeQueryTest($query);
        $this->assertEquals($docTable[0]['status'],EsignDocument::COMPLETED);
        $this->assertEquals($signerTable[0]['status'],EsignDocument::COMPLETED);
        $this->assertEquals(true,FileUtils::fileExists($destination));

    }
}