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

    private function executeQueryTest($query){
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet->toArray();
    }


    private function executeUpdate($query){
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();
        return $result;
    }
  

    public function testCreateByAdmin()
    {
        $this->initAuthToken($this->adminUser);
        $role = array('id' => '343db64a-a71d-11e9-b648-68ecc57cde45','id' => '343db567-a71d-11e9-b648-68ecc57cde45');
        $data = ['username' => 'John Holt', 'status' => 'Active', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'harshva.com', 'gender' => 'Male','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7'],['id' => '5ecccd2d-4dc7-4e19-ae5f-adb3c8f48073'])];
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USER_ADDED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USER_ADDED')->once()->andReturn();
        }
        $this->dispatch('/user', 'POST', $data);
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
        $this->assertNotEmpty($content['data']['id']);
        $this->assertEquals($content['data']['username'], $data['username']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($userOrg[0]['user_id'], $userId[0]['id']);
        $this->assertEquals($userOrg[0]['org_id'],1);
        $this->assertEquals($userRole[0]['user_id'], $userId[0]['id']);
        $this->assertEquals($userRole[0]['role_id'], 16);
        $this->assertEquals($userRole[1]['role_id'], 17);
    }


    public function testCreateWithRoleOfOtherOrg()
    {
        $this->initAuthToken($this->adminUser);
        $role = array('id' => '343db64a-a71d-11e9-b648-68ecc57cde45','id' => '343db567-a71d-11e9-b648-68ecc57cde45');
        $data = ['username' => 'John Holt', 'status' => 'Active', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'harshva.com', 'gender' => 'Male','role' => array(['id' => '508572ae-a6c2-11e9-b648-68ecc57cde45'],['id' => '50873a47-a6c2-11e9-b648-68ecc57cde45'])];
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

        $query = "SELECT id from ox_user where username = '" .$data['username']."'";
        $userId = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_org where user_id = ".$userId[0]['id'];
        $userOrg = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_role where user_id = ".$userId[0]['id'];
        $userRole = $this->executeQueryTest($query);

        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content['data']['id']);
        $this->assertEquals($content['data']['username'], $data['username']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($userOrg[0]['user_id'], $userId[0]['id']);
        $this->assertEquals($userOrg[0]['org_id'],1);
        $this->assertEquals(count($userRole),0);
    }

    public function testCreateByEmployee()
    {
        $this->initAuthToken($this->employeeUser);
        $role = array('id' => '343db64a-a71d-11e9-b648-68ecc57cde45','id' => '343db567-a71d-11e9-b648-68ecc57cde45');
        $data = ['username' => 'John Holt', 'status' => 'Active', 'date_of_birth' => date('Y-m-d H:i:s', strtotime("-50 year")), 'date_of_join' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt','designation' => 'CEO','location' => 'USA', 'email' => 'harshva.com', 'gender' => 'Male','role' => array(['id' => '50873baa-a6c2-11e9-b648-68ecc57cde45'],['id' => '50873bf0-a6c2-11e9-b648-68ecc57cde45'])];
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

    

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 5);
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['data'][1]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][1]['name'], 'Karan Agarwal');
        $this->assertEquals($content['data'][2]['uuid'], 'fbde2453-17eb-4d7f-909a-0fccc6d53e7a');
        $this->assertEquals($content['data'][2]['name'], 'rakesh kumar');
        $this->assertEquals($content['total'],5);
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
        $this->assertEquals($content['data'][0]['name'], 'rohan kumar');
        $this->assertEquals($content['data'][1]['uuid'], '4fd9f04d-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][1]['name'], 'rakshith amin');
        $this->assertEquals($content['total'],5);
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
        $this->assertEquals($content['data'][0]['uuid'], 'fbde2453-17eb-4d7f-909a-0fccc6d53e7a');
        $this->assertEquals($content['data'][0]['name'], 'rakesh kumar');
        $this->assertEquals($content['total'],5);
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
        $this->assertEquals($content['data'][0]['name'], 'Karan Agarwal');
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
        // print_r($content);
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Bharat Gogineni');
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
        $this->assertEquals($content['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['total'],5);
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
        $this->assertEquals($content['data'][0]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Karan Agarwal');
        $this->assertEquals($content['data'][1]['uuid'], 'fbde2453-17eb-4d7f-909a-0fccc6d53e7a');
        $this->assertEquals($content['data'][1]['name'], 'rakesh kumar');
        $this->assertEquals($content['total'], 5);
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
        $this->assertEquals($content['data']['name'], 'Bharat Gogineni');
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
        $data = ['name' => 'John Holt'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateWithRole()
    {
        $data = ['name' => 'John Holt','role' => array(['id' => '89a01b30-9cc9-416e-8027-1fd2083786c7'],['id' => '5ecccd2d-4dc7-4e19-ae5f-adb3c8f48073'])];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/4fd99e8e-758f-11e9-b2d5-68ecc57cde45', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $query = "SELECT id from ox_user where name = '" .$data['name']."'";
        $userId = $this->executeQueryTest($query);

        $query = "SELECT * from ox_user_role where user_id = ".$userId[0]['id'];
        $userRole = $this->executeQueryTest($query);


        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($userRole[0]['user_id'], $userId[0]['id']);
        $this->assertEquals($userRole[0]['role_id'], 16);
        $this->assertEquals($userRole[1]['role_id'], 17);
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Test User'];
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
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('changepassword');
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
        $this->assertEquals($content['data']['username'], $this->adminUser);
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

    public function testUserAccess()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/user/me/access', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('getUserAppsAndPrivileges');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        // TO DO : Whitelisted apps for manageruser and employeeuser
        $this->assertNotEmpty($content['data']['privilege']);
        $this->assertNotEmpty($content['data']['whiteListedApps']);
        $this->assertEquals(6,count($content['data']['whiteListedApps']));
    }

     public function testForgotPassword()
    {
        $this->initAuthToken($this->managerUser);
        $data = ['email' => 'test1@va.com'];
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'mail')->once()->andReturn();
        }
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
        $this->initAuthToken($this->employeeUser);
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

        $this->initAuthToken($this->employeeUser);
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

        $this->initAuthToken($this->employeeUser);
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
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']),2);
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

    public function testSaveMe(){
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

    public function testBlackListApps(){
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
        $update = "update ox_app set uuid = 'c980e23a-ade8-4bd9-a06c-a39ca7854b9d' where name = 'AppBuilder'";
        $result = $this->executeUpdate($update);

        $update = "update ox_app set uuid = '636cb8e2-14a9-4c09-a668-14f6518b8d0d' where name = 'CRM'";
        $result = $this->executeUpdate($update);
      
      
        $this->dispatch('/user/me/bapp', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['blackListedApps']['Admin'],'f297dd6a-3eb4-4e06-83ad-fb289e5c0535');
        $this->assertEquals($content['data']['blackListedApps']['AppBuilder'],'c980e23a-ade8-4bd9-a06c-a39ca7854b9d');
        $this->assertEquals($content['data']['blackListedApps']['CRM'],'636cb8e2-14a9-4c09-a668-14f6518b8d0d');
    }

    public function testBlackListAppsForManager(){
        $this->initAuthToken($this->managerUser);
        $update = "update ox_app set uuid = 'c980e23a-ade8-4bd9-a06c-a39ca7854b9d' where name = 'AppBuilder'";
        $result = $this->executeUpdate($update);

        $this->dispatch('/user/me/bapp', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('loggedInUser');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['blackListedApps']['Admin'],'f297dd6a-3eb4-4e06-83ad-fb289e5c0535');
        $this->assertEquals($content['data']['blackListedApps']['AppBuilder'],'c980e23a-ade8-4bd9-a06c-a39ca7854b9d');
    }
}
