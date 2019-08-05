<?php
namespace Analytics;

use Analytics\Controller\QueryController;
use Analytics\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\Framework\TestResult;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class QueryControllerTest extends ControllerTest
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
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Analytics');
        $this->assertControllerName(QueryController::class); // as specified in router's controller name alias
        $this->assertControllerClass('QueryController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => "query4", 'datasource_id' => 1, 'query_json' => '{"date_type":"date_created","date-period":"2018-01-01/now","operation":"sum","group":"created_by","field":"amount"}', 'ispublic' => 1];
        $this->assertEquals(3, $this->getConnection()->getRowCount('query'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/query', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('query');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['type'], $data['type']);
        $this->assertEquals($content['data']['connection_string'], $data['connection_string']);
        $this->assertEquals(4, $this->getConnection()->getRowCount('query'));
    }

    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => "query4", 'query_json' => '{"date_type":"date_created","date-period":"2018-01-01/now","operation":"sum","group":"created_by","field":"amount"}'];
        $this->assertEquals(3, $this->getConnection()->getRowCount('query'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/query', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('query');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['datasource_id'], 'required');
    }

    public function testUpdate()
    {
        $data = ['name' => "querytest", 'datasource_id' => 2];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/query/8f1d2819-c5ff-4426-bc40-f7a20704a738', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('query');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['datasource_id'], $data['datasource_id']);
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => "querytest", 'datasource_id' => 2];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/query/1000', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('query');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/query/8f1d2819-c5ff-4426-bc40-f7a20704a738', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('query');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/query/10000', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('query');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGet() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/query/8f1d2819-c5ff-4426-bc40-f7a20704a738', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['data'][0]['uuid'], '8f1d2819-c5ff-4426-bc40-f7a20704a738');
        $this->assertEquals($content['data']['data'][0]['name'], 'query1');
    }

    public function testGetNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/query/100', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/query', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 3);
        $this->assertEquals($content['data']['data'][0]['uuid'], '8f1d2819-c5ff-4426-bc40-f7a20704a738');
        $this->assertEquals($content['data']['data'][0]['name'], 'query1');
        $this->assertEquals($content['data']['data'][1]['datasource_id'], 1);
        $this->assertEquals($content['data']['data'][1]['name'], 'query2');
        $this->assertEquals($content['data']['total'],3);
    }

    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/query?sort=[{"field":"name","dir":"desc"}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 3);
        $this->assertEquals($content['data']['data'][0]['uuid'], '1a7d9e0d-f6cd-40e2-9154-87de247b9ce1');
        $this->assertEquals($content['data']['data'][0]['name'], 'query3');
        $this->assertEquals($content['data']['data'][1]['ispublic'], 1);
        $this->assertEquals($content['data']['data'][1]['name'], 'query2');
        $this->assertEquals($content['data']['total'],3);
    }

     public function testGetListSortWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/query?skip=1&limit=10&sort=[{"field":"name","dir":"asc"}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 2);
        $this->assertEquals($content['data']['data'][0]['uuid'], '86c0cc5b-2567-4e5f-a741-f34e9f6f1af1');
        $this->assertEquals($content['data']['data'][0]['name'], 'query2');
        $this->assertEquals($content['data']['data'][0]['created_by'], 2);
        $this->assertEquals($content['data']['total'],3);
    }

    public function testGetListwithQueryParameters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/query?limit=10&sort=[{"field":"name","dir":"desc"}]&filter=[{"logic":"and"},{"filters":[{"field":"name","operator":"endswith","value":"3"},{"field":"name","operator":"startswith","value":"q"}]}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 1);
        $this->assertEquals($content['data']['data'][0]['uuid'], '1a7d9e0d-f6cd-40e2-9154-87de247b9ce1');
        $this->assertEquals($content['data']['data'][0]['name'], 'query3');
        $this->assertEquals($content['data']['total'],1);
    }
}