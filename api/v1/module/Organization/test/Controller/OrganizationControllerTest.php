<?php
namespace Organization;

use Bos\Db\ModelTable;
use Zend\Db\Adapter\AdapterInterface;
use Organization\Controller\OrganizationController;
use Oxzion\Test\MainControllerTest;
use Oxzion\Service\OrganizationService;
use Mockery;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Utils\FileUtils;



class OrganizationControllerTest extends MainControllerTest
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

   
//Testing to see if the Create Contact function is working as intended if all the value passed are correct.

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['name'], 'Cleveland Black');
        $this->assertEquals($content['data']['data'][1]['id'], 2);
        $this->assertEquals($content['data']['data'][1]['name'], 'Golden State Warriors');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 1);
        $this->assertEquals($content['data']['pagination']['pageSize'], 20);
    }

    public function testGetListWithQuery()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization?f=state&q=o&psz=1&pg=2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data']['data'][0]['id'], 2);
        $this->assertEquals($content['data']['data'][0]['name'], 'Golden State Warriors');
        $this->assertEquals($content['data']['pagination']['page'], 2);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 2);
        $this->assertEquals($content['data']['pagination']['pageSize'], 1);
    }


    public function testGetListWithQueryField()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization?f=name&q=gol', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data']['data'][0]['id'], 2);
        $this->assertEquals($content['data']['data'][0]['name'], 'Golden State Warriors');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 1);
        $this->assertEquals($content['data']['pagination']['pageSize'], 20);
    }

    public function testGetListWithQueryPageNo()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization?f=state&q=o&psz=1&pg=1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['name'], 'Cleveland Black');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 2);
        $this->assertEquals($content['data']['pagination']['pageSize'], 1);
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Cleveland Black');
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-494', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['DATA_FOLDER']."organization/".$this->testOrgId."/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__."/../files/logo.png", $tempFolder."logo.png");
        $contact = array('firstname'=>'Bharat','lastname'=>'Gogineni','email'=>'bharat@myvamla.com');
        $data = array('name'=>'ORGANIZATION','address' => 'Bangalore','contact' => json_encode($contact));
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'ORGANIZATION', 'status' => 'Active')),'ORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->dispatch('/organization', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('organization');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
    }

    public function testCreateWithOutNameFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['logo' => 'logo.png', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
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
        $data = ['name' => 'Cleveland Cavaliers', 'logo' => 'logo.png', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
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
        $data = ['name' => 'Cleveland Cavaliers', 'logo' => 'logo.png', 'status' => 'InActive'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('new_orgname' => 'Cleveland Cavaliers','old_orgname'=> 'Cleveland Black','status' => 'InActive')),'ORGANIZATION_UPDATED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'InActive')),'ORGANIZATION_DELETED')->once()->andReturn();
        }
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
        $data = ['name' => 'Cleveland Cavaliers', 'logo' => 'logo.png', 'status' => 'Active'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
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

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a', 'DELETE');
          if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'InActive')),'ORGANIZATION_DELETED')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');        
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/organization/53012471-2863-4', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testAddUserToOrganization()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/1/adduser/3', 'POST');
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => 'rakshith', 'orgname' => 'Golden State Warriors', 'status' => 'Active')),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToOrganizationWithSameData()
    {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => 'karan', 'orgname' => 'Cleveland Black', 'status' => 'Active')),'USERTOORGANIZATION_ALREADYEXISTS')->once()->andReturn();
        }
        $this->dispatch('/organization/1/adduser/2', 'POST');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testAddUserToOrganizationWithDifferentUser()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/1/adduser/3', 'POST');
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => 'rakshith', 'orgname' => 'Cleveland Black', 'status' => 'Active')),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

}