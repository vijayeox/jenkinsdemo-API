<?php
namespace Oxzion\Service;

use Oxzion\Test\AbstractServiceTest;
use Prehire\Service\PrehireService;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\ResultSet\ResultSet;

class PrehireServiceTest extends AbstractServiceTest
{
    private $adapter = NULL;
    private $prehireService;

    /**
     * Method executes before every test, Initialise variables and open file connection
     */
    protected function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
        $this->prehireService = $this->getApplicationServiceLocator()->get(\Prehire\Service\PrehireService::class);
        $this->adapter = $this->getDbAdapter();
        $this->adapter->getDriver()->getConnection()->setResource(static::$pdo);
    }

    /**
     *  Prepare dataset for project to compare the actual contents of a database against the expected contents.
     */
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/Dataset/Prehire.yml");
        return $dataset;
    }
    private function runQuery($query)
    {
        $statement = $this->adapter->query($query);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $result = $resultSet->initialize($result)->toArray();
        return $result;
    }
    public function testgetRequest(){
        
        $uuid='4fd99e8e-758f-11e9-b2d5-68ecc57cde45';
        $getData=$this->prehireService->getPrehireRequestData($uuid);
        // print_r($getData);exit;
        $sqlQuery = "SELECT * FROM ox_prehire WHERE uuid = '".$uuid."'";
        $result = $this->runQuery($sqlQuery);
        // print_r($result);exit;
        $this->assertEquals($getData['request_type'],$result[0]['request_type']);
        $this->assertEquals($getData['request'],$result[0]['request']);
        $this->assertEquals($getData['user_id'],$result[0]['user_id']);
        // print_r($result);exit;
        
    }

    public function testcreate(){
        $data=['uuid'=>'4fd99e8e-758f-11e9-b2d5-68ecc57cde48','user_id'=>170,'referenceId'=>'4fd99e8e-758f-11e9-b2d5-68ecc57cde49','request_type'=>'MVR','request'=>'{something ooooo}','implementation'=>'foley'];
        $create=$this->prehireService->createRequest($data);
        // print_r($create);exit;
        $uuid='4fd99e8e-758f-11e9-b2d5-68ecc57cde48';
        $sqlQuery = "SELECT * FROM ox_prehire WHERE uuid = '".$uuid."'";
        $result = $this->runQuery($sqlQuery);
        $this->assertEquals($create['uuid'],$result[0]['uuid']);
        //  print_r($result);

    }

    public function testUpdate(){
        $data=['request_type'=>'MVRR','request'=>'{something hi}'];
        $uuid='4fd99e8e-758f-11e9-b2d5-68ecc57cde45';
        $update=$this->prehireService->updateRequest($uuid,$data);
        // print_r($update);exit;
        $sqlQuery = "SELECT * FROM ox_prehire WHERE uuid = '".$uuid."'";
        $result = $this->runQuery($sqlQuery);
        // print_r($result[0]['uuid']);
        $this->assertEquals($update['uuid'],$result[0]['uuid']);
    }

    
   public function testdelete(){
      $uuid='4fd99e8e-758f-11e9-b2d5-68ecc57cde45';
      $delete=$this->prehireService->deleteRequest($uuid);
   
      $sqlquery="SELECT * FROM ox_prehire WHERE uuid = '".$uuid."'";
      

      $result=$this->runQuery($sqlquery);
    
      $this->assertEquals(NULL, $delete);

   }
}