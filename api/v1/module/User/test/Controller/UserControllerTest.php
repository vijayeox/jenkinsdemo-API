<?php
namespace User;

use User\Controller\UserController;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class UserControllerTest extends ControllerTest{

    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   

    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/User.yml");
        $dataset->addYamlFile(dirname(__FILE__)."/../Dataset/Project.yml");
        return $dataset;
    }

    protected function setDefaultAsserts($router = "user"){
        $this->assertModuleName('User');
        $this->assertControllerName(UserController::class); // as specified in router's controller name alias
        $this->assertControllerClass('UserController');
        $this->assertMatchedRouteName($router);
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    
    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'John Holt','status'=>'1','dob'=>date('Y-m-d H:i:s', strtotime("-50 year")),'doj'=>date('Y-m-d H:i:s'),'icon'=>'test-oxzionlogo.png', 'managerid' =>'471', 'firstname' =>'John', 'lastname' => 'Holt', 'username' => 'johnh', 'password' => 'welcome2oxzion', 'designation' => 'CEO', 'level' => '7', 'cluster' => 'Management', 'location' => 'USA','gamelevel'=>'Wanna be','email'=>'harshva.com','sex'=>'M','role'=>'employee','listtoggle'=>1,'mission_link'=>'test'];
        $this->assertEquals(3, $this->getConnection()->getRowCount('avatars'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user', 'POST', null);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content['data']['id']);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['dob'], $data['dob']);
        $this->assertEquals($content['data']['doj'], $data['doj']);
        $this->assertEquals($content['data']['icon'], $data['icon']);
        $this->assertEquals($content['data']['managerid'], $data['managerid']);
        $this->assertEquals($content['data']['firstname'], $data['firstname']);
        $this->assertEquals($content['data']['lastname'], $data['lastname']);
        $this->assertEquals($content['data']['username'], $data['username']);
        $this->assertEquals($content['data']['email'], $data['email']);
        $this->assertEquals($content['data']['cluster'], $data['cluster']);
        $this->assertEquals($content['data']['level'], $data['level']);
        $this->assertEquals($content['data']['cluster'], $data['cluster']);
        $this->assertEquals($content['data']['location'], $data['location']);
        $this->assertEquals(4, $this->getConnection()->getRowCount('avatars'));
    }
    public function testCreateWithOutNameFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['status'=>'1','dob'=>date('Y-m-d H:i:s', strtotime("-50 year")),'doj'=>date('Y-m-d H:i:s'),'icon'=>'test-oxzionlogo.png', 'managerid' =>'471', 'firstname' =>'John', 'lastname' => 'Holt', 'username' => 'johnh', 'password' => 'welcome2oxzion', 'designation' => 'CEO', 'level' => '7', 'cluster' => 'Management', 'location' => 'USA','gamelevel'=>'Wanna be','email'=>'harshva.com','sex'=>'M','listtoggle'=>1,'mission_link'=>'test'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user', 'POST', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }
    
    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['data'][1]['id'], 2);
        $this->assertEquals($content['data'][1]['name'], 'Karan Agarwal');
        $this->assertEquals($content['data'][2]['id'], 3);
        $this->assertEquals($content['data'][2]['name'], 'Rakshith Amin');
    }
    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Bharat Gogineni');
    }
    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/64', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }


    public function testUpdate(){
        $data = ['name' => 'John Holt','status'=>'1','dob'=>date('Y-m-d H:i:s', strtotime("-50 year")),'doj'=>date('Y-m-d H:i:s'),'icon'=>'test-oxzionlogo.png', 'managerid' =>'471', 'firstname' =>'John', 'lastname' => 'Holt', 'username' => 'johnh', 'password' => 'welcome2oxzion', 'designation' => 'CEO', 'level' => '7', 'cluster' => 'Management', 'location' => 'USA','gamelevel'=>'Wanna be','email'=>'harshva.com','sex'=>'M','listtoggle'=>1,'mission_link'=>'test'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
    }

    public function testUpdateNotFound(){
        $data = ['name' => 'Test User','groups'=>'[{"id":1},{"id":2}]','status'=>1,'start_date'=>date('Y-m-d H:i:s'),'end_date'=>date('Y-m-d H:i:s',strtotime("+7 day"))];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/3', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }

    public function testassignManagerToUser() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/assign/2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('assignUserManager');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_user_manager'));
    }


    public function testassignUserExists() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/assign/3', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('assignUserManager');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals(1, $this->getConnection()->getRowCount('ox_user_manager'));
    }

    public function testremoveManagerToUser() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/remove/3', 'delete', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('removeUserManager');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testaddusertogroup() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/3/addusertogroup/1', 'POST');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addusertogroup');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToGroupWithExistingGroup() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/addusertogroup/1', 'POST');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('addusertogroup');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testaddusertoproject() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/3/addusertoproject/1', 'POST');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addusertoproject');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToProjectWithExistingProject() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/addusertoproject/1', 'POST');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('addusertoproject');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
