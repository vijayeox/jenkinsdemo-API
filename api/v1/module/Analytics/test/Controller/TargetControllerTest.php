<?php
namespace Analytics;

use Analytics\Controller\TargetController;
use Analytics\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\Framework\TestResult;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class TargetControllerTest extends ControllerTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Target.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Analytics');
        $this->assertControllerName(TargetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('TargetController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['type' => '1','period_type' => 'monthly','red_limit' => 10,'version' => 1];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_target'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/target', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('target');        
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['period_type'], $data['period_type']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_target'));
    }


    public function testUpdate()
    {
        $data = ['period_type' => "daily", 'version' => 1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/target/44f22a46-3434-48df-96b9-c58520005817', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('target');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['period_type'], $data['period_type']);
    }

     public function testUpdateWithWrongVersion()
     {
         $data = ['period_type' => "daily", 'version' => 3];
         $this->initAuthToken($this->adminUser);
         $this->setJsonContent(json_encode($data));
         $this->dispatch('/analytics/target/44f22a46-3434-48df-96b9-c58520005817', 'PUT', null);
         $this->assertResponseStatusCode(404);
         $this->setDefaultAsserts();
         $this->assertMatchedRouteName('target');
         $content = (array)json_decode($this->getResponse()->getContent(), true);
         $this->assertEquals($content['status'], 'error');
         $this->assertEquals($content['message'], 'Version changed');
     }

    public function testUpdateNotFound()
    {
        $data = ['period_type' => "test"];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/target/1000', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('target');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGet() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/target/44f22a46-3434-48df-96b9-c58520005817', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['uuid'], '44f22a46-3434-48df-96b9-c58520005817');
        $this->assertEquals($content['data']['period_type'], 'monthly');
    }

    public function testGetNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/target/100', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/target', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 2);
        $this->assertEquals($content['data']['data'][0]['uuid'], '44f22a46-3434-48df-96b9-c58520005817');
        $this->assertEquals($content['data']['data'][0]['period_type'], 'monthly');
        $this->assertEquals($content['data']['data'][0]['red_limit'], '10');
        $this->assertEquals($content['data']['data'][1]['uuid'], '44f22a46-3434-48df-8888-c58520005817');
        $this->assertEquals($content['data']['data'][1]['period_type'], 'daily');
        $this->assertEquals($content['data']['data'][1]['red_limit'], '15');
        $this->assertEquals($content['data']['total'],2);
    }



    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/target?filter=[{"sort":[{"field":"period_type","dir":"asc"}]}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 2);
        $this->assertEquals($content['data']['data'][1]['uuid'], '44f22a46-3434-48df-96b9-c58520005817');
        $this->assertEquals($content['data']['data'][1]['period_type'], 'monthly');
        $this->assertEquals($content['data']['data'][1]['red_limit'], '10');
        $this->assertEquals($content['data']['data'][0]['uuid'], '44f22a46-3434-48df-8888-c58520005817');
        $this->assertEquals($content['data']['data'][0]['period_type'], 'daily');
        $this->assertEquals($content['data']['data'][0]['red_limit'], '15');
        $this->assertEquals($content['data']['total'],2);
    }

     public function testGetListSortWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/target?filter=[{"sort":[{"field":"period_type","dir":"asc"}],"skip":1,"take":10}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 1);
        $this->assertEquals($content['data']['data'][0]['uuid'], '44f22a46-3434-48df-96b9-c58520005817');
        $this->assertEquals($content['data']['data'][0]['period_type'], 'monthly');
        $this->assertEquals($content['data']['data'][0]['red_limit'], '10');
        $this->assertEquals($content['data']['total'],2);
    }


    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/target/44f22a46-3434-48df-96b9-c58520005817?version=1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('target');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/target/10000?version=1', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('target');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDeleteWithWrongVersion()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/target/44f22a46-3434-48df-96b9-c58520005817?version=3', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('target');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Version changed');
    }
}