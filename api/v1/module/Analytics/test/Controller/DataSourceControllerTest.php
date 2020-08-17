<?php
namespace Analytics;

use Analytics\Controller\DataSourceController;
use Analytics\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\Framework\TestResult;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class DataSourceControllerTest extends ControllerTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/DataSource.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Analytics');
        $this->assertControllerName(DataSourceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('DataSourceController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => "Orocrm", 'type' => 'MySql', 'configuration' => '{"data": { "server": "myServerAddress", "Database": "myDataBase", "Uid": "myUsername","Pwd": "myPassword"}}'];
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_datasource'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/datasource', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dataSource');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['type'], $data['type']);
        $this->assertEquals($content['data']['configuration'], $data['configuration']);
        $this->assertEquals(4, $this->getConnection()->getRowCount('ox_datasource'));
    }

    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['type' => 'MySql', 'configuration' => '{"data": { "server": "myServerAddress", "Database": "myDataBase", "Uid": "myUsername","Pwd": "myPassword"}}'];
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_datasource'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/datasource', 'POST', $data);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dataSource');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation error(s).');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testUpdate()
    {
        $data = ['name' => "Analytics", 'type' => 'Elastic' , 'version' => 1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/datasource/7700c623-1361-4c85-8203-e255ac995c4a', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dataSource');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['type'], $data['type']);
    }

    public function testUpdateWithWrongVersion()
    {
        $data = ['name' => "Analytics", 'type' => 'Elastic' , 'version' => 3];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/datasource/7700c623-1361-4c85-8203-e255ac995c4a', 'PUT', null);
        $this->assertResponseStatusCode(412);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dataSource');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Entity version sent by client does not match the version on server.');
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => "Analytics", 'type' => 'Elastic'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/datasource/1000', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dataSource');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/datasource/7700c623-1361-4c85-8203-e255ac995c4a?version=1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dataSource');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteWithWrongVersion()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/datasource/7700c623-1361-4c85-8203-e255ac995c4a?version=3', 'DELETE');
        $this->assertResponseStatusCode(412);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dataSource');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Entity version sent by client does not match the version on server.');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/datasource/11111111-1111-111111111-111111111111?version=1', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dataSource');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Entity not found.');
    }

    public function testGet() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/datasource/7700c623-1361-4c85-8203-e255ac995c4a', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['uuid'], '7700c623-1361-4c85-8203-e255ac995c4a');
        $this->assertEquals($content['data']['name'], 'mattermost');
    }

    public function testGetNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/datasource/100', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/datasource', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['data'][1]['uuid'], '7700c623-1361-4c85-8203-e255ac995c4a');
        $this->assertEquals($content['data'][1]['name'], 'mattermost');
        $this->assertEquals($content['data'][2]['type'], 'Elastic');
        $this->assertEquals($content['data'][2]['name'], 'reporting engine');
        $this->assertEquals($content['total'],3);
    }

    public function testGetListWithDeleted()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/datasource?show_deleted=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['data'][1]['uuid'], '7700c623-1361-4c85-8203-e255ac995c4a');
        $this->assertEquals($content['data'][1]['name'], 'mattermost');
        $this->assertEquals($content['data'][1]['isdeleted'], 0);
        $this->assertEquals($content['data'][2]['type'], 'Elastic');
        $this->assertEquals($content['data'][2]['name'], 'reporting engine');
        $this->assertEquals($content['total'],3);
    }

    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/datasource?filter=[{"sort":[{"field":"id","dir":"desc"}]}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['data'][0]['uuid'], 'cb1bebce-df33-4266-bbd6-d8da5571b10a');
        $this->assertEquals($content['data'][0]['name'], 'reporting engine');
        $this->assertEquals($content['data'][1]['type'], 'MySql');
        $this->assertEquals($content['data'][1]['name'], 'mattermost');
        $this->assertEquals($content['total'],3);
    }

    public function testGetListSortWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/datasource?filter=[{"sort":[{"field":"id","dir":"asc"}],"skip":1,"take":10}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][1]['uuid'], 'cb1bebce-df33-4266-bbd6-d8da5571b10a');
        $this->assertEquals($content['data'][1]['name'], 'reporting engine');
        $this->assertEquals($content['data'][1]['type'], 'Elastic');
        $this->assertEquals($content['total'],3);
    }

    public function testGetListwithQueryParameters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/datasource?filter=[{"filter":{"logic":"and","filters":[{"field":"name","operator":"endswith","value":"t"},{"field":"name","operator":"startswith","value":"m"}]},"sort":[{"field":"id","dir":"desc"}],"skip":0,"take":10}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '7700c623-1361-4c85-8203-e255ac995c4a');
        $this->assertEquals($content['data'][0]['name'], 'mattermost');
        $this->assertEquals($content['total'],1);
    }
}
