<?php
namespace Organization;

use Bos\Db\ModelTable;
use Organization\Controller\OrganizationController;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Service\OrganizationService;
use Mockery;
use Oxzion\Messaging\MessageProducer;

class OrganizationControllerTest extends ControllerTest
{
    protected $topic;
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getMockMessageProducer(){
        $organizationService = $this->getApplicationServiceLocator()->get(OrganizationService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $organizationService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Organization.yml");
        return $dataset;
    }

//Testing to see if the Create Contact function is working as intended if all the value passed are correct.

    // public function testGetList()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $this->dispatch('/organization', 'GET');
    //     $this->assertResponseStatusCode(200);
    //     $this->setDefaultAsserts();
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'success');
    //     $this->assertEquals(count($content['data']), 2);
    //     $this->assertEquals($content['data'][0]['id'], 1);
    //     $this->assertEquals($content['data'][0]['name'], 'Cleveland Cavaliers');
    //     $this->assertEquals($content['data'][1]['id'], 2);
    //     $this->assertEquals($content['data'][1]['name'], 'Golden State Warriors');
    // }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    // public function testGet()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $mockMessageProducer = $this->getMockMessageProducer();
    //     $this->dispatch('/organization/1', 'GET');
    //     $this->assertResponseStatusCode(200);
    //     $this->setDefaultAsserts();
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'success');
    //     $this->assertEquals($content['data']['id'], 1);
    //     $this->assertEquals($content['data']['name'], 'Cleveland Cavaliers');
    // }

    // public function testGetNotFound()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $mockMessageProducer = $this->getMockMessageProducer();
    //     $this->dispatch('/organization/64', 'GET');
    //     $this->assertResponseStatusCode(404);
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    // }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Cleveland Black', 'logo' => 'logo.png', 'status' => 'Active'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_organization'));
        $this->setJsonContent(json_encode($data));
        $mockMessageProducer = $this->getMockMessageProducer();
        $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'Active')),'ORGANIZATION_ADDED')->once()->andReturn();
        $this->dispatch('/organization', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('organization');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_organization'));
    }

    public function testCreateWithOutNameFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['logo' => 'logo.png', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $mockMessageProducer = $this->getMockMessageProducer();
        $this->dispatch('/organization', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateAccess()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Cleveland Black', 'logo' => 'logo.png', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $mockMessageProducer = $this->getMockMessageProducer();
        $this->dispatch('/organization', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertMatchedRouteName('organization');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdate()
    {
        $data = ['name' => 'Cleveland Black', 'logo' => 'logo.png', 'status' => 'InActive'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $mockMessageProducer = $this->getMockMessageProducer();
        $mockMessageProducer->expects('sendTopic')->with(json_encode(array('new_orgname' => 'Cleveland Black','old_orgname'=> 'Cleveland Cavaliers','status' => 'InActive')),'ORGANIZATION_UPDATED')->once()->andReturn();
        $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Cavaliers', 'status' => 'InActive')),'ORGANIZATION_DELETED')->once()->andReturn();
        $this->dispatch('/organization/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
    }

    public function testUpdateRestricted()
    {
        $data = ['name' => 'Cleveland Blacks', 'logo' => 'logo.png', 'status' => 'Active'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $mockMessageProducer = $this->getMockMessageProducer();
        $this->dispatch('/organization/1', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertMatchedRouteName('organization');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Cleveland Blacks', 'logo' => 'logo.png', 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $mockMessageProducer = $this->getMockMessageProducer();
        $this->dispatch('/organization/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $mockMessageProducer = $this->getMockMessageProducer();
        $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Golden State Warriors', 'status' => 'Inactive')),'ORGANIZATION_DELETED')->once()->andReturn();
        $this->dispatch('/organization/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $mockMessageProducer = $this->getMockMessageProducer();
        $this->dispatch('/organization/1222', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testAddUserToOrganization()
    {
        $this->initAuthToken($this->adminUser);
        $mockMessageProducer = $this->getMockMessageProducer();
        $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => 'rakshith', 'orgname' => 'Golden State Warriors', 'status' => 'Active')),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        $this->dispatch('/organization/2/adduser/3', 'POST');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToOrganizationWithSameData()
    {
        $this->initAuthToken($this->adminUser);
        $mockMessageProducer = $this->getMockMessageProducer();
        $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => 'karan', 'orgname' => 'Cleveland Cavaliers', 'status' => 'Active')),'USERTOORGANIZATION_ALREADYEXISTS')->once()->andReturn();
        $this->dispatch('/organization/1/adduser/2', 'POST');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testAddUserToOrganizationWithDifferentUser()
    {
        $this->initAuthToken($this->adminUser);
        $mockMessageProducer = $this->getMockMessageProducer();
        $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => 'rakshith', 'orgname' => 'Cleveland Cavaliers', 'status' => 'Active')),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        $this->dispatch('/organization/1/adduser/3', 'POST');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

}