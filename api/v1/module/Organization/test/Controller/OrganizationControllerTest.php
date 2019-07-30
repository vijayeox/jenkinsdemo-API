<?php
namespace Organization;

use Oxzion\Db\ModelTable;
use Zend\Db\Adapter\AdapterInterface;
use Organization\Controller\OrganizationController;
use Oxzion\Test\ControllerTest;
use Oxzion\Service\OrganizationService;
use Mockery;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Utils\FileUtils;
use Oxzion\Transaction\TransactionManager;
use Oxzion\Service\AbstractService;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;




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

    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Organization.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
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
   
// Testing to see if the Create Contact function is working as intended if all the value passed are correct.

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(3, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['data'][0]['name'], 'Cleveland Black');
        $this->assertEquals($content['data'][1]['uuid'], 'b0971de7-0387-48ea-8f29-5d3704d96a46');
        $this->assertEquals($content['data'][1]['name'], 'Golden State Warriors');
        $this->assertEquals($content['data'][2]['uuid'], 'b6499a34-c100-4e41-bece-5822adca3844');
        $this->assertEquals($content['data'][2]['name'], 'Sample Organization');
        $this->assertEquals($content['total'],3);
    }

    public function testGetListWithQuery()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization?filter=[{"filter":{"logic":"and","filters":[{"field":"name","operator":"endswith","value":"rs"},{"field":"state","operator":"contains","value":"oh"}]},"sort":[{"field":"id","dir":"asc"},{"field":"uuid","dir":"dsc"}],"skip":0,"take":1}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], 'b0971de7-0387-48ea-8f29-5d3704d96a46');
        $this->assertEquals($content['data'][0]['name'], 'Golden State Warriors');
        $this->assertEquals($content['total'],1);
    }


    public function testGetListWithQueryField()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization?filter=[{"filter":{"logic":"and","filters":[{"field":"state","operator":"contains","value":"oh"}]},"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['data'][0]['name'], 'Cleveland Black');
        $this->assertEquals($content['total'],2);
    }

    public function testGetListWithQueryPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['data'][0]['name'], 'Cleveland Black');
        $this->assertEquals($content['total'],3);
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['uuid'], '53012471-2863-4949-afb1-e69b0891c98a');
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
        $tempFolder = $config['UPLOAD_FOLDER']."organization/".$this->testOrgId."/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__."/../files/logo.png", $tempFolder."logo.png");
        $contact = array('username' => 'goku','firstname'=>'Bharat','lastname'=>'Gogineni','email'=>'bharat@myvamla.com');
        $preferences = array('currency' => 'INR','timezone' => 'Asia/Calcutta','dateformat' => 'dd/mm/yyy');
        $data = array('name'=>'ORGANIZATION','address' => 'Bangalore','contact' => json_encode($contact),'preferences' => json_encode($preferences));
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
        
        $query = "SELECT * from ox_role where org_id = (SELECT id from ox_organization where uuid = '".$content['data']['uuid']."')";
        $roleResult = $this->executeQueryTest($query);
      
        for($x=0;$x<sizeof($roleResult);$x++){
            $query = "SELECT count(id) from ox_role_privilege where org_id = (SELECT id from ox_organization where role_id =".$roleResult[$x]['id']."
                AND uuid = '".$content['data']['uuid']."')";
            $rolePrivilegeResult[] = $this->executeQueryTest($query);
        }

        $select = "SELECT * FROM ox_user_role where role_id =".$roleResult[0]['id'];
        $roleResult = $this->executeQueryTest($select); 

        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='".$content['data']['uuid']."')";
        $orgResult = $this->executeQueryTest($select); 

        $select = "SELECT * FROM ox_user where username ='".$contact['username']."'";
        $usrResult = $this->executeQueryTest($select); 
       

        $this->assertEquals(count($roleResult), 1);
        $this->assertEquals(count($orgResult), 1);
        $this->assertEquals($usrResult[0]['firstname'],$contact['firstname']);
        $this->assertEquals($usrResult[0]['lastname'],$contact['lastname']);
        $this->assertEquals($usrResult[0]['designation'],'Admin');
        $this->assertEquals($rolePrivilegeResult[0][0]['count(id)'], 22);
        $this->assertEquals($rolePrivilegeResult[1][0]['count(id)'], 6);
        $this->assertEquals($rolePrivilegeResult[2][0]['count(id)'], 1);
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

    public function testsaveUser()
    {
        $this->initAuthToken($this->adminUser);
        $uuid = "53012471-2863-4949-afb1-e69b0891c98a";


        $data = ['userid' => array(['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];

        $this->dispatch('/organization/'.$uuid.'/save', 'POST',$data);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'Active', 'username' => 'rakshith')),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }

        

        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);  

        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='".$uuid."')";
        $orgResult = $this->executeQueryTest($select); 


        $select = "SELECT count(id) from ox_user where orgid is NULL";
        $orgCount = $this->executeQueryTest($select); 
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($orgResult),2);
        $this->assertEquals($orgResult[0]['user_id'],1);
        $this->assertEquals($orgResult[0]['org_id'],1);
        $this->assertEquals($orgResult[0]['default'],1);
        $this->assertEquals($orgResult[1]['user_id'],3);
        $this->assertEquals($orgResult[1]['org_id'],1);
        $this->assertEquals($orgResult[1]['default'],1);
        $this->assertEquals($orgCount[0]['count(id)'],2);

    }


    public function testsaveUserWithUserAlreadyExistsInOtherOrg()
    {
        $this->initAuthToken($this->adminUser);
        $uuid = "53012471-2863-4949-afb1-e69b0891c98a";

        $data = ['userid' => array(['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'],['uuid' => 'fbde2453-17eb-4d7f-909a-0fccc6d53e7a'])];

        $this->dispatch('/organization/'.$uuid.'/save', 'POST', $data);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'Active', 'username' => 'rakshith')),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }

        

        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);  

        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='".$uuid."')";
        $orgResult = $this->executeQueryTest($select); 

        $select = "SELECT count(id) from ox_user where orgid is NULL";
        $orgCount = $this->executeQueryTest($select); 
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($orgResult),3);
        $this->assertEquals($orgResult[0]['user_id'],1);
        $this->assertEquals($orgResult[0]['org_id'],1);
        $this->assertEquals($orgResult[0]['default'],1);
        $this->assertEquals($orgResult[1]['user_id'],3);
        $this->assertEquals($orgResult[1]['org_id'],1);
        $this->assertEquals($orgResult[1]['default'],1);
        $this->assertEquals($orgResult[2]['user_id'],5);
        $this->assertEquals($orgResult[2]['org_id'],1);
        $this->assertEquals($orgResult[2]['default'],NULL);
        $this->assertEquals($orgCount[0]['count(id)'],2);

    }


    public function testsaveUserWithUserToOtherOrg()
    {
        $this->initAuthToken($this->adminUser);
        $uuid = "53012471-2863-4949-afb1-e69b0891c98a";

        $data = ['userid' => array(['uuid' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'],['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'],['uuid' => '768d1fb9-de9c-46c3-8d5c-23e0e484ce2e'],['uuid' => 'fbde2453-17eb-4d7f-909a-0fccc6d53e7a'])];

        $this->dispatch('/organization/'.$uuid.'/save', 'POST', $data);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'Active', 'username' => 'rakshith')),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }

        

        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);  

        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='".$uuid."')";
        $orgResult = $this->executeQueryTest($select);

        $select = "SELECT count(id) from ox_user where orgid is NULL";
        $orgCount = $this->executeQueryTest($select);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($orgResult),4);
        $this->assertEquals($orgResult[0]['user_id'],1);
        $this->assertEquals($orgResult[0]['org_id'],1);
        $this->assertEquals($orgResult[0]['default'],1);
        $this->assertEquals($orgResult[1]['user_id'],2);
        $this->assertEquals($orgResult[1]['org_id'],1);
        $this->assertEquals($orgResult[1]['default'],1);
        $this->assertEquals($orgResult[2]['user_id'],4);
        $this->assertEquals($orgResult[2]['org_id'],1);
        $this->assertEquals($orgResult[2]['default'],1);
        $this->assertEquals($orgResult[3]['user_id'],5);
        $this->assertEquals($orgResult[3]['org_id'],1);
        $this->assertEquals($orgResult[3]['default'],NULL);
        $this->assertEquals($orgCount[0]['count(id)'],1);

    }

    public function testToDeleteContactUserFromOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userid' => array(['uuid' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'])];

        $uuid = "b6499a34-c100-4e41-bece-5822adca3844";
        $update = "update ox_organization set contactid = 6 where id = 3";
        $orgResult = $this->executeUpdate($update);
        $this->dispatch('/organization/'.$uuid.'/save', 'POST',$data);
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Sample Organization', 'status' => 'Active', 'username' => 'abc134')),'USERTOORGANIZATION_DELETED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Sample Organization', 'status' => 'Active', 'username' => 'bharatgtest')),'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);  

        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='".$uuid."')";
        $orgResult = $this->executeQueryTest($select);
        
        $select = "SELECT count(id) from ox_user where orgid is NULL";
        $orgCount = $this->executeQueryTest($select);
        
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($orgResult),2);
        $this->assertEquals($orgResult[0]['user_id'],6);
        $this->assertEquals($orgResult[0]['org_id'],3);
        $this->assertEquals($orgResult[0]['default'],1);
        $this->assertEquals($orgResult[1]['user_id'],1);
        $this->assertEquals($orgResult[1]['org_id'],3);
        $this->assertEquals($orgResult[1]['default'],NULL);
        $this->assertEquals($orgCount[0]['count(id)'],1);
    }

    

    public function testAddUserToOrganizationWithDifferentUser()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userid' => array(['uuid' => '4fd99e8e-758f-11e9-b2d5'],['uuid' => '4fd99e8e68ecc57cde4'])];

        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/save', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }



    public function testgetUsersofOrg()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(4, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], "4fd99e8e-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['data'][1]['uuid'], "4fd9ce37-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][1]['name'], 'Karan Agarwal');
        $this->assertEquals($content['total'],4);
    }

    public function testgetUsersofOrgWithFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/users?filter=[{"skip":1,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], "4fd9ce37-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][0]['name'], 'Karan Agarwal');
        $this->assertEquals($content['data'][1]['uuid'], "4fd9f04d-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][1]['name'], 'rakshith amin');
        $this->assertEquals($content['total'],4);
    }

    public function testgetUsersofOrgWithSortFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/users?filter=[{"sort":[{"field":"name","dir":"asc"}],"skip":2,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], "4fd9f04d-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][0]['name'], 'rakshith amin');
        $this->assertEquals($content['data'][1]['uuid'], "768d1fb9-de9c-46c3-8d5c-23e0e484ce2e");
        $this->assertEquals($content['data'][1]['name'], 'rohan kumar');
        $this->assertEquals($content['total'],4);
    }

    public function testgetUsersofOrgWithFieldFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/users?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"gogineni"}]},"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], "4fd99e8e-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['total'],1);
    }

    public function testgetAdminUsersOrgWithFilter(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/adminusers?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"gogineni"}]},"sort":[{"field":"name","dir":"asc"}],"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['total'],1); 
    }


    public function testgetAdminUsersOrg(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/adminusers?filter=[{"sort":[{"field":"name","dir":"asc"}],"skip":0,"take":20}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], 'fbde2453-17eb-4d7f-909a-0fccc6d53e7a');
        $this->assertEquals($content['data'][0]['name'], 'rakesh kumar');
        $this->assertEquals($content['total'],1); 
    }


    public function testgetAdminUsersOrgByManager(){
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/adminusers?filter=[{"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":20}]', 'GET');
        $this->assertResponseStatusCode(403);
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertMatchedRouteName('getListofAdminUsers');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testgetAdminUsersOrgByEmployee(){
        $this->initAuthToken($this->employeeUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/adminusers?filter=[{"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":20}]', 'GET');
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API'); 
    }

}