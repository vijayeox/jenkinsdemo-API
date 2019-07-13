<?php
namespace Analytics;

use Analytics\Controller\WidgetController;
use Analytics\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\Framework\TestResult;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class WidgetControllerTest extends ControllerTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/DataSource.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Query.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Visualization.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Widget.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Analytics');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['query_id' => 3,'visualization_id' => 2, 'ispublic' => 1];
        $this->assertEquals(2, $this->getConnection()->getRowCount('widget'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/widget', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['query_id'], $data['query_id']);
        $this->assertEquals($content['data']['ispublic'], $data['ispublic']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('widget'));
    }

    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['query_id' => 3,'visualization_id' => 2];
        $this->assertEquals(2, $this->getConnection()->getRowCount('widget'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/widget', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['ispublic'], 'required');
    }

    public function testUpdate()
    {
        $data = ['visualization_id' => 1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/widget/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['visualization_id'], $data['visualization_id']);
    }

    public function testUpdateNotFound()
    {
        $data = ['visualization_id' => 1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/widget/1000', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/10000', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGet() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['query_id'],1);
        $this->assertEquals($content['data']['visualization_id'],2);
    }

    public function testGetNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/100', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 2);
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['visualization_id'], 2);
        $this->assertEquals($content['data']['data'][0]['query_id'], 1);
        $this->assertEquals($content['data']['data'][1]['visualization_id'], 1);
        $this->assertEquals($content['data']['data'][0]['query_id'], 1);
        $this->assertEquals($content['data']['data'][1]['id'], 2);
        $this->assertEquals($content['data']['total'],2);
    }

    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget?sort=[{"field":"visualization_id","dir":"asc"}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 2);
        $this->assertEquals($content['data']['data'][0]['id'], 2);
        $this->assertEquals($content['data']['data'][0]['visualization_id'], 1);
        $this->assertEquals($content['data']['data'][1]['visualization_id'], 2);
        $this->assertEquals($content['data']['data'][1]['id'], 1);
        $this->assertEquals($content['data']['total'],2);
    }

     public function testGetListSortWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget?skip=1&limit=10&sort=[{"field":"visualization_id","dir":"asc"}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 1);
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['visualization_id'], 2);
        $this->assertEquals($content['data']['data'][0]['query_id'], 1);
        $this->assertEquals($content['data']['total'],2);
    }

    public function testGetListwithQueryParameters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget?limit=10&sort=[{"field":"id","dir":"desc"}]&filter=[{"logic":"and"},{"filters":[{"field":"visualization_id","operator":"neq","value":"2"}]}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 1);
        $this->assertEquals($content['data']['data'][0]['id'], 2);
        $this->assertEquals($content['data']['data'][0]['visualization_id'], 1);
        $this->assertEquals($content['data']['total'],1);
    }
}