<?php
namespace App;

use App\Controller\CacheController;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class CacheControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../../Dataset/Workflow.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('App');
        $this->assertControllerName(CacheController::class); // as specified in router's controller name alias
        $this->assertControllerClass('CacheController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/cache', 'GET');
        $this->assertMatchedRouteName('app_cache');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['content'], "Some Content");
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/eedebfcc-df80-11e9-8a34-2a2ae2dbcce4/cache', 'GET');
        $this->assertMatchedRouteName('app_cache');
        $this->assertResponseStatusCode(200);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/deletecache', 'DELETE');
        $this->assertMatchedRouteName('remove_app_cache');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['message'], "The cache has been successfully deleted");
    }
    
    public function testDeleteWithIncorrectAppId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/eedebfcc-df80-11e9-8a34-2a2ae2dbcce4/deletecache', 'DELETE');
        $this->assertMatchedRouteName('remove_app_cache');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], "Invalid AppId - Cache not deleted");
    }
}
