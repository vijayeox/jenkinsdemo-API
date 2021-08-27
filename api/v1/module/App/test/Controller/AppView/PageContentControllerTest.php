<?php
namespace App;

use App\Controller\PageContentController;
use Zend\Stdlib\ArrayUtils;
use Form\Model\Field;
use Oxzion\Test\ControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class PageContentControllerTest extends ControllerTest
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

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['sequence' => 1,'page_id'=>1,'content'=>"something",'type'=>3];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/pagecontent', 'POST', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('App');
        $this->assertControllerName(PageContentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageContentController');
        $this->assertMatchedRouteName('apppagecontent');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'] > 2, true);
    }

    public function testCreateFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['app_id'=>99,'page_id'=>1,'content'=>"something"];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/pagecontent', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(PageContentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageContentController');
        $this->assertMatchedRouteName('apppagecontent');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
    }

    public function testUpdate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['sequence' => 1,'app_id'=>99,'page_id'=>1,'content'=>"something",'type'=>3];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/pagecontent/2', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(PageContentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageContentController');
        $this->assertMatchedRouteName('apppagecontent');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 2);
        $this->assertEquals($content['data']['sequence'], $data['sequence']);
    }

    public function testUpdateNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['sequence' => 1,'app_id'=>99,'page_id'=>1,'content'=>"something",'type'=>3];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/pagecontent/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(PageContentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageContentController');
        $this->assertMatchedRouteName('apppagecontent');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/pagecontent/3', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(PageContentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageContentController');
        $this->assertMatchedRouteName('apppagecontent');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/pagecontent/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(PageContentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PageContentController');
        $this->assertMatchedRouteName('apppagecontent');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
