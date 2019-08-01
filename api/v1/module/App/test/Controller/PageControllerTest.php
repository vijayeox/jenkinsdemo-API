<?php
namespace App;

use App\Controller\PageController;
use Zend\Stdlib\ArrayUtils;
use Form\Model\Field;
use Oxzion\Test\ControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class PageControllerTest extends ControllerTest{
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
        $this->dispatch('/app/99/page', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['data'][0]['id']>0, true);
        $this->assertEquals($content['data'][0]['name'], 'page1');
        $this->assertEquals($content['data'][1]['id']>1, true);
        $this->assertEquals($content['data'][1]['name'], 'page2');
    }

    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/page/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id']>0, true);
        $this->assertEquals($content['data']['name'], 'page1');
    }

    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/page/122', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }


    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'page4','app_id'=>99,'text'=>'Some HTML Text'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/page', 'POST', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'] > 2, true);
        $this->assertEquals($content['data']['name'], $data['name']);
     }

    public function testCreateFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['app_id'=>99];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/page', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testUpdate(){
        $this->initAuthToken($this->adminUser);
        $data = ['id'=>2,'name' => 'page23','app_id' => 99,'required'=> 0, 'sequence' => 2,'type'=>'Page'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/page/2', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 2);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['sequence'], $data['sequence']);
    }

    public function testUpdateNotFound(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Sample2', 'text' => 'Sample 2 Description'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/page/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/page/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');        
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/page/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}
