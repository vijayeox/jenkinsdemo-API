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


class UserControllerTest extends ControllerTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getMockMessageProducer(){
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

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'John Holt', 'status' => 'Active', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt', 'password' => 'welcome2oxzion', 'designation' => 'CEO','location' => 'USA', 'email' => 'harshva.com', 'gender' => 'Male'];
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_user'));
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => 'John Holt', 'firstname' => 'John', 'email' => 'harshva.com')),'USER_ADDED')->once()->andReturn();
        }
        $this->dispatch('/user', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content['data']['id']);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals(4, $this->getConnection()->getRowCount('ox_user'));
    }

    public function testCreateWithOutPasswordFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'John Holt', 'status' => 'Active', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt', 'designation' => 'CEO','location' => 'USA', 'email' => 'harshva.com', 'gender' => 'Male'];
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/user', 'POST', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['password'], 'required');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['data']['data'][1]['id'], 2);
        $this->assertEquals($content['data']['data'][1]['name'], 'Karan Agarwal');
        $this->assertEquals($content['data']['data'][2]['id'], 3);
        $this->assertEquals($content['data']['data'][2]['name'], 'rakshith amin');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 1);
        $this->assertEquals($content['data']['pagination']['pageSize'], 20);
    }

    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?sort=lastname', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data']['data'][0]['id'], 2);
        $this->assertEquals($content['data']['data'][0]['name'], 'Karan Agarwal');
        $this->assertEquals($content['data']['data'][1]['id'], 3);
        $this->assertEquals($content['data']['data'][1]['name'], 'rakshith amin');
        $this->assertEquals($content['data']['data'][2]['id'], 1);
        $this->assertEquals($content['data']['data'][2]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 1);
        $this->assertEquals($content['data']['pagination']['pageSize'], 20);
    }

    public function testGetListwithQueryParameters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?f=email&q=test&pg=1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data']['data'][0]['id'], 2);
        $this->assertEquals($content['data']['data'][0]['name'], 'Karan Agarwal');
        $this->assertEquals($content['data']['data'][1]['id'], 3);
        $this->assertEquals($content['data']['data'][1]['name'], 'rakshith amin');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 1);
        $this->assertEquals($content['data']['pagination']['pageSize'], 20);
    }

    public function testGetListwithQueryWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?f=email&q=test&psz=1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data']['data'][0]['id'], 2);
        $this->assertEquals($content['data']['data'][0]['name'], 'Karan Agarwal');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 2);
        $this->assertEquals($content['data']['pagination']['pageSize'], 1);
    }

    public function testGetListwithQueryPage()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?psz=2&pg=2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
         $this->assertEquals($content['data']['data'][0]['id'], 3);
        $this->assertEquals($content['data']['data'][0]['name'], 'rakshith amin');
        $this->assertEquals($content['data']['pagination']['page'], 2);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 2);
        $this->assertEquals($content['data']['pagination']['pageSize'], 2);
    }

    public function testGetListwithQueryPageNo()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user?psz=2&pg=1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['data']['data'][1]['id'], 2);
        $this->assertEquals($content['data']['data'][1]['name'], 'Karan Agarwal');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 2);
        $this->assertEquals($content['data']['pagination']['pageSize'], 2);
    }


    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'], 'bharatg');
        $this->assertEquals($content['data']['name'], 'Bharat Gogineni');
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/64', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUpdate()
    {
        $data = ['name' => 'John Holt'];
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

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Test User'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/122', 'PUT', null);
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
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('username' => 'rakshith', 'orgname' => 'Cleveland Black')),'USER_DELETED')->once()->andReturn();
        }
        $this->dispatch('/user/3', 'DELETE');
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

    public function testAddUserToGroup()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/3/addusertogroup/2', 'POST');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addusertogroup');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddUserToGroupWithExistingGroup()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/addusertogroup/1', 'POST');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('addusertogroup');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
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
   public function testAddOrganizationToUser()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/3/organization/2', 'POST');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addOrganizationToUser');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAddOrganizationToUserWithSameData()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/2/organization/1', 'POST');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('addOrganizationToUser');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }

    public function testAddOrganizationToUserWithDifferentOrganization()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/1/organization/2', 'POST');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addOrganizationToUser');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testLoggedInUser()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/me/m', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['data']['username'], 'bharatg');
        $this->assertEquals($content['data']['name'], 'Bharat Gogineni');
    }

   public function testLoggedInUserCompleteDetails()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/me/a', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'], 'bharatg');
        $this->assertEquals($content['data']['status'], 'Active');
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
        $this->assertEquals($content['data']['status'], 'Active');
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
    } 

     public function testForgotPassword()
    {
        $this->initAuthToken($this->managerUser);
        $data = ['email' => 'test@va.com'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/forgotpassword', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('forgotpassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->resetCode = $content['data']['password_reset_code'];
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['email'], $data['email']);
    }


    public function testForgotPasswordWrongEmail()
    {
        $this->initAuthToken($this->managerUser);
        $data = ['email' => 'wrongemail@va.com'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/forgotpassword', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('forgotpassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'The email entered does not match your profile email');
    }
    public function testUpdateNewPassword()
    {
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $query="UPDATE ox_user SET password_reset_code = 'pvAQyJkY',password_reset_expiry_date ='".date('Y-m-d H:i:s', strtotime('+1 day', time()))."' WHERE id = 3";
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();
        $this->initAuthToken($this->managerUser);
        $data = ['password_reset_code' => "pvAQyJkY", 'new_password' => 'password', 'confirm_password' => 'password'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/updatenewpassword', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('updatenewpassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['new_password'], $data['new_password']);
    }

    public function testUpdateNewPasswordWithWrongPassword()
    {
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $query="UPDATE ox_user SET password_reset_code = 'pvAQyJkY',password_reset_expiry_date ='".date('Y-m-d H:i:s', strtotime('+1 day', time()))."' WHERE id = 3";
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();
        
        $this->initAuthToken($this->managerUser);
        $data = ['password_reset_code' => "pvAQyJkY", 'new_password' => 'password', 'confirm_password' => 'wrongpassword'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/updatenewpassword', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('updatenewpassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], "Failed to Update Password");
    }

     public function testUpdateNewPasswordWithWrongCode()
    {
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $query="UPDATE ox_user SET password_reset_code = 'pvAQyJkY',password_reset_expiry_date ='".date('Y-m-d H:i:s', strtotime('+1 day', time()))."' WHERE id = 3";
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();
        
        $this->initAuthToken($this->managerUser);
        $data = ['password_reset_code' => "wrongCode", 'new_password' => 'password', 'confirm_password' => 'password'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/updatenewpassword', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->setDefaultAsserts('updatenewpassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], "You have entered an incorrect code");
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
        $diff=array_diff($data, $content);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($diff, array());
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
        $diff=array_diff($data, $content);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($diff, array());
    }

}
