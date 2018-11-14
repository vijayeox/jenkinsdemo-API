<?php
namespace Form;

use Form\Controller\FormController;
use Form\Model;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;

class FormControllerTest extends ControllerTest{
    
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../../../File/test/Dataset/File.yml");
        return $dataset;
    }

    protected function tearDown() : void {
        $config = $this->getApplicationConfig();
        $formsFolder = $config['DATA_FOLDER']."organization/1/forms";
        FileUtils::deleteDirectoryContents($formsFolder);
    }
    protected function setDefaultAsserts(){
        $this->assertModuleName('Form');
        $this->assertControllerName(FormController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FormController');
        $this->assertMatchedRouteName('form');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/form', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['name'], 'Test Form 1');
        $this->assertEquals($content['data'][1]['id'], 2);
        $this->assertEquals($content['data'][1]['name'], 'Test Form 2');
    }
    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/form/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Test Form 1');
    }
    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/form/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Form 1','app_id'=>1,'statuslist'=>'[{"data":[{"1":"assigned"},{2:"In progress"},{3:"Completed"}]}]'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_form'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['startdate'], $data['startdate']);
        $this->assertEquals($content['data']['enddate'], $data['enddate']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_form'));
    }
    public function testCreateWithOutNameFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['app_id'=>1,'statuslist'=>'[{"data":[{"1":"assigned"},{2:"In progress"},{3:"Completed"}]}]'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }
    public function testCreateWithOutAppFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Form 1','statuslist'=>'[{"data":[{"1":"assigned"},{2:"In progress"},{3:"Completed"}]}]'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['app_id'], 'required');
    }

    public function testCreateAccess(){
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Test Form 1','app_id'=>1,'statuslist'=>'[{"data":[{"1":"assigned"},{2:"In progress"},{3:"Completed"}]}]'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Form');
        $this->assertControllerName(FormController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FormController');
        $this->assertMatchedRouteName('form');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    public function testUpdate(){
        $data = ['name' => 'Test Form 1','app_id'=>1,'statuslist'=>'[{"data":[{"1":"assigned"},{2:"In progress"},{3:"Completed"}]}]','field1'=>1,'field2'=>2];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['field1'], $data['field1']);
        $this->assertEquals($content['data']['field2'], $data['field2']);
    }
    public function testUpdateRestricted(){
        $data = ['name' => 'Test Form 1','app_id'=>1,'statuslist'=>'[{"data":[{"1":"assigned"},{2:"In progress"},{3:"Completed"}]}]'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form/1', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Form');
        $this->assertControllerName(FormController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FormController');
        $this->assertMatchedRouteName('form');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound(){
        $data = ['name' => 'Test Form 1','app_id'=>1,'statuslist'=>'[{"data":[{"1":"assigned"},{2:"In progress"},{3:"Completed"}]}]'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/form/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/form/1222', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}