<?php
namespace Analytics;

use Analytics\Controller\DashboardController;
use Analytics\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\Framework\TestResult;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class DashboardControllerTest extends ControllerTest
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
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Dashboard.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Analytics');
        $this->assertControllerName(DashboardController::class); // as specified in router's controller name alias
        $this->assertControllerClass('DashboardController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Dashboard3', 'dashboard_type' => 'DocumentDashboard', 'description' => 'description'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('dashboard'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['dashboard_type'], $data['dashboard_type']);
        $this->assertEquals($content['data']['description'], $data['description']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('dashboard'));
    }

    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Dashboard3', 'description' => 'description'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('dashboard'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['dashboard_type'], 'required');
    }

    public function testUpdate()
    {
        $data = ['name' => "dashboardtest", 'description' => 'descriptiontest'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard/fc67ceb2-4b6f-4a33-8527-5fc6b0822988', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => "dashboardtest", 'description' => 'descriptiontest'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard/1000', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard/fc67ceb2-4b6f-4a33-8527-5fc6b0822988', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard/10000', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGet() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard/a59f865e-efba-472e-91f2-2ae2d8a16d36', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['uuid'], 'a59f865e-efba-472e-91f2-2ae2d8a16d36');
        $this->assertEquals($content['data']['name'], 'Dashboard1');
    }

    public function testGetNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard/100', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 2);
        $this->assertEquals($content['data']['data'][0]['uuid'], 'a59f865e-efba-472e-91f2-2ae2d8a16d36');
        $this->assertEquals($content['data']['data'][0]['name'], 'Dashboard1');
        $this->assertEquals($content['data']['data'][1]['dashboard_type'], 'Document Dashboard');
        $this->assertEquals($content['data']['data'][1]['name'], 'Dashboard2');
        $this->assertEquals($content['data']['total'],2);
    }

    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard?sort=[{"field":"name","dir":"desc"}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 2);
        $this->assertEquals($content['data']['data'][0]['uuid'], 'fc67ceb2-4b6f-4a33-8527-5fc6b0822988');
        $this->assertEquals($content['data']['data'][0]['name'], 'Dashboard2');
        $this->assertEquals($content['data']['data'][1]['ispublic'], 1);
        $this->assertEquals($content['data']['data'][1]['name'], 'Dashboard1');
        $this->assertEquals($content['data']['total'],2);
    }

     public function testGetListSortWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard?skip=1&limit=10&sort=[{"field":"name","dir":"asc"}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 1);
        $this->assertEquals($content['data']['data'][0]['uuid'], 'fc67ceb2-4b6f-4a33-8527-5fc6b0822988');
        $this->assertEquals($content['data']['data'][0]['name'], 'Dashboard2');
        $this->assertEquals($content['data']['data'][0]['created_by'], 1);
        $this->assertEquals($content['data']['total'],2);
    }

    public function testGetListwithQueryParameters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard?limit=10&sort=[{"field":"name","dir":"desc"}]&filter=[{"logic":"and"},{"filters":[{"field":"name","operator":"endswith","value":"2"},{"field":"name","operator":"startswith","value":"D"}]}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 1);
        $this->assertEquals($content['data']['data'][0]['uuid'], 'fc67ceb2-4b6f-4a33-8527-5fc6b0822988');
        $this->assertEquals($content['data']['data'][0]['name'], 'Dashboard2');
        $this->assertEquals($content['data']['total'],1);
    }
}