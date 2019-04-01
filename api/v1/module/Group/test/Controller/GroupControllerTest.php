<?php
namespace Group;

use Group\Controller\GroupController;
use Group\Model;
use Oxzion\Test\ControllerTest;
use Bos\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Service\GroupService;
use Mockery;
use Oxzion\Messaging\MessageProducer;


class GroupControllerTest extends ControllerTest {

    public function setUp() : void {
        $this->loadConfig();
        parent::setUp();
    }

    public function getMockMessageProducer(){
        $organizationService = $this->getApplicationServiceLocator()->get(Service\GroupService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $organizationService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Group.yml");
        return $dataset;
    }

    protected function setDefaultAsserts() {
        $this->assertModuleName('Group');
        $this->assertControllerName(GroupController::class); // as specified in router's controller name alias
        $this->assertControllerClass('GroupController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testgetGroupsforUser() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_group'));
    }

    public function testgetGroupsforUserNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/10000', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

//Testing to see if the Create Group function is working as intended if all the value passed are correct.
    public function testCreate() {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Groups 22', 'parent_id'=> 9, 'org_id'=>1, 'manager_id' => 436, 'description
        '=>'Description Test Data', 'logo' => 'grp1.png', 'cover_photo'=>'grp1.png', 'type' => 1, 'status' => 'Active'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_group'));
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
        $mockMessageProducer = $this->getMockMessageProducer();
        $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupname' => 'Groups 22', 'orgname'=>'Cleveland Cavaliers')),'GROUP_ADDED')->once()->andReturn();
        }
        $this->dispatch('/group', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['parent_id'], 9);
        $this->assertEquals($content['data']['org_id'], 1);
        $this->assertEquals($content['data']['manager_id'], 436);
        $this->assertEquals($content['data']['description'], $data['description']);
        $this->assertEquals($content['data']['logo'], "grp1.png");
        $this->assertEquals($content['data']['cover_photo'], "grp1.png");
        $this->assertEquals($content['data']['type'], 1);
        $this->assertEquals($content['data']['status'], "Active");
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_group'));
    }

//Test Case to check the errors when the required field is not selected. Here I removed the parent_id field from the list.
    public function testCreateWithoutRequiredField() {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Groups 22', 'manager_id' => 436, 'description
        '=>'Description Test Data', 'logo' => 'grp1.png', 'cover_photo'=>'grp1.png', 'type' => 1, 'status' => 'Active'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_group'));
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/group', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        //  print_r($content);exit;
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['parent_id'], 'required');
    }

    public function testUpdate() {
        $data = ['name' => 'Test Create Group', 'parent_id'=> 9, 'org_id'=>1, 'manager_id' => 436, 'description
        '=>'Description Test Data', 'logo' => 'grp1.png', 'cover_photo'=>'grp1.png', 'type' => 1, 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('old_groupname' => 'Test Group', 'orgname'=> 'Cleveland Cavaliers' , 'new_groupname'=> 'Test Create Group')),'GROUP_UPDATED')->once()->andReturn();
        }
        $this->dispatch('/group/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'Test Create Group');
        $this->assertEquals($content['data']['parent_id'], 9);
        $this->assertEquals($content['data']['org_id'], 1);
        $this->assertEquals($content['data']['manager_id'], 436);
        $this->assertEquals($content['data']['description'], "Description Test Data");
        $this->assertEquals($content['data']['logo'], "grp1.png");
        $this->assertEquals($content['data']['cover_photo'], "grp1.png");
        $this->assertEquals($content['data']['type'], 1);
        $this->assertEquals($content['data']['status'], "Active");
    }

    public function testUpdateNotFound() {
        $data = ['name' => 'Test Create Group', 'org_id'=>1, 'manager_id' => 436, 'description
        '=>'Description Test Data', 'logo' => 'grp1.png', 'cover_photo'=>'grp1.png', 'type' => 1, 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
           $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/group/10000', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUpdateWithoutRequiredField() {
        $data = ['name' => 'Test Create Group', 'org_id'=>1, 'manager_id' => 436, 'description
        '=>'Description Test Data', 'logo' => 'grp1.png', 'cover_photo'=>'grp1.png', 'type' => 1, 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/group/1', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete() {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupname' => 'Test Group', 'orgname'=>'Cleveland Cavaliers')),'GROUP_DELETED')->once()->andReturn();
        }
        $this->dispatch('/group/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound() {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/group/10000', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testsaveuser() {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupname' => 'Test Group', 'orgname'=>'Cleveland Cavaliers','username' => 'bharatg')),'USERTOGROUP_DELETED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupname' => 'Test Group', 'orgname'=>'Cleveland Cavaliers','username' => 'rakshith')),'USERTOGROUP_ADDED')->once()->andReturn();
        }
        $this->dispatch('/group/1/save','POST',array('userid' => '[{"id":2},{"id":3}]')); 
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success'); 
    }

    public function testsaveuserwithoutuser() {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/group/1/save','POST'); 
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error'); 
    }

    public function testsaveusernotfound() {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/group/1/save','POST',array('userid' => '[{"id":1},{"id":23}]')); 
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testgetuserlist() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/1/users','GET'); 
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success'); 
    }

    public function testgetuserlistNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/64/users','GET'); 
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error'); 
    }

}