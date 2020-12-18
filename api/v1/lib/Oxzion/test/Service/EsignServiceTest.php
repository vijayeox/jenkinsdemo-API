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
use PaymentGateway\Model\EsignDocument;
use PaymentGateway\Model\EsignDocumentSigner;
use Oxzion\ValidationException;	

class EsignServiceTest extends AbstractServiceTest
{

    private $adapter = null;

    private $esignService;

    protected function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
        $this->EsignService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\EsignService::class); 
        $this->adapter = $this->getDbAdapter();
        $this->adapter->getDriver()->getConnection()->setResource(static::$pdo);
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/Dataset/File.yml");
        return $dataset;
    }

    private function runQuery($query) {
        $statement = $this->adapter->query($query);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $result = $resultSet->initialize($result)->toArray();
        return $result; 
    }    

    public function testgetAuthToken(){
       $authToken = $this->EsignService->getAuthToken();
       $this->assertEquals(isset($authToken),true);
   }

    //TODO negative test for get auth token

   public function testSetupDocumentWithDocument(){

    AuthContext::put(AuthConstants::USER_ID, 1);
    AuthContext::put(AuthConstants::ACCOUNT_ID, 1);
    $ref_id = '20';
    $documentUrl = __DIR__."/Files/";
    $signers = array(
            "name"=>"signature_field",
            "email"=>"eox_user_1@gmail.com",
            "message"=>"Please sign",
            "action"=>"SIGNINGLINK",
            "sendername"=>"eox admin",
            "signers"=>array("0"=>"eox_user_1@gmail.com"),
            "fields"=> array(
                 "0" =>   array(
                "name"=>"signfield",
                "fieldHeight"=>"50",
                "fieldWidth"=>"50",
                "fieldX"=>"10",
                "fieldY"=>"84",
                "pageNumber"=>"0",
                "required"=>"true",
                "type"=>"SIGNATURE")
             )
            );
    $doc_id = $this->EsignService->setupDocument($ref_id, $documentUrl, $signers);
    $this->assertEquals(isset($doc_id),true);

    }

     
    public function testGetStatus(){
        $docId = '83303bb4-4819-45dc-9e09-efa4f64b54a8';
       $status = $this->EsignService->getDocumentStatus($docId);
       $this->assertEquals(isset($status),true);
   }

    public function testGetSigningLink(){
        $docId = '79e36ce6-9267-4ed6-9bb0-3dc1076b85a7';
       $data = $this->EsignService->getDocumentSigningLink($docId);
       $this->assertEquals(isset($data),true);
   }
   public function testCallback(){
    //work for test uuid
    $uuid = '09ab7b0f-8141-45a8-9357-4f7c9d6af576';
    $docId = '79e36ce6-9267-4ed6-9bb0-3dc1076b85a7';
    $ref_id = '20';
    $data = $this->EsignService->callBack($uuid,$docId,$ref_id);
    $this->assertEquals($data,true);
    }
}