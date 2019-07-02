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
        $this->dispatch('/analytics/query/1', 'PUT', null);
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
        $this->dispatch('/analytics/query/1', 'DELETE');
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
        $this->dispatch('/analytics/query/1', 'GET');
        print_r(json_decode($this->getResponse()->getContent(), true));exit;
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'query1');
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
        $this->assertEquals(count($content['data']['data']), 2);
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['name'], 'mattermost');
        $this->assertEquals($content['data']['data'][1]['type'], 'Elastic');
        $this->assertEquals($content['data']['data'][1]['name'], 'reporting engine');
        $this->assertEquals($content['data']['total'],2);
    }

}