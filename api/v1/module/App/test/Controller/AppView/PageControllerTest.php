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

class PageControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../../Dataset/Workflow.yml");
        return $dataset;
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/page', 'GET');
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

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/page/05028156-df8d-11e9-8a34-2a2ae2dbcce4', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['content'][0]['type'], 'Form');
        $this->assertNotEmpty($content['data']['content'][0]['content']);
        $this->assertEquals($content['data']['content'][1]['type'], 'List');
        $this->assertNotEmpty($content['data']['content'][1]['content']);
        $this->assertEquals($content['data']['content'][2]['type'], 'Document');
        $this->assertEquals($content['data']['content'][2]['content'], 'content_3');
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/page/4c73dbc0-df8d-11e9-8a34-2a2ae2dbcce4', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }


    // public function testCreate()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $data = json_decode('{"name":"Page Test1","description":"Page Description","content":[{"content":"<div>Page Content goes here!!!....</div>","type": "Document"},{"type": "List","content": {"data": "organization"}},{"form_id":1,"type": "Form"}]}');
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/page', 'POST', null);
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertResponseStatusCode(201);
    //     $this->assertModuleName('App');
    //     $this->assertControllerName(PageController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('PageController');
    //     $this->assertMatchedRouteName('apppage');
    //     $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    //     $this->assertEquals($content['status'], 'success');
    //     $this->assertEquals($content['data']['id'] > 2, true);
    //     $this->assertEquals($content['data']['name'], $data->name);
    // }

    // public function testCreateFailure()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $data = ['app_id'=>99];
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/page', 'POST', null);
    //     $this->assertResponseStatusCode(404);
    //     $this->assertModuleName('App');
    //     $this->assertControllerName(PageController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('PageController');
    //     $this->assertMatchedRouteName('apppage');
    //     $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    //     $this->assertEquals($content['message'], 'Validation Errors');
    //     $this->assertEquals($content['data']['errors']['name'], 'required');
    // }

    public function testUpdate()
    {
        $this->initAuthToken($this->adminUser);
        $data = json_decode('{"name":"Page23","description":"Page Description","content":[{"content":"<div>Page Content goes here!!!....</div>","type": "Document"},{"form_id":1,"type": "Form"}]}');
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/page/050283ae-df8d-11e9-8a34-2a2ae2dbcce4', 'PUT', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data->name);
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/page/050283ae-df8d-11e9-8a34-2a2ae2dbcce4', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/page/6b5f3700-df8d-11e9-8a34-2a2ae2dbcce4', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(PageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageController');
        $this->assertMatchedRouteName('apppage');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'],'Page Not Found'); 
    }
}
