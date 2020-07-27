<?php
namespace User;

use User\Controller\UserController;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Service\UserService;
use Mockery;
use Oxzion\Messaging\MessageProducer;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Stdlib\ArrayUtils;
use User\Controller\ForgotPasswordController;

class UserControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getMockMessageProducer()
    {
        $organizationService = $this->getApplicationServiceLocator()->get(UserService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $organizationService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/User.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../../../Project/test/Dataset/Project.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../../../Group/test/Dataset/Group.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../../../Role/test/Dataset/Role.yml");
        return $dataset;
    }

    protected function setDefaultAsserts($router = "user")
    {
        $this->assertModuleName('User');
        $this->assertControllerName(UserController::class); // as specified in router's controller name alias
        $this->assertControllerClass('UserController');
        $this->assertMatchedRouteName($router);
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

   

    public function testCreateByAdmin()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'John Holt', 'status' => 'Active', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'harshva.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7'],['id' => '5ecccd2d-4dc7-4e19-ae5f-adb3c8f48073'])];
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USER_ADDED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->dispatch('/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $query = "SELECT ox_user.id,usrp.address_id from ox_user join ox_user_profile usrp on usrp.id=ox_user.user_profile_id where username = '" .$data['username']."'";
        $userId = $this->executeQueryTest($query);

        $query = "SELECT * from ox_address where id = ".$userId[0]['address_id'];
        $address = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_org where user_id = ".$userId[0]['id'];
        $userOrg = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_role where user_id = ".$userId[0]['id'];
        $userRole = $this->executeQueryTest($query);

        $query = "SELECT designation,date_of_join from ox_employee inner join ox_user on ox_user.user_profile_id = ox_employee.user_profile_id  where username = '" .$data['username']."'";
        $empDetails = $this->executeQueryTest($query);

        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content['data']['id']);
        $this->assertEquals($content['data']['username'], $data['username']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($userOrg[0]['user_id'], $userId[0]['id']);
        $this->assertEquals($userOrg[0]['org_id'],1);
        $this->assertEquals($userRole[0]['user_id'], $userId[0]['id']);
        $this->assertEquals($userRole[0]['role_id'], 16);
        $this->assertEquals($userRole[1]['role_id'], 17);
        $this->assertEquals($address[0]['address1'], $data['address1']);
        $this->assertEquals($address[0]['city'], $data['city']);
        $this->assertEquals($address[0]['state'], $data['state']);
        $this->assertEquals($address[0]['country'], $data['country']);
        $this->assertEquals($empDetails[0]['designation'], $data['designation']);
        $this->assertEquals($empDetails[0]['date_of_join'], $data['date_of_join']);
    }

    public function testCreateWithRoleOfOtherOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'John Holt', 'status' => 'Active', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d'), 'icon' => 'test-oxzionlogo.png','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'harshva.com', 'gender' => 'Male','role' => array(['id' => '508572ae-a6c2-11e9-b648-68ecc57cde45'],['id' => '50873a47-a6c2-11e9-b648-68ecc57cde45'])];
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USER_ADDED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->dispatch('/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);

        $query = "SELECT id from ox_user where username = '" .$data['username']."'";
        $userId = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_org where user_id = ".$userId[0]['id'];
        $userOrg = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_role where user_id = ".$userId[0]['id'];
        $userRole = $this->executeQueryTest($query);

        $query = "SELECT designation,date_of_join from ox_employee inner join ox_user on ox_user.user_profile_id = ox_employee.user_profile_id  where username = '" .$data['username']."'";
        $empDetails = $this->executeQueryTest($query);

        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content['data']['id']);
        $this->assertEquals($content['data']['username'], $data['username']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($userOrg[0]['user_id'], $userId[0]['id']);
        $this->assertEquals($userOrg[0]['org_id'],1);
        $this->assertEquals($empDetails[0]['designation'], $data['designation']);
        $this->assertEquals($empDetails[0]['date_of_join'], $data['date_of_join']);
        $this->assertEquals(count($userRole),0);
    }

    public function testCreateByEmployee()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['username' => 'John Holt', 'status' => 'Active', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'harshva.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '50873baa-a6c2-11e9-b648-68ecc57cde45'],['id' => '50873bf0-a6c2-11e9-b648-68ecc57cde45'])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user', 'POST', $data);
        $this->assertResponseStatusCode(401);
         $this->assertModuleName('User');
        $this->assertControllerName(UserController::class); // as specified in router's controller name alias
        $this->assertControllerClass('UserController');
        $this->assertMatchedRouteName('user');
               $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'],'You have no Access to this API');
    }

    public function testCreateforExistingInactiveUserWithoutReactivateFlag()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'rajesh', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'john@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7'],['id' => '5ecccd2d-4dc7-4e19-ae5f-adb3c8f48073'])];
        $this->setJsonContent(json_encode($data));
        
        $this->dispatch('/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User already exists would you like to reactivate?');
    }


    public function testCreateforExistingInactiveUserWithReactivateFlag()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'rajesh', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'john@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7'],['id' => '5ecccd2d-4dc7-4e19-ae5f-adb3c8f48073']),'reactivate' => 1];
        $this->setJsonContent(json_encode($data));
        
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USER_ADDED')->once()->andReturn();
        }

        $this->dispatch('/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        
        $query = "SELECT status from ox_user where username = '" .$data['username']."'";
        $userStatus = $this->executeQueryTest($query);

        $query = "SELECT id from ox_user where username = '" .$data['username']."'";
        $userId = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_org where user_id = ".$userId[0]['id'];
        $userOrg = $this->executeQueryTest($query);


        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'], $data['username']);
        $this->assertEquals($userStatus[0]['status'], 'Active');
        $this->assertEquals($userOrg[0]['org_id'],2);
        $this->assertEquals($userOrg[1]['org_id'],1);
    } 


    public function testCreateforExistingUser()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'rajesh', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'john@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7'],['id' => '5ecccd2d-4dc7-4e19-ae5f-adb3c8f48073'])];
        $this->setJsonContent(json_encode($data));
        
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USER_ADDED')->once()->andReturn();
        }

        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User already exists would you like to reactivate?');
    } 

    public function testCreateforOtherOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'rajesha', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'john@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '53012471-2863'])];
        $this->setJsonContent(json_encode($data));
        
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USER_ADDED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }

        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

       
    
        $query = "SELECT id from ox_user where username = '" .$data['username']."'";
        $userId = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_org where user_id = ".$userId[0]['id'];
        $userOrg = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_role where user_id = ".$userId[0]['id'];
        $userRole = $this->executeQueryTest($query);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'], $data['username']);
        $this->assertEquals($userOrg[0]['org_id'],2);
        $this->assertEquals($userRole[0]['user_id'], $userId[0]['id']);
        $this->assertEquals($userRole[0]['role_id'], 15);
    }

    public function testCreateExistingUsernameInDifferentOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'deepak', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'john@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '53012471-2863'])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Username or Email Exists in other Organization');
    }

    public function testCreateExistingUsername()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'deepak', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'john@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '53012471-2863'])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Username/Email Exists');
    }

    public function testCreateExistingEmailId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'raju', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'deepak@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '53012471-2863'])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Email Exists');
    }
    
    public function testCreateExistingEmailIdInDifferentOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'raju', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'deepak@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '53012471-2863'])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Username or Email Exists in other Organization');
    }

    
    public function testCreateExistingEmailIdInactiveUser()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'raju', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'prajwal@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7'])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User already exists would you like to reactivate?');
    }


    public function testCreateExistingUsernameInactiveUser()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'prajwal', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'klmn@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7'])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User already exists would you like to reactivate?');
    }

    public function testCreateExistingUsernameInactiveUserWithReactivateFlag()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'prajwal', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'klmn@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7']),'reactivate' => 1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $query = "SELECT id from ox_user where username = '" .$data['username']."'";
        $userId = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_org where user_id = ".$userId[0]['id'];
        $userOrg = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_role where user_id = ".$userId[0]['id'];
        $userRole = $this->executeQueryTest($query);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'], $data['username']);
        $this->assertEquals($userOrg[0]['org_id'],1);
        $this->assertEquals($userRole[0]['user_id'], $userId[0]['id']);
        $this->assertEquals($userRole[0]['role_id'], 16);
    }



    public function testCreateExistingUsernameAndEmailIdInactiveUser()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'prajwal', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'prajwal@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7'])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User already exists would you like to reactivate?');
    }


    public function testCreateExistingUsernameAndEmailIdInactiveUserWithReactivateFlag()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'prajwal', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'prajwal@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7']),'reactivate' => 1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $query = "SELECT id from ox_user where username = '" .$data['username']."'";
        $userId = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_org where user_id = ".$userId[0]['id'];
        $userOrg = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_role where user_id = ".$userId[0]['id'];
        $userRole = $this->executeQueryTest($query);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'], $data['username']);
        $this->assertEquals($userOrg[0]['org_id'],1);
        $this->assertEquals($userRole[0]['user_id'], $userId[0]['id']);
        $this->assertEquals($userRole[0]['role_id'], 16);
    }

    public function testCreateUsernameAndEmailIdExist()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'prajwal', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'deepak@gmail.com', 'gender' => 'Male','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7'])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Username or Email Exists in other Organization');
    }


//GET
    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Admin Test');
        $this->assertEquals($content['data'][1]['uuid'], 'd9890624-8f42-4201-bbf9-675ec5dc8400');
        $this->assertEquals($content['data'][1]['name'], 'Deepak S');
        $this->assertEquals($content['data'][2]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][2]['name'], 'Manager Test');
    }

    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?filter=[{"sort":[{"field":"name","dir":"dsc"}],"skip":0,"take":2}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '768d1fb9-de9c-46c3-8d5c-23e0e484ce2e');
        $this->assertEquals($content['data'][0]['name'], 'Cleveland Test');
        $this->assertEquals($content['data'][1]['uuid'], '4fd9f04d-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][1]['name'], 'Employee Test');
    }

    public function testGetListWithSortForCountry()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?filter=[{"sort":[{"field":"country","dir":"dsc"}],"skip":0,"take":2}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '768d1fb9-de9c-46c3-8d5c-23e0e484ce2e');
        $this->assertEquals($content['data'][0]['name'], 'Cleveland Test');
        $this->assertEquals($content['data'][1]['uuid'], 'd9890624-8f42-4201-bbf9-675ec5dc8400');
        $this->assertEquals($content['data'][1]['name'], 'Deepak S');
    }

    public function testGetListWithFilterForCountry()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?filter=[{"filter":{"logic":"and","filters":[{"field":"country","operator":"startswith","value":"Ghana"}]},"sort":[{"field":"id","dir":"asc"},{"field":"uuid","dir":"dsc"}],"skip":0,"take":2}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Manager Test');
        $this->assertEquals($content['data'][1]['uuid'], '4fd9f04d-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][1]['name'], 'Employee Test');
    }

     public function testGetListWithMultipleFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?filter=[{"filter":{"logic":"and","filters":[{"field":"country","operator":"startswith","value":"in"},{"field":"address1","operator":"endswith","value":"r"},{"field":"address2","operator":"contains","value":"mba"},{"field":"state","operator":"startswith","value":"Tamil"}]},"sort":[{"field":"id","dir":"asc"},{"field":"uuid","dir":"dsc"}],"skip":0,"take":2}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], 'd9890624-8f42-4201-bbf9-675ec5dc8400');
        $this->assertEquals($content['data'][0]['name'], 'Deepak S');
    }
     public function testGetListSortWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?filter=[{"sort":[{"field":"name","dir":"asc"}],"skip":2,"take":2}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Manager Test');
    }

    public function testGetListwithQueryParameters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?filter=[{"filter":{"logic":"and","filters":[{"field":"name","operator":"endswith","value":"al"},{"field":"designation","operator":"startswith","value":"it"}]},"sort":[{"field":"id","dir":"asc"},{"field":"uuid","dir":"dsc"}],"skip":0,"take":2}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Manager Test');
        $this->assertEquals($content['total'],1);
    }

    public function testGetListwithQueryWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?filter=[{"filter":{"logic":"and","filters":[{"field":"name","operator":"endswith","value":"ni"},{"field":"designation","operator":"startswith","value":"it"}]},"sort":[{"field":"id","dir":"asc"},{"field":"uuid","dir":"dsc"}],"skip":0,"take":1}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Admin Test');
        $this->assertEquals($content['total'],1);
    }

    public function testGetListwithQueryPage()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?filter=[{"skip":0,"take":1}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Admin Test');
    }

    public function testGetListwithQueryPageNo()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?filter=[{"skip":1,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], 'd9890624-8f42-4201-bbf9-675ec5dc8400');
        $this->assertEquals($content['data'][0]['name'], 'Deepak S');
        $this->assertEquals($content['data'][1]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][1]['name'], 'Manager Test');
      }


    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'], $this->adminUser);
        $this->assertEquals($content['data']['name'], 'Admin Test');
        $this->assertEquals($content['data']['country'], 'Germany');
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/4fd99e8e-758f-11e9-b2d5-68ecc57c3456', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUpdate()
    {
        $data = ['firstname' => 'John','lastname' => 'Holt'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['firstname'], $data['firstname']);
        $this->assertEquals($content['data']['lastname'], $data['lastname']);
    }

    public function testUpdateWithAddress()
    {
        $data = ['firstname' => 'John','lastname' => 'Holt','address1' => 'Banshankari','city' => 'Bangalore', 'state' => 'Karnataka','country' => 'India','zip' => '23456',];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $query = "SELECT address_id from ox_user inner join ox_user_profile on ox_user.user_profile_id = ox_user_profile.id where ox_user.uuid = '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'";
        $addId = $this->executeQueryTest($query);

        $query = "SELECT * from ox_address where id = ".$addId[0]['address_id'];
        $address = $this->executeQueryTest($query);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['firstname'], $data['firstname']);
        $this->assertEquals($content['data']['lastname'], $data['lastname']);
        $this->assertEquals($address[0]['address1'],$data['address1']);
        $this->assertEquals($address[0]['city'],$data['city']);
        $this->assertEquals($address[0]['state'],$data['state']);
    }


    public function testUpdateWithOrgID()
    {
        $data = ['firstname' => 'John','lastname' => 'Holt'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'PUT', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['firstname'], $data['firstname']);
        $this->assertEquals($content['data']['lastname'], $data['lastname']);
    }


    public function testUpdateWithInvalidOrgID()
    {
        $data = ['firstname' => 'John','lastname' => 'Holt'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'PUT', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User does not belong to the organization');
    }


    public function testUpdateWithInvalidUserid()
    {
        $data = ['firstname' => 'John','lastname' => 'Holt'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user/4fd99e8e-e9-b2d5-68ecc57cde45', 'PUT', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User not found');
    }

    public function testUpdateNotFound()
    {
        $data = ['firstname' => 'Test','lastname' => 'User'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/4fd99e8e-758f-11e9-b2d5-68ecc57c6543', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->employeeUser, 'orgname' => 'Cleveland Black')),'USER_DELETED')->once()->andReturn();
        }
        $this->dispatch('/user/4fd9f04d-758f-11e9-b2d5-68ecc57cde45', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }


    public function testDeleteProjectManager()
    {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->employeeUser, 'orgname' => 'Cleveland Black')),'USER_DELETED')->once()->andReturn();
        }
        $this->dispatch('/user/768d1fb9-de9c-46c3-8d5c-23e0e484ce2e', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'],'Not allowed to delete the project manager');
    }

    public function testDeleteGroupManager()
    {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->employeeUser, 'orgname' => 'Cleveland Black')),'USER_DELETED')->once()->andReturn();
        }
        $this->dispatch('/user/4fd9ce37-758f-11e9-b2d5-68ecc57cde45', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'],'Not allowed to delete the group manager');
    }

    
    public function testDeleteWithOrgID()
    {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->employeeUser, 'orgname' => 'Cleveland Black')),'USER_DELETED')->once()->andReturn();
        }
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user/4fd9f04d-758f-11e9-b2d5-68ecc57cde45', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteInvalidOrgID()
    {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->employeeUser, 'orgname' => 'Cleveland Black')),'USER_DELETED')->once()->andReturn();
        }
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/user/4fd9f04d-758f-11e9-b2d5-68ecc57cde45', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User does not belong to the organization');
    }

    public function testDeleteInvalidUserID()
    {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->employeeUser, 'orgname' => 'Cleveland Black')),'USER_DELETED')->once()->andReturn();
        }
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user/4fd9f04d-7e9-b2d5-68ecc57cde45', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User not found');
    }

    public function testDeleteAdminUser()
    {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => $this->employeeUser, 'orgname' => 'Cleveland Black')),'USER_DELETED')->once()->andReturn();
        }
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Not allowed to delete Admin user');
    }


    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/user/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testAssignManagerToUser()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/assign/2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('assignUserManager');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_user_manager'));
    }


    public function testAssignUserExists()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/assign/3', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('assignUserManager');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals(1, $this->getConnection()->getRowCount('ox_user_manager'));
    }

    public function testRemoveManagerToUser()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/remove/3', 'delete', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('removeUserManager');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }


    public function testAddUserToProject()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/3/addusertoproject/1', 'POST');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addusertoproject');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToProjectWithExistingProject()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/addusertoproject/1', 'POST');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('addusertoproject');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUserLoginToken()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/usertoken', 'get');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('userToken');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }



    public function testUserSearch()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['searchVal' => 'Karan', 'firstname' => 'Karan', 'lastname' => 'Agarwal'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/usersearch', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('userSearch');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['firstname'], $data['firstname']);
        $this->assertEquals($content['data'][0]['lastname'], $data['lastname']);
    }


    public function testUserSearchNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['searchVal' => 'errorName', 'firstname' => 'Karan', 'lastname' => 'Agarwal'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/usersearch', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('userSearch');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], "No results found for " . $data['searchVal']);
    }

    public function testChangePassword()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['old_password' => 'password', 'new_password' => 'welcome', 'confirm_password' => 'welcome'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/changepassword', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('changepassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testChangePasswordWithWrongOldPassword()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['old_password' => 'wrongPassword', 'new_password' => 'welcome', 'confirm_password' => 'welcome'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/changepassword', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('changepassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testChangePasswordWithDifferentPassword()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['old_password' => 'password', 'new_password' => 'welcome', 'confirm_password' => 'wrongConfirmPassword'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/changepassword', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('changepassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testChangePasswordWithAllWrongData()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['old_password' => 'wrongPassword', 'new_password' => 'wrongNewPassword', 'confirm_password' => 'wrongConfirmPassword'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/changepassword', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('changepassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
   

    public function testLoggedInUser()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/me/m', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['data']['username'], $this->adminUser);
        $this->assertEquals($content['data']['name'], 'Admin Test');
    }

   public function testLoggedInUserCompleteDetails()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/me/a', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'], $this->adminUser);
    }

    public function testLoggedInUserComboDetails()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/me/a+ap', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['apps'][0]['name'], 'Admin');
    }

    public function testLoggedInUserComboWithProjects()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/me/a+pr', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['projects'][0]['name'], 'Test Project 1');
        $this->assertEquals($content['data']['projects'][1]['uuid'], 'ced672bb-fe33-4f0a-b153-f1d182a02603');
    }

    public function testUserAccess()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/me/access', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('getUserAppsAndPrivileges');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content['data']['privilege']);
        $this->assertNotEmpty($content['data']['whiteListedApps']);
        $this->assertEquals(true,count($content['data']['whiteListedApps']) > 0);
    }

    public function testGetUserProjectWithdata()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['data' => array([
            "id" => "1",
            "name"=> "Test Project 1",
            "org_id"=>"1",
            "description"=> "Description Test Data",
            "created_by"=> "1",
            "modified_by"=> "1",
            "date_created"=> "2018-11-11 07:25:06",
            "date_modified"=> "2018-12-11 07:25:06",
            "isdeleted"=> "0",
            "user_id"=> "1",
            "project_id"=>"1"
        ],[
            "id"=> "3",
            "name"=> "Test Project 2",
            "org_id"=>"1",
            "description"=> "Description Test Data",
            "created_by"=> "1",
            "modified_by"=> "1",
            "date_created"=> "2018-11-11 07:25:06",
            "date_modified"=> "2018-12-11 07:25:06",
            "isdeleted"=> "0",
            "user_id"=> "1",
            "project_id"=> "2"
        ])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/1/project', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('getuserproject');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
    }

    public function testGetUserProjectWithoutdata()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['data' => array([])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/5/project', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('getuserproject');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']),0);
    }

    public function testSaveMe()
    {
        $data = ['name' => 'John Holt','firstname' => 'John','lastname' => 'Holt'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/save', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('saveMe');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['firstname'], $data['firstname']);
        $this->assertEquals($content['data']['lastname'], $data['lastname']);
    }

    public function testBlackListApps()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/me/bapp', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['blackListedApps']), 0);
    }

    public function testBlackListAppsForEmployee(){
        $this->initAuthToken($this->employeeUser); 
        $this->dispatch('/user/me/bapp', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['blackListedApps']['Admin'],'f297dd6a-3eb4-4e06-83ad-fb289e5c0535');
    }

    public function testBlackListAppsForManager(){
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/user/me/bapp', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testGetExcludedUserList(){
        $this->initAuthToken($this->adminUser);
        $data = ['exclude' => array('4fd9f04d-758f-11e9-b2d5-68ecc57cde45','768d1fb9-de9c-46c3-8d5c-23e0e484ce2e'),'filter' => json_encode(array('0' => array('filter' => array('logic' => 'and','filters' => array(['field' => 'name','operator' => 'endswith','value' => 'al'],['field' => 'designation' ,'operator' => 'startswith','value' => 'it'])),'sort' => array(['field' => 'id','dir' => 'asc'],['field' => 'uuid','dir' => 'dsc']),'skip' => 0,'take' => 2)))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/users/list', 'POST',$data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('usersList');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'],'Manager Test');
    }


    public function testGetExcludedUserListWithExcludedUserFilter(){
        $this->initAuthToken($this->adminUser);
        $data = ['exclude' => array('4fd9f04d-758f-11e9-b2d5-68ecc57cde45','768d1fb9-de9c-46c3-8d5c-23e0e484ce2e','4fd9ce37-758f-11e9-b2d5-68ecc57cde45'),'filter' => json_encode(array('0' => array('filter' => array('logic' => 'and','filters' => array(['field' => 'name','operator' => 'endswith','value' => 'al'],['field' => 'designation' ,'operator' => 'startswith','value' => 'it'])),'sort' => array(['field' => 'id','dir' => 'asc'],['field' => 'uuid','dir' => 'dsc']),'skip' => 0,'take' => 2)))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/users/list', 'POST',$data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('usersList');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['total'],0);
    }
    
    public function testGetExcludedUserListWithOrgId(){
        $this->initAuthToken($this->adminUser);
        $data = ['exclude' => array('4fd9f04d-758f-11e9-b2d5-68ecc57cde45','768d1fb9-de9c-46c3-8d5c-23e0e484ce2e'),'filter' => json_encode(array('0' => array('sort' => array(['field' => 'id','dir' => 'asc'],['field' => 'uuid','dir' => 'dsc']),'skip' => 0,'take' => 20)))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/users/list', 'POST',$data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('usersList');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'],'Admin Test');
        $this->assertEquals($content['data'][1]['name'],'Manager Test');
        $this->assertEquals($content['data'][2]['name'],'Deepak S');
    }    

    public function testgetUserProfileDetail(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45/profile', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('getuserdetaillist');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'],'Admin Test');
        $this->assertEquals($content['data']['role'][0]['name'],'ADMIN');
        $this->assertEquals($content['data']['role'][1]['name'],'MANAGER');
    }


    public function testgetUserProfileDetailInvalidOrg(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/5301247-4949-afb1-e69b0891c98a/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45/profile', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('getuserdetaillist');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['data'],array());
        $this->assertEquals($content['data']['role'],array());
    }

    public function testgetUserProfileDetailInvalidUserId(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/user/4fd99e8e-758f-11e9-bcc57cde45/profile', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('getuserdetaillist');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['data'],array());
        $this->assertEquals($content['data']['role'],array());
    }

    public function testgetUserProfileDetailDifferentOrg(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45/profile', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('getuserdetaillist');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['data'],array());
        $this->assertEquals($content['data']['role'],array());
     }
}
