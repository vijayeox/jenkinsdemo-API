<?php
namespace App;

use App\Controller\MenuItemController;
use Zend\Stdlib\ArrayUtils;
use Form\Model\Field;
use Oxzion\Test\ControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class MenuItemControllerTest extends ControllerTest{
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Workflow.yml");
        return $dataset;
    }

    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/menu', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(MenuItemController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MenuItemController');
        $this->assertMatchedRouteName('appmenu');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id']>0, true);
        $this->assertEquals($content['data'][0]['name'], 'menu1');
        $this->assertEquals($content['data'][1]['id']>1, true);
        $this->assertEquals($content['data'][1]['name'], 'menu2');
    }

    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/menu/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(MenuItemController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MenuItemController');
        $this->assertMatchedRouteName('appmenu');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id']>0, true);
        $this->assertEquals($content['data']['name'], 'menu1');
    }

    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/menu/122', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(MenuItemController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MenuItemController');
        $this->assertMatchedRouteName('appmenu');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }


    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'menu4','app_id'=>1,'required'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/menu', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('App');
        $this->assertControllerName(MenuItemController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MenuItemController');
        $this->assertMatchedRouteName('appmenu');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'] > 2, true);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['required'], $data['required']);
    }

    public function testCreateFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['required'=>1,'title' => 'menu4','sequence'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/menu', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(MenuItemController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MenuItemController');
        $this->assertMatchedRouteName('appmenu');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testUpdate(){
        $this->initAuthToken($this->adminUser);
        $data = ['id'=>2,'title' => 'menu32','name' => 'menu23','app_id' => 99,'required'=> 0, 'sequence' => 2,'type'=>'Page'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/menu/2', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(MenuItemController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MenuItemController');
        $this->assertMatchedRouteName('appmenu');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 2);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['sequence'], $data['sequence']);
    }

    public function testUpdateNotFound(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Sample2','title' => 'menu32', 'text' => 'Sample 2 Description'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/menu/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(MenuItemController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MenuItemController');
        $this->assertMatchedRouteName('appmenu');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/menu/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(MenuItemController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MenuItemController');
        $this->assertMatchedRouteName('appmenu');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');        
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/menu/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(MenuItemController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MenuItemController');
        $this->assertMatchedRouteName('appmenu');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}
