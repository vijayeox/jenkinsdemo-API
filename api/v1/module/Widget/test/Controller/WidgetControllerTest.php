<?php
namespace Widget;

use Widget\Controller\WidgetController;
use Widget\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class WidgetControllerTest extends ControllerTest{
    
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Widget.yml");
        return $dataset;
    }
    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/widget', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Widget');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertMatchedRouteName('Widget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['name'], 'Announcement');
        $this->assertEquals($content['data'][0]['defaultheight'], 3);
        $this->assertEquals($content['data'][0]['defaultwidth'], 2);
        $this->assertEquals($content['data'][1]['id'], 3);
        $this->assertEquals($content['data'][1]['name'], 'Followups');
        $this->assertEquals($content['data'][1]['defaultheight'], 2);
        $this->assertEquals($content['data'][1]['defaultwidth'], 3);

    }


    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/widget/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Widget');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertMatchedRouteName('Widget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Announcement');
    }

    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/widget/9999', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Widget');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertMatchedRouteName('Widget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $data = ['name' => 'Test Widget','defaultheight' =>5,'defaultwidth'=>1,'applicationguid'=>'abcd1234'];
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_widget'));
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/widget', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Widget');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertMatchedRouteName('Widget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['defaultheight'], $data['defaultheight']);
        $this->assertEquals($content['data']['defaultwidth'], $data['defaultwidth']);
        $this->assertEquals(4, $this->getConnection()->getRowCount('ox_widget'));
    }


    public function testUpdate(){
        $data = ['name' => 'Test Widget Update'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/widget/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Widget');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertMatchedRouteName('Widget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateNotFound(){
        $data = ['name' => 'Test Widget'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/widget/99999', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Widget');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertMatchedRouteName('Widget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/widget/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Widget');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertMatchedRouteName('Widget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/widget/99999', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Widget');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertMatchedRouteName('Widget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}