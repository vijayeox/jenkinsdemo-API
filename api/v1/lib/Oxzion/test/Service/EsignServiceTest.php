<?php
namespace Oxzion\Service;

use Oxzion\Test\AbstractServiceTest;
use Oxzion\Service\EsignService;
use Exception;
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
        $this->config = $config = $this->getApplicationConfig();
        $this->folderToClean = NULL;
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

    public function testgetAuthToken(){
       $authToken = $this->esignService->getAuthToken();
       $this->assertEquals(isset($authToken),true);
    }

    //TODO negative test for get auth token

    public function testSetupDocumentWithDocument(){

        AuthContext::put(AuthConstants::USER_ID, 1);
        AuthContext::put(AuthConstants::ACCOUNT_ID, 1);
        $refId = '20';
        $fileName = "mockpdf_with_field.pdf";
        $documentUrl = __DIR__."/Files/$fileName";
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
        $docId = $this->esignService->setupDocument($refId, $documentUrl, $signers);
        $this->saveToFile($docId);
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
        $status = $this->esignService->getDocumentStatus($docId);
        $this->assertEquals(isset($status),true);
        $this->assertEquals('FIELD_PLACEMENT', $status);
    }

    public function testGetSigningLink(){
        $docId = $this->readFromFile();
        $data = $this->esignService->getDocumentSigningLink($docId);
        $this->assertEquals(isset($data),true);
        $this->assertEquals(isset($data['signingLink']),true);
    }

    public function testSignEvent(){
        $docId = $this->readFromFile();
        $query = "UPDATE ox_esign_document SET doc_id ='$docId' WHERE id=1 ";
        $result = $this->executeUpdate($query);
        $data = $this->esignService->signEvent($docId,'FINALIZED');
        $query = "SELECT * from ox_esign_document where doc_id = '".$docId."'";
        $docTable = $this->executeQueryTest($query);

        $query = "SELECT * from ox_esign_document_signer where esign_document_id =1 ";
        $signerTable = $this->executeQueryTest($query);
        $destination = $this->config['APP_ESIGN_FOLDER'];
        $destination .= "/".$docTable[0]['uuid'].'/signed/';
        $this->folderToClean = $destination;
        $this->assertEquals($docTable[0]['status'],EsignDocument::COMPLETED);
        $this->assertEquals($signerTable[0]['status'],EsignDocument::COMPLETED);
        $this->assertEquals(true,FileUtils::fileExists($destination."/signed.pdf"));

    }
}