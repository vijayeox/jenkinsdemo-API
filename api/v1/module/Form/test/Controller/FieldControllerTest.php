<?php
namespace Form;

use Form\Controller\FieldController;
use Zend\Stdlib\ArrayUtils;
use Form\Model\Field;
use Oxzion\Test\ControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class FieldControllerTest extends ControllerTest{
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../../../File/test/Dataset/File.yml");
        return $dataset;
    }

    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/form/1/field', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['name'], 'field1');
        $this->assertEquals($content['data'][1]['id'], 2);
        $this->assertEquals($content['data'][1]['name'], 'field2');
    }

    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/form/1/field/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'field1');
        
    }

    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/form/1/field/122', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
    }


    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Field 1','required'=>1,'sequence'=>1,'data_type'=>'text'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form/1/field', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 3);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['required'], $data['required']);
        $this->assertEquals($content['data']['data_type'], $data['data_type']);
    }
    public function testCreateWithOutSequence(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Field 1','required'=>1,'data_type'=>'text'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form/1/field', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 3);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['required'], $data['required']);
        $this->assertEquals($content['data']['sequence'], 3);
        $this->assertEquals($content['data']['data_type'], $data['data_type']);
    }

    public function testCreateFormNameExists(){
        $this->initAuthToken($this->adminUser);
        $data = ['name'=>'field1','required'=>1,'sequence'=>1,'data_type'=>'text'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form/1/field', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'Field Name Exists');
    }
    public function testCreateFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['required'=>1,'sequence'=>1,'data_type'=>'text'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form/1/field', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testUpdate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Sample2','required'=>0, 'sequence' => 1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form/1/field/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['sequence'], $data['sequence']);
    }

    public function testUpdateNotFound(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Sample2', 'text' => 'Sample 2 Description'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/form/1/field/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/form/1/field/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');        
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/form/1/field/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('form');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}
