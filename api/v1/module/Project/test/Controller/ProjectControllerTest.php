<?php
namespace Project;

use Mockery;
use Oxzion\Service\ProjectService;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Project\Controller\ProjectController;

class ProjectControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getMockMessageProducer()
    {
        $organizationService = $this->getApplicationServiceLocator()->get(Service\ProjectService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $organizationService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Project.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Project');
        $this->assertControllerName(ProjectController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ProjectController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['data' => array([
            "uuid" => "886d7eff-6bae-4892-baf8-6fefc56cbf0b",
            "name" => "Test Project 1",
            "manager_id" => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45",
            "description" => "Description Test Data",
        ], [
            "uuid" => "ced672bb-fe33-4f0a-b153-f1d182a02603",
            "name" => "Test Project 2",
            "manager_id" => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45",
            "description" => "Description Test Data",
        ], [
            "name" => "New Project",
            "manager_id" => "1",
            "description" => "Description Test Data",
        ],
        )];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $diff = array();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['total'], 3);
    }

    public function testGetListByManagerWithDifferentOrg()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(403);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to get the project list');
    }

    public function testGetListWithOrgId()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'], 'New Project');
    }

    public function testGetListByManager()
    {
        $this->initAuthToken($this->managerUser);
        $data = ['data' => array([
            "id" => "1",
            "uuid" => "886d7eff-6bae-4892-baf8-6fefc56cbf0b",
            "name" => "Test Project 1",
            "org_id" => "1",
            "manager_id" => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45",
            "description" => "Description Test Data",
            "created_by" => "1",
            "modified_by" => "1",
            "date_created" => "2018-11-11 07:25:06",
            "date_modified" => "2018-12-11 07:25:06",
            "isdeleted" => "0",
            "user_id" => "1",
            "project_id" => "1",
        ],
        )];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['total'], 3);
    }

    public function testGetListByEmployee()
    {
        $this->initAuthToken($this->employeeUser);
        $this->dispatch('/project?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Project');
        $this->assertControllerName(ProjectController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ProjectController');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    public function testGetListWithQuery()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['data' => array([
            "id" => "3",
            "name" => "Test Project 2",
            "org_id" => "1",
            "description" => "Description Test Data",
            "created_by" => "1",
            "modified_by" => "1",
            "date_created" => "2018-11-11 07:25:06",
            "date_modified" => "2018-12-11 07:25:06",
            "isdeleted" => "0",
            "user_id" => "1",
            "project_id" => "2",
        ])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"2"}]},"sort":[{"field":"name","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListWithQuerywithPageNo()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['data' => array([
            "id" => "1",
            "name" => "Test Project 1",
            "org_id" => "1",
            "description" => "Description Test Data",
            "created_by" => "1",
            "modified_by" => "1",
            "date_created" => "2018-11-11 07:25:06",
            "date_modified" => "2018-12-11 07:25:06",
            "isdeleted" => "0",
            "user_id" => "1",
            "project_id" => "2",
        ])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['total'], 3);
    }

    public function testGetListWithQuerywithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project?filter=[{"sort":[{"field":"name","dir":"asc"}],"skip":0,"take":3}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '3dbacd80-ff27-4169-a683-4a45d2a8fb8f');
        $this->assertEquals($content['data'][0]['name'], 'New Project');
        $this->assertEquals($content['data'][1]['uuid'], '886d7eff-6bae-4892-baf8-6fefc56cbf0b');
        $this->assertEquals($content['data'][1]['name'], 'Test Project 1');
        $this->assertEquals($content['data'][2]['uuid'], 'ced672bb-fe33-4f0a-b153-f1d182a02603');
        $this->assertEquals($content['data'][2]['name'], 'Test Project 2');
        $this->assertEquals($content['total'], 3);
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Test Project 1');
    }

    public function testGetWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Test Project 1');
    }

    public function testGetWithInvalidOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetWithInvalidProjectId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project/886d7eff-6baf8-6fefc56cbf0b', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/64', 'GET');
        $this->assertResponseStatusCode(200);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Project 3', 'description' => 'Project Description', 'manager_id' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'];
        $this->assertEquals(4, $this->getConnection()->getRowCount('ox_project'));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            //Message to be sent to Mockery => json_encode(array('orgname'=> 'Cleveland Black','projectname' => 'Test Project 3','description' => 'Project Description','uuid' => '')
            // Since value of uuid changes during each project creation Mockery Message is set to Mockery::any()
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'PROJECT_ADDED')->once()->andReturn();
        }
        $this->dispatch('/project', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $select = "SELECT id,manager_id from ox_project where name = 'Test Project 3'";
        $project = $this->executeQueryTest($select);
        $select = "SELECT * from ox_user_project where user_id =" . $project[0]['manager_id'] . " and project_id =" . $project[0]['id'];
        $oxproject = $this->executeQueryTest($select);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($project[0]['manager_id'], 1);
        $this->assertEquals($oxproject[0]['user_id'], 1);
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_project'));
    }

    public function testCreateWithOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Project 3', 'description' => 'Project Description', 'manager_id' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'];
        $this->assertEquals(4, $this->getConnection()->getRowCount('ox_project'));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            //Message to be sent to Mockery => json_encode(array('orgname'=> 'Cleveland Black','projectname' => 'Test Project 3','description' => 'Project Description','uuid' => '')
            // Since value of uuid changes during each project creation Mockery Message is set to Mockery::any()
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'PROJECT_ADDED')->once()->andReturn();
        }
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $select = "SELECT id,manager_id from ox_project where name = 'Test Project 3'";
        $project = $this->executeQueryTest($select);
        $select = "SELECT * from ox_user_project where user_id =" . $project[0]['manager_id'] . " and project_id =" . $project[0]['id'];
        $oxproject = $this->executeQueryTest($select);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($project[0]['manager_id'], 1);
        $this->assertEquals($oxproject[0]['user_id'], 1);
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_project'));
    }

    public function testCreateWithExistingProject()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Project 1', 'description' => 'Project Description', 'manager_id' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'];
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Project already exists');
    }

    public function testCreateDeletedProject()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'New Project 1', 'description' => 'Project Description', 'manager_id' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'];
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Project already exists would you like to reactivate?');
    }

    public function testCreateDeletedProjectWithReactivateFlag()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'New Project 1', 'description' => 'Project Description', 'manager_id' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'reactivate' => 1];
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
    }

    public function testCreateWithDifferentOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Project 3', 'description' => 'Project Description', 'manager_id' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'];
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertequals($content['data']['org_id'], 2);
    }

    public function testCreateWithOutNameFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['description' => 'Project Description', 'manager_id' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'];
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/project', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateAccess()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Test Project 1', 'description' => 'Project Description'];
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/project', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Project');
        $this->assertControllerName(ProjectController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ProjectController');
        $this->assertMatchedRouteName('project');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testCreateWithParentId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Project 3', 'description' => 'Project Description', 'manager_id' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'parent_id' => 'ced672bb-fe33-4f0a-b153-f1d182a02603'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Project');
        $this->assertControllerName(ProjectController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ProjectController');
        $this->assertMatchedRouteName('project');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['parent_id'], 2);
    }

    public function testUpdate()
    {
        $data = ['name' => 'Test Project', 'description' => 'Project Description'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'old_projectname' => 'Test Project 1', 'new_projectname' => 'Test Project', 'description' => 'Project Description', 'uuid' => '886d7eff-6bae-4892-baf8-6fefc56cbf0b',"parent_identifier" => null)), 'PROJECT_UPDATED')->once()->andReturn();
        }
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateWithManagerID()
    {
        $data = ['name' => 'Test Project', 'description' => 'Project Description', 'manager_id' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'old_projectname' => 'Test Project 1', 'new_projectname' => 'Test Project', 'description' => 'Project Description', 'uuid' => '886d7eff-6bae-4892-baf8-6fefc56cbf0b', "parent_identifier" => null, 'manager_login' => 'employeetest')), 'PROJECT_UPDATED')->once()->andReturn();
        }
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $select = "SELECT id,manager_id from ox_project where name = 'Test Project'";
        $project = $this->executeQueryTest($select);

        $select = "SELECT * from ox_user_project where user_id =" . $project[0]['manager_id'] . " and project_id =" . $project[0]['id'];
        $oxproject = $this->executeQueryTest($select);

        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($project[0]['manager_id'], 3);
        $this->assertEquals($oxproject[0]['user_id'], 3);
        $this->assertEquals($oxproject[0]['project_id'], 1);

    }

    public function testUpdateByManager()
    {
        $data = ['name' => 'Test Project', 'description' => 'Project Description'];
        $this->initAuthToken($this->managerUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'old_projectname' => 'Test Project 1', 'new_projectname' => 'Test Project', 'description' => 'Project Description', 'uuid' => '886d7eff-6bae-4892-baf8-6fefc56cbf0b', "parent_identifier" => null)), 'PROJECT_UPDATED')->once()->andReturn();
        }
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateByManagerofDifferentOrg()
    {
        $data = ['name' => 'Test Project', 'description' => 'Project Description'];
        $this->initAuthToken($this->managerUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b', 'PUT', null);
        $this->assertResponseStatusCode(403);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to edit the project');
    }

    public function testUpdateInvalidOrg()
    {
        $data = ['name' => 'Test Project', 'description' => 'Project Description'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Project does not belong to the organization');
    }

    public function testUpdateInvalidProjectId()
    {
        $data = ['name' => 'Test Project', 'description' => 'Project Description'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project/886d7eff-6bae-4892-bc56cbf0b', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Updating non-existent Project');
    }

    public function testUpdateRestricted()
    {
        $data = ['name' => 'Test Project 1', 'description' => 'Project Description'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Project');
        $this->assertControllerName(ProjectController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ProjectController');
        $this->assertMatchedRouteName('project');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Test Project 1', 'description' => 'Project Description'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/project/886d7eff-6bae', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'projectname' => 'Test Project 2', 'uuid' => 'ced672bb-fe33-4f0a-b153-f1d182a02603')), 'PROJECT_DELETED')->once()->andReturn();
        }
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project/ced672bb-fe33-4f0a-b153-f1d182a02603', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
    }
    //TODO write tests for sub project scenarios - create, update, delete and get APIS

    public function testDeleteWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'projectname' => 'Test Project 2', 'uuid' => 'ced672bb-fe33-4f0a-b153-f1d182a02603')), 'PROJECT_DELETED')->once()->andReturn();
        }
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project/ced672bb-fe33-4f0a-b153-f1d182a02603', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteWithOrgIdInvalidOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/ced672bb-fe33-4f0a-b153-f1d182a02603', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Project does not belong to the organization');
    }

    public function testDeleteByManagerOfDifferentOrg()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/ced672bb-fe33-4f0a-b153-f1d182a02603', 'DELETE');
        $this->assertResponseStatusCode(403);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to delete the project');
    }

    public function testDeleteByManager()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/project/ced672bb-fe33-4f0a-b153-f1d182a02603', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/project/1222', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testSaveUser()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userid' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'projectname' => 'Test Project 1', 'username' => $this->adminUser)), 'USERTOPROJECT_DELETED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'projectname' => 'Test Project 1', 'username' => $this->employeeUser)), 'USERTOPROJECT_ADDED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->adminUser, 'projectUuid' => '886d7eff-6bae-4892-baf8-6fefc56cbf0b')), 'DELETION_USERFROMPROJECT')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->employeeUser, 'firstname' => 'Employee', 'lastname' => 'Test', 'email' => 'admin3@eoxvantage.in', 'timezone' => 'United States/New York', 'projectUuid' => '886d7eff-6bae-4892-baf8-6fefc56cbf0b')), 'ADDITION_USERTOPROJECT')->once()->andReturn();
        }

        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/save', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
    }

    public function testSaveUserWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userid' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'projectname' => 'Test Project 1', 'username' => $this->adminUser)), 'USERTOPROJECT_DELETED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'projectname' => 'Test Project 1', 'username' => $this->employeeUser)), 'USERTOPROJECT_ADDED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->adminUser, 'projectUuid' => '886d7eff-6bae-4892-baf8-6fefc56cbf0b')), 'DELETION_USERFROMPROJECT')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->employeeUser, 'firstname' => 'Employee', 'lastname' => 'Test', 'email' => 'admin3@eoxvantage.in', 'timezone' => 'United States/New York', 'projectUuid' => '886d7eff-6bae-4892-baf8-6fefc56cbf0b')), 'ADDITION_USERTOPROJECT')->once()->andReturn();
        }

        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/save', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testSaveUserByManagerOfDifferentOrg()
    {
        $this->initAuthToken($this->managerUser);
        $data = ['userid' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/save', 'POST', $data);
        $this->assertResponseStatusCode(403);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to add users to project');
    }

    public function testSaveUserInvalidOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userid' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        $this->dispatch('/organization/b0971de7-0387-48e5d3704d96a46/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/save', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Project does not belong to the organization');
    }

    public function testSaveUserInvalidProject()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userid' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/8-6bae-4892-baf8-6fefc56cbf0b/save', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Entity not found');
    }

    public function testSaveUserWithoutUser()
    {
        $this->initAuthToken($this->adminUser);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/save', 'POST');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testSaveUserNotFound()
    {
        $this->initAuthToken($this->adminUser);

        $data = ['userid' => array(['uuid' => '4fd99e8e-758f-11ikwsd-dbnb'], ['uuid' => '4fd99e8e-758f-11e9'])];

        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/save', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetListOfUsers()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Admin Test');
        $this->assertEquals($content['data'][1]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][1]['name'], 'Manager Test');
        $this->assertEquals($content['total'], 2);
    }

    public function testGetListOfUsersWithOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Admin Test');
        $this->assertEquals($content['data'][1]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][1]['name'], 'Manager Test');
        $this->assertEquals($content['total'], 2);
    }

    public function testGetListOfUsersWithInvalidOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetListOfUsersWithInvalidProjectId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project/886d7eff-6bae-4892-baf8-6f/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetListOfUsersByManagerofDifferentOrg()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/users?filter=[{"take":20,"skip":0}]', 'GET');
        $this->assertResponseStatusCode(403);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to get the user list of project');
    }

    public function testGetListOfUsersWithQuery()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/users?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"r Test"}]},"sort":[{"field":"id","dir":"desc"}],"skip":0,"take":1}]
    ', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Manager Test');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListOfUsersWithPageNoQuery()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/users?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Manager Test');
        $this->assertEquals($content['total'], 2);
    }

    public function testGetListOfUsersWithQueryParameter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/886d7eff-6bae-4892-baf8-6fefc56cbf0b/users?filter=[{"filter":{"filters":[{"field":"name","operator":"startswith","value":"Manager"}]},"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Manager Test');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListOfUsersNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/64/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
        $this->assertEquals($content['total'], 0);
    }

    public function testGetMyProjectList()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['data' => array([
            "id" => "1",
            "name" => "Test Project 1",
            "org_id" => "1",
            "description" => "Description Test Data",
            "created_by" => "1",
            "modified_by" => "1",
            "date_created" => "2018-11-11 07:25:06",
            "date_modified" => "2018-12-11 07:25:06",
            "isdeleted" => "0",
            "user_id" => "1",
            "project_id" => "1",
        ], [
            "id" => "3",
            "name" => "Test Project 2",
            "org_id" => "1",
            "description" => "Description Test Data",
            "created_by" => "1",
            "modified_by" => "1",
            "date_created" => "2018-11-11 07:25:06",
            "date_modified" => "2018-12-11 07:25:06",
            "isdeleted" => "0",
            "user_id" => "1",
            "project_id" => "2",
        ])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project/myproject', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('myproject');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
    }

    public function testGetMyProjectListWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['data' => array([
            "id" => "1",
            "name" => "Test Project 1",
            "org_id" => "1",
            "description" => "Description Test Data",
            "created_by" => "1",
            "modified_by" => "1",
            "date_created" => "2018-11-11 07:25:06",
            "date_modified" => "2018-12-11 07:25:06",
            "isdeleted" => "0",
            "user_id" => "1",
            "project_id" => "1",
        ], [
            "id" => "3",
            "name" => "Test Project 2",
            "org_id" => "1",
            "description" => "Description Test Data",
            "created_by" => "1",
            "modified_by" => "1",
            "date_created" => "2018-11-11 07:25:06",
            "date_modified" => "2018-12-11 07:25:06",
            "isdeleted" => "0",
            "user_id" => "1",
            "project_id" => "2",
        ])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/project/myproject', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('myproject');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
    }

    public function testGetMyProjectListByManager()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/myproject', 'GET');
        $this->assertResponseStatusCode(403);
        $this->setDefaultAsserts('myproject');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to get the users of project');
    }

    public function testGetMyProjectListDifferentOrg()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/project/myproject', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('myproject');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetMyProjectListWithoutdata()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/myproject', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('myproject');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
}
