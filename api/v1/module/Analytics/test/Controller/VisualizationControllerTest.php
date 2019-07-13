<?php
namespace Analytics;

use Analytics\Controller\VisualizationController;
use Analytics\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\Framework\TestResult;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class VisualizationControllerTest extends ControllerTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Visualization.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Analytics');
        $this->assertControllerName(VisualizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('VisualizationController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['type' => "BarChart"];
        $this->assertEquals(2, $this->getConnection()->getRowCount('visualization'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/visualization', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('visualization');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['type'], $data['type']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('visualization'));
    }

    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['type' => ''];
        $this->assertEquals(2, $this->getConnection()->getRowCount('visualization'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/visualization', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('visualization');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['type'], 'required');
    }

    public function testUpdate()
    {
        $data = ['type' => "test"];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/visualization/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('visualization');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['type'], $data['type']);
    }

    public function testUpdateNotFound()
    {
        $data = ['type' => "test"];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/visualization/1000', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('visualization');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/visualization/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('visualization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/visualization/10000', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('visualization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGet() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/visualization/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['type'], 'Pie Chart');
    }

    public function testGetNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/visualization/100', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/visualization', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 2);
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['type'], 'Pie Chart');
        $this->assertEquals($content['data']['data'][1]['type'], 'Panel Item');
        $this->assertEquals($content['data']['data'][1]['id'], 2);
        $this->assertEquals($content['data']['total'],2);
    }

    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/visualization?sort=[{"field":"type","dir":"asc"}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 2);
        $this->assertEquals($content['data']['data'][0]['id'], 2);
        $this->assertEquals($content['data']['data'][0]['type'], 'Panel Item');
        $this->assertEquals($content['data']['data'][1]['type'], 'Pie Chart');
        $this->assertEquals($content['data']['data'][1]['id'], 1);
        $this->assertEquals($content['data']['total'],2);
    }

     public function testGetListSortWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/visualization?skip=1&limit=10&sort=[{"field":"type","dir":"asc"}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 1);
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['type'], 'Pie Chart');
        $this->assertEquals($content['data']['data'][0]['created_by'], 1);
        $this->assertEquals($content['data']['total'],2);
    }

    public function testGetListwithQueryParameters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/visualization?limit=10&sort=[{"field":"id","dir":"desc"}]&filter=[{"logic":"and"},{"filters":[{"field":"type","operator":"endswith","value":"t"},{"field":"type","operator":"startswith","value":"p"}]}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 1);
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['type'], 'Pie Chart');
        $this->assertEquals($content['data']['total'],1);
    }
}