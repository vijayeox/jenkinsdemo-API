<?php
namespace Analytics;

use Analytics\Controller\DashboardController;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class DashboardControllerTest extends ControllerTest
{

    public function setUp(): void
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
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/WidgetQuery.yml");
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
        $data = ['name' => 'Dashboard3', 'dashboard_type' => 'DocumentDashboard', 'description' => 'description', 'content' => 'Content 1', 'filter_configuration' => '[{"filterName":"Store","field":"store","fieldType":"text","dataType":"text","operator":"==","value":[{"value":"columbus","label":"columbus"}],"key":0}]', 'export_configuration' => '{configuration: "{"app_name":"qa3_bsri_claim","operation":"sum","field":"total incurred","group":"producer","date_type":"policy_effective_date","date_period":"first day of january this year - 4 years/last day of december this year","round":"2","expression":"/1000000"}"
            datasource_uuid: "3f20c17f-7a76-4cce-8461-26a67f81e479"}', ];
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_dashboard'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['dashboard_type'], $data['dashboard_type']);
        $this->assertEquals($content['data']['description'], $data['description']);
        $this->assertEquals($content['data']['filter_configuration'], $data['filter_configuration']);
        $this->assertEquals($content['data']['export_configuration'], $data['export_configuration']);
        $this->assertEquals(4, $this->getConnection()->getRowCount('ox_dashboard'));
    }

    public function testCreateWithDefault()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Dashboard4', 'dashboard_type' => 'DocumentDashboard', 'description' => 'description', 'content' => 'Content 1', 'isdefault' => 1, 'filter_configuration' => '[{"filterName":"Store","field":"store","fieldType":"text","dataType":"text","operator":"==","value":[{"value":"columbus","label":"columbus"}],"key":0}]', 'export_configuration' => '{configuration: "{"app_name":"qa3_bsri_claim","operation":"sum","field":"total incurred","group":"producer","date_type":"policy_effective_date","date_period":"first day of january this year - 4 years/last day of december this year","round":"2","expression":"/1000000"}"
        datasource_uuid: "3f20c17f-7a76-4cce-8461-26a67f81e479"}', ];
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_dashboard'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['dashboard_type'], $data['dashboard_type']);
        $this->assertEquals($content['data']['description'], $data['description']);
        $this->assertEquals($content['data']['isdefault'], $data['isdefault']);
        $this->assertEquals($content['data']['filter_configuration'], $data['filter_configuration']);
        $this->assertEquals($content['data']['export_configuration'], $data['export_configuration']);
        $this->assertEquals(4, $this->getConnection()->getRowCount('ox_dashboard'));
    }

    public function testCreateWithDefaultFalse()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Dashboard4', 'dashboard_type' => 'DocumentDashboard', 'description' => 'description', 'content' => 'Content 1', 'isdefault' => 0];
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_dashboard'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['dashboard_type'], $data['dashboard_type']);
        $this->assertEquals($content['data']['description'], $data['description']);
        $this->assertEquals($content['data']['isdefault'], $data['isdefault']);
        $this->assertEquals(4, $this->getConnection()->getRowCount('ox_dashboard'));
    }

    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Dashboard3', 'description' => 'description', 'content' => 'Content 1'];
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_dashboard'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation error(s).');
        $this->assertEquals($content['data']['errors']['dashboard_type']['error'], 'required');
    }

    public function testUpdate()
    {
        $data = ['name' => "dashboardtest", 'description' => 'descriptiontest', 'version' => 1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard/fc67ceb2-4b6f-4a33-8527-5fc6b0822988', 'PUT', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['dashboard']['version'], 2);
        $this->assertEquals($content['data']['dashboard']['data']['name'], $data['name']);
        $this->assertEquals($content['data']['dashboard']['data']['description'], $data['description']);
    }

    public function testUpdateWithDefault()
    {
        $data = ['name' => "dashboardtest", 'description' => 'descriptiontest', 'version' => 1, 'isdefault' => 1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard/fc67ceb2-4b6f-4a33-8527-5fc6b0822988', 'PUT', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['dashboard']['version'], 2);
        $this->assertEquals($content['data']['dashboard']['data']['name'], $data['name']);
        $this->assertEquals($content['data']['dashboard']['data']['isdefault'], $data['isdefault']);
        $this->assertEquals($content['data']['dashboard']['data']['description'], $data['description']);
    }

    public function testUpdateWithWrongVersion()
    {
        $data = ['name' => "dashboardtest", 'description' => 'descriptiontest', 'version' => 3];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard/fc67ceb2-4b6f-4a33-8527-5fc6b0822988', 'PUT', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(412);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Entity version sent by client does not match the version on server.');
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => "dashboardtest", 'description' => 'descriptiontest', 'version' => 1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/dashboard/1000', 'PUT', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard/fc67ceb2-4b6f-4a33-8527-5fc6b0822988?version=1', 'DELETE');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteWithWrongVersion()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard/fc67ceb2-4b6f-4a33-8527-5fc6b0822988?version=3', 'DELETE');
        $this->assertResponseStatusCode(412);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Entity version sent by client does not match the version on server.');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard/11111111-1111-1111-1111-111111111111?version=3', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('dashboard');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard/a59f865e-efba-472e-91f2-2ae2d8a16d36', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['dashboard']['uuid'], 'a59f865e-efba-472e-91f2-2ae2d8a16d36');
        $this->assertEquals($content['data']['dashboard']['name'], 'Dashboard1');
    }

    public function testGetNotFound()
    {
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
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['data'][1]['uuid'], 'a59f865e-efba-472e-91f2-2ae2d8a16d36');
        $this->assertEquals($content['data'][1]['name'], 'Dashboard1');
        $this->assertEquals($content['data'][2]['description'], 'Description');
        $this->assertEquals($content['data'][2]['name'], 'Dashboard2');
        $this->assertEquals($content['total'], 3);
    }

    public function testGetListWithDeleted()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard?show_deleted=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['data'][1]['uuid'], 'a59f865e-efba-472e-91f2-2ae2d8a16d36');
        $this->assertEquals($content['data'][1]['name'], 'Dashboard1');
        $this->assertEquals($content['data'][1]['isdeleted'], 0);
        $this->assertEquals($content['data'][2]['description'], 'Description');
        $this->assertEquals($content['data'][2]['name'], 'Dashboard2');
        $this->assertEquals($content['total'], 3);
    }

    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard?filter=[{"sort":[{"field":"name","dir":"desc"}]}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['data'][1]['uuid'], 'fc67ceb2-4b6f-4a33-8527-5fc6b0822988');
        $this->assertEquals($content['data'][1]['name'], 'Dashboard2');
        $this->assertEquals($content['data'][2]['ispublic'], 1);
        $this->assertEquals($content['data'][2]['name'], 'Dashboard1');
        $this->assertEquals($content['total'], 3);
    }

    public function testGetListSortWithPageSizeWithNoLimit()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard?filter=[{"sort":[{"field":"name","dir":"asc"}],"skip":0,"take":0}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['data'][0]['uuid'], 'a59f865e-efba-472e-91f2-2ae2d8a16d36');
        $this->assertEquals($content['data'][0]['name'], 'Dashboard1');
        $this->assertEquals($content['data'][0]['is_owner'], true);
        $this->assertEquals($content['total'], 3);
    }

    public function testGetListSortWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard?filter=[{"sort":[{"field":"name","dir":"asc"}],"skip":1,"take":20}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], 'fc67ceb2-4b6f-4a33-8527-5fc6b0822988');
        $this->assertEquals($content['data'][0]['name'], 'Dashboard2');
        $this->assertEquals($content['data'][0]['is_owner'], true);
        $this->assertEquals($content['total'], 3);
    }

    public function testGetListwithQueryParameters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/dashboard?filter=[{"filter":{"logic":"and","filters":[{"field":"name","operator":"endswith","value":"2"},{"field":"name","operator":"startswith","value":"D"}]},"sort":[{"field":"name","dir":"desc"}],"skip":0,"take":10}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], 'fc67ceb2-4b6f-4a33-8527-5fc6b0822988');
        $this->assertEquals($content['data'][0]['name'], 'Dashboard2');
        $this->assertEquals($content['total'], 1);
    }
}
