<?php
namespace File;

use File\Controller\FileController;
use Oxzion\Test\ControllerTest;
use Bos\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;

class FileControllerTest extends ControllerTest{
    
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/File.yml");
        return $dataset;
    }

    protected function createDummyFile(){
        $config = $this->getApplicationConfig();
        $tempFolder = $config['DATA_FOLDER']."organization/".$this->testOrgId."/files/temp/";
        FileUtils::createDirectory($tempFolder);
        copy(dirname(__FILE__)."/../files/test-oxzionlogo.png", $tempFolder."test-oxzionlogo.png");
    }
    protected function setDefaultAsserts(){
        $this->assertModuleName('File');
        $this->assertControllerName(FileController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileController');
        $this->assertMatchedRouteName('file');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file', 'GET');
        $this->assertResponseStatusCode(405);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Method Not Found');
    }
    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Test Task 1');
    }
    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test File','status'=>1,'field1'=>1,'field2'=>1,'form_id'=>1];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_file'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['startdate'], $data['startdate']);
        $this->assertEquals($content['data']['enddate'], $data['enddate']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_file'));
    }
    public function testCreateWithOutNameFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['status'=>1,'field1'=>1,'field2'=>1,'form_id'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateWithOutFormIdFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test File','status'=>1,'field1'=>1,'field2'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['form_id'], 'required');
    }
    public function testCreateAccess(){
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Test File','status'=>1,'field1'=>1,'field2'=>1,'form_id'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('File');
        $this->assertControllerName(FileController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileController');
        $this->assertMatchedRouteName('file');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    public function testUpdate(){
        $data = ['name' => 'Test File','status'=>1,'field1'=>1,'field2'=>2];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['field1'], $data['field1']);
        $this->assertEquals($content['data']['field2'], $data['field2']);
    }
    public function testUpdateRestricted(){
        $data = ['name' => 'Test File','status'=>1,'field1'=>1,'field2'=>1];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('File');
        $this->assertControllerName(FileController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileController');
        $this->assertMatchedRouteName('file');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound(){
        $data = ['name' => 'Test File','status'=>1,'field1'=>1,'field2'=>1,'form_id'=>1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1222', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}