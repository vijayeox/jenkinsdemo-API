<?php
namespace Screen;

use Screen\Controller\ScreenController;
use Screen\Controller\ScreenwidgetController;
use Screen\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class ScreenControllerTest extends ControllerTest{
    
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Screen.yml");
        return $dataset;
    }
    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/screen', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenController');
        $this->assertMatchedRouteName('screen');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['name'], 'Dashboard');
        $this->assertEquals($content['data'][1]['id'], 2);
        $this->assertEquals($content['data'][1]['name'], 'Profile');
    }

    public function testGetScreenWidgetList() {
        $this->initAuthToken($this->employeeUser);
        $this->dispatch('/screen/1/widget', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenwidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenwidgetController');
        $this->assertMatchedRouteName('screenwidget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], 2);
        $this->assertEquals($content['data'][0]['widgetid'], 2);
        $this->assertEquals($content['data'][0]['width'], 2);
        $this->assertEquals($content['data'][0]['height'], 3);
        $this->assertEquals($content['data'][0]['column'], 2);
        $this->assertEquals($content['data'][0]['row'], 3);
        $this->assertEquals($content['data'][1]['id'], 3);
        $this->assertEquals($content['data'][1]['widgetid'], 1);
        $this->assertEquals($content['data'][1]['width'], 2);
        $this->assertEquals($content['data'][1]['height'], 2);
        $this->assertEquals($content['data'][1]['column'], 4);
        $this->assertEquals($content['data'][1]['row'], 1);

    }

    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/screen/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenController');
        $this->assertMatchedRouteName('screen');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Dashboard');
    }

    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/screen/9999', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenController');
        $this->assertMatchedRouteName('screen');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $data = ['name' => 'Test Screen'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_screen'));
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/screen', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenController');
        $this->assertMatchedRouteName('screen');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_screen'));
    }

    public function testScreenwidgetCreate(){
        $data = ['widgetid' =>10,'width'=>3,'height'=>2,'row'=>1,'column'=>3];
        $this->assertEquals(4, $this->getConnection()->getRowCount('ox_screen_widget'));
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/screen/1/widget', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenwidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenwidgetController');
        $this->assertMatchedRouteName('screenwidget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['screenid'], 1);
        $this->assertEquals($content['data']['userid'], $this->employeeUserId);
        $this->assertEquals($content['data']['widgetid'], 10);
        $this->assertEquals($content['data']['width'], 3);
        $this->assertEquals($content['data']['height'], 2);
        $this->assertEquals($content['data']['column'], 3);
        $this->assertEquals($content['data']['row'], 1);
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_screen_widget'));
    }



    public function testEmployeePutAccess(){
        $data = ['name' => 'Test Screen'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/screen/1', 'PUT', null);        
        $this->assertResponseStatusCode(401);
    }

    public function testUpdate(){
        $data = ['name' => 'Test Screen'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));        
        $this->dispatch('/screen/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenController');
        $this->assertMatchedRouteName('screen');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testScreenwidgetUpdate(){
        $data = ['width'=>3,'height'=>2,'row'=>1,'column'=>3];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/screen/1/widget/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenwidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenwidgetController');
        $this->assertMatchedRouteName('screenwidget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['screenid'], 1);
        $this->assertEquals($content['data']['userid'], $this->employeeUserId);
        $this->assertEquals($content['data']['widgetid'], 1);
        $this->assertEquals($content['data']['width'], 3);
        $this->assertEquals($content['data']['height'], 2);
        $this->assertEquals($content['data']['column'], 3);
        $this->assertEquals($content['data']['row'], 1);
    }


    public function testUpdateNotFound(){
        $data = ['name' => 'Test Screen'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/screen/99999', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenController');
        $this->assertMatchedRouteName('screen');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/screen/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenController');
        $this->assertMatchedRouteName('screen');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testScreenwidgetDelete(){
        $this->initAuthToken($this->employeeUser);
        $this->dispatch('/screen/1/widget/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenwidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenwidgetController');
        $this->assertMatchedRouteName('screenwidget');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testEmployeeDelete() {
        $this->initAuthToken($this->employeeUser);
        $this->dispatch('/screen/2', 'DELETE');  
        $this->assertResponseStatusCode(401);
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/screen/99999', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Screen');
        $this->assertControllerName(ScreenController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ScreenController');
        $this->assertMatchedRouteName('screen');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}