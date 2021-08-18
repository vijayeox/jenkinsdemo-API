<?php
namespace Prehire;

use Oxzion\Test\ControllerTest;
use Prehire\Controller\PrehireController;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class PrehireControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Prehire.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Prehire');
        $this->assertControllerName(PrehireController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PrehireController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
      public function testgetPrehireDetails(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/prehire/foley/4fd99e8e-758f-11e9-b2d5-68ecc57cde47/4fd99e8e-758f-11e9-b2d5-68ecc57cde45','GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        // print_r($content);exit;
        $projectId=$content['data']['uuid'];
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['request_type'], 'MVR');
        $selectquery="SELECT * FROM ox_prehire WHERE uuid='".$projectId."'";
        $select = $this->executeQueryTest($selectquery);
        // print_r($select);exit;
        $this->assertEquals($content['data']['request_type'],$select[0]['request_type']);
          }

    public function testgetPrehireDetailswithInvalidPrehireId(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/prehire/foley/4fd99e8e-758f-11e9-b2d5-68ecc57cde47/4fd99e8e-758f-11e9-b2d5-68ecc57cde46','GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        // print_r($content);exit;
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'The uuid $uuid provided does not exist');
       
    }
     
    public function testUpdate()
        {
        $data=['request_type'=>'MVRR','request'=>'{something hi}'];
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/prehire/foley/4fd99e8e-758f-11e9-b2d5-68ecc57cde47/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'PUT',$data); 
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        // print_r($content);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('prehire');
        $this->assertEquals($content['data']['request_type'],'MVRR');
        $this->assertEquals($content['data']['request'],'{something hi}');
        $selectquery="SELECT * FROM ox_prehire WHERE request_type='MVRR'";
        $select = $this->executeQueryTest($selectquery);
        // print_r($select);exit;
        $this->assertEquals($content['data']['request_type'],$select[0]['request_type']);
        $this->assertEquals($content['data']['request'],$select[0]['request']);

        
        }
        public function testUpdatewithWrongPrehireId()
        {
        $data=['request_type'=>'MVRR','request'=>'{something hi}'];
        $this->initAuthToken($this->adminUser);
        // $this->setJsonContent(json_encode($data));
        $this->dispatch('/prehire/foley/4fd99e8e-758f-11e9-b2d5-68ecc57cde47/4fd99e8e-758f-11e9-b2d5-68ecc57cde46', 'PUT',$data); 
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        // print_r($content);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('prehire');
        $this->assertEquals($content['status'],'error');
        
        
        }

     public function testCreatePrehire()
    {
       
        $this->initAuthToken($this->adminUser);
        $data=['uuid'=>'4fd99e8e-758f-11e9-b2d5-68ecc57cde48','user_id'=>170,'request_type'=>'MVR','request'=>'{something ooooo}','implementation'=>'foley'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/prehire/foley/4fd99e8e-758f-11e9-b2d5-68ecc57cde49', 'POST',$data); 
        $content = json_decode($this->getResponse()->getContent(), true);
        // print_r($content);exit;
        $this->assertResponseStatusCode(201);
        $this->assertMatchedRouteName('prehire');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['request_type'],'MVR');
        $this->assertEquals($content['data']['implementation'],'foley');
        $this->assertEquals($content['data']['request'],'{something ooooo}');
        $selectquery="SELECT * FROM ox_prehire WHERE user_id=170";
        $select = $this->executeQueryTest($selectquery);
        // print_r($select);exit;
        $this->assertEquals($content['data']['uuid'],$select[0]['uuid']);
               
        }
        public function testCreatePrehireWithoutdata()
        {
           
            $this->initAuthToken($this->adminUser);
            $data=['request_type'=>'MVR','request'=>'{something ooooo}','implementation'=>'foley'];
            $this->setJsonContent(json_encode($data));
            $this->dispatch('/prehire/foley/4fd99e8e-758f-11e9-b2d5-68ecc57cde47/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'POST',null); 
            $content = json_decode($this->getResponse()->getContent(), true);
            $this->assertResponseStatusCode(404);
            $this->assertMatchedRouteName('prehire');
            $this->assertEquals($content['status'], 'error');
                   
        }
        public function testDeletePrehireDetails(){
            $this->initAuthToken($this->adminUser);
            $this->dispatch('/prehire/foley/4fd99e8e-758f-11e9-b2d5-68ecc57cde47/4fd99e8e-758f-11e9-b2d5-68ecc57cde45','DELETE');
            $content = json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'success');
            $selectquery="SELECT * FROM ox_prehire WHERE uuid='4fd99e8e-758f-11e9-b2d5-68ecc57cde45'";
            $select = $this->executeQueryTest($selectquery);
            // print_r($select);exit;
            
            }
        public function testDeleteWithInvalidPrehireId(){

            $this->initAuthToken($this->adminUser);
            $this->dispatch('/prehire/foley/4fd99e8e-758f-11e9-b2d5-68ecc57cde47/4fd99e8e-758f-11e9-b2d5-68ecc57cde46','DELETE');
            //$uuid='4fd99e8e-758f-11e9-b2d5-68ecc57cde46';
            $content = json_decode($this->getResponse()->getContent(), true);
            //  print_r($content);exit;
            $this->assertEquals($content['status'], 'error');
            $this->assertEquals($content['message'],'Prehire entry not found');
            $this->assertEquals($content['errorCode'], '404');
                }
                
        


    
}
