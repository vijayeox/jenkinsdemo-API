<?php
namespace Organization;

use Mockery;
use Organization\Controller\OrganizationController;
use Oxzion\Service\OrganizationService;
use Oxzion\Test\ControllerTest;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class OrganizationControllerTest extends ControllerTest
{
    protected $topic;

    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getMockMessageProducer()
    {
        $organizationService = $this->getApplicationServiceLocator()->get(OrganizationService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $organizationService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Organization.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../../../Group/test/Dataset/Group.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../../../Project/test/Dataset/Project.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../../../Announcement/test/Dataset/Announcement.yml");
        return $dataset;
    }
    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    // Testing to see if the Create Contact function is working as intended if all the value passed are correct.

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(3, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['data'][0]['name'], 'Cleveland Black');
        $this->assertEquals($content['data'][1]['uuid'], 'b0971de7-0387-48ea-8f29-5d3704d96a46');
        $this->assertEquals($content['data'][1]['name'], 'Golden State Warriors');
        $this->assertEquals($content['data'][2]['uuid'], 'b6499a34-c100-4e41-bece-5822adca3844');
        $this->assertEquals($content['data'][2]['name'], 'Sample Organization');
        $this->assertEquals($content['total'], 3);
    }

    public function testGetListWithQuery()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization?filter=[{"filter":{"logic":"and","filters":[{"field":"name","operator":"endswith","value":"rs"},{"field":"state","operator":"contains","value":"oh"}]},"sort":[{"field":"id","dir":"asc"},{"field":"uuid","dir":"dsc"}],"skip":0,"take":1}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], 'b0971de7-0387-48ea-8f29-5d3704d96a46');
        $this->assertEquals($content['data'][0]['name'], 'Golden State Warriors');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListWithQueryField()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization?filter=[{"filter":{"logic":"and","filters":[{"field":"state","operator":"contains","value":"oh"}]},"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['data'][0]['name'], 'Cleveland Black');
        $this->assertEquals($content['total'], 2);
    }

    public function testGetListWithQueryPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['data'][0]['name'], 'Cleveland Black');
        $this->assertEquals($content['total'], 3);
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
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/logo.png", $tempFolder . "logo.png");
        $contact = array('username' => 'goku', 'firstname' => 'Bharat', 'lastname' => 'Gogineni', 'email' => 'barat@myvamla.com', 'phone' => '1234567890');
        $preferences = array('currency' => 'INR', 'timezone' => 'Asia/Calcutta', 'dateformat' => 'dd/mm/yyy');
        $data = array('name' => 'ORGANIZATION', 'address1' => 'Banshankari', 'city' => 'Bangalore', 'state' => 'Karnataka', 'country' => 'India', 'zip' => '23456', 'contact' => json_encode($contact), 'preferences' => json_encode($preferences));
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'ORGANIZATION', 'status' => 'Active')), 'ORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->dispatch('/organization', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('organization');
        $query = "SELECT * from ox_role where org_id = (SELECT id from ox_organization where uuid = '" . $content['data']['uuid'] . "')";
        $role = $this->executeQueryTest($query);
        for ($x = 0; $x < sizeof($role); $x++) {
            $query = "SELECT count(id) from ox_role_privilege where org_id = (SELECT id from ox_organization where role_id =" . $role[$x]['id'] . "
                AND uuid = '" . $content['data']['uuid'] . "')";
            $rolePrivilegeResult[] = $this->executeQueryTest($query);
        }
        $select = "SELECT * FROM ox_user_role where role_id =" . $role[0]['id'];
        $roleResult = $this->executeQueryTest($select);
        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='" . $content['data']['uuid'] . "')";
        $orgResult = $this->executeQueryTest($select);
        $select = "SELECT ox_user.*,ox_user_profile.firstname,ox_user_profile.lastname,ox_user_profile.address_id,ox_employee.designation FROM ox_user inner join ox_user_profile on ox_user_profile.id = ox_user.user_profile_id inner join ox_employee on ox_employee.user_profile_id = ox_user_profile.id where ox_user.username ='" . $contact['username'] . "'";
        $usrResult = $this->executeQueryTest($select);
        $select = "SELECT ox_address.address1,ox_organization_profile.uuid,ox_organization.uuid,ox_organization_profile.name from ox_address join ox_organization_profile on ox_address.id = ox_organization_profile.address_id join ox_organization on ox_organization.org_profile_id=ox_organization_profile.id where name = 'ORGANIZATION'";
        $org = $this->executeQueryTest($select);
        $query = "SELECT * from ox_app_registry where org_id = (SELECT id from ox_organization where uuid = '" . $content['data']['uuid'] . "')";
        $appResult = $this->executeQueryTest($query);
        $this->assertEquals(count($role), 3);
        $this->assertEquals(count($roleResult), 1);
        $this->assertEquals(count($orgResult), 1);
        $this->assertEquals($usrResult[0]['firstname'], $contact['firstname']);
        $this->assertEquals($usrResult[0]['lastname'], $contact['lastname']);
        $this->assertEquals($usrResult[0]['designation'], 'Admin');
        $this->assertEquals($rolePrivilegeResult[0][0]['count(id)'], 34);
        $this->assertEquals($rolePrivilegeResult[1][0]['count(id)'], 10);
        $this->assertEquals($rolePrivilegeResult[2][0]['count(id)'], 8);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals(isset($usrResult[0]['address_id']), true);
        $this->assertEquals($org[0]['address1'], $data['address1']);
        $this->assertEquals($appResult[0]['app_id'], 1);
    }

    public function testCreateWithoutOrgaddress()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/logo.png", $tempFolder . "logo.png");
        $contact = array('username' => 'goku', 'firstname' => 'Bharat', 'lastname' => 'Gogineni', 'email' => 'bharatg@myvamla.com', 'phone' => '1234567890');
        $preferences = array('currency' => 'INR', 'timezone' => 'Asia/Calcutta', 'dateformat' => 'dd/mm/yyy');
        $data = array('name' => 'ORGANIZATION', 'contact' => json_encode($contact), 'preferences' => json_encode($preferences));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('organization');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
    }

    public function testCreateWithExistingContactpersonEmail()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/logo.png", $tempFolder . "logo.png");
        $contact = array('username' => 'goku', 'firstname' => 'Bharat', 'lastname' => 'Gogineni', 'email' => 'bharatg@myvamla.com', 'phone' => '1234567890');
        $preferences = array('currency' => 'INR', 'timezone' => 'Asia/Calcutta', 'dateformat' => 'dd/mm/yyy');
        $data = array('name' => 'ORGANIZATION', 'address1' => 'Banshankari', 'city' => 'Bangalore', 'state' => 'Karnataka', 'country' => 'India', 'zip' => '23456', 'contact' => json_encode($contact), 'preferences' => json_encode($preferences));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization', 'POST', $data);
        $this->assertResponseStatusCode(500);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('organization');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Username or Email Exists in other Organization');
    }

    public function testCreateWithExistingContactpersonUsername()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/logo.png", $tempFolder . "logo.png");
        $contact = array('username' => 'bharatgtest', 'firstname' => 'Bharat', 'lastname' => 'Gogineni', 'email' => 'goku@myvamla.com', 'phone' => '1234567890');
        $preferences = array('currency' => 'INR', 'timezone' => 'Asia/Calcutta', 'dateformat' => 'dd/mm/yyy');
        $data = array('name' => 'ORGANIZATION', 'address1' => 'Banshankari', 'city' => 'Bangalore', 'state' => 'Karnataka', 'country' => 'India', 'zip' => '23456', 'contact' => json_encode($contact), 'preferences' => json_encode($preferences));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization', 'POST', $data);
        $this->assertResponseStatusCode(500);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('organization');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Username or Email Exists in other Organization');
    }

    public function testCreateWithExistingActiveOrg()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/logo.png", $tempFolder . "logo.png");
        $contact = array('username' => 'goku', 'firstname' => 'Bharat', 'lastname' => 'Gogineni', 'email' => 'bharatg@myvamla.com', 'phone' => '1234567890');
        $preferences = array('currency' => 'INR', 'timezone' => 'Asia/Calcutta', 'dateformat' => 'dd/mm/yyy');
        $data = array('name' => 'Sample Organization', 'address1' => 'HSR','city' => 'Bangalore','state' => 'KARNATAKA','zip' => '560080','contact' => json_encode($contact), 'preferences' => json_encode($preferences));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(500);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('organization');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Organization already exists');
    }

    public function testCreateWithExistingInactiveOrgWithoutReactivateFlag()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/logo.png", $tempFolder . "logo.png");
        $contact = array('username' => 'goku', 'firstname' => 'Bharat', 'lastname' => 'Gogineni', 'email' => 'bharatg@myvamla.com', 'phone' => '1234567890');
        $preferences = array('currency' => 'INR', 'timezone' => 'Asia/Calcutta', 'dateformat' => 'dd/mm/yyy');
        $data = array('name' => 'Test Organization', 'address1' => 'Ariyalur', 'city' => 'Chennai','state' => 'Tamil Nadu', 'zip' =>'560079','contact' => json_encode($contact), 'preferences' => json_encode($preferences));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(500);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('organization');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Organization already exists would you like to reactivate?');
    }

    public function testCreateWithExistingInactiveOrgWithReactivateFlag()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/logo.png", $tempFolder . "logo.png");
        $contact = array('username' => 'goku', 'firstname' => 'Bharat', 'lastname' => 'Gogineni', 'email' => 'bharatg@myvamla.com', 'phone' => '1234567890');
        $preferences = array('currency' => 'INR', 'timezone' => 'Asia/Calcutta', 'dateformat' => 'dd/mm/yyy');
        $data = array('name' => 'Test Organization', 'address1' => 'Ariyalur', 'city' => 'Chennai','state' => 'Tamil Nadu', 'zip' =>'560079', 'contact' => json_encode($contact), 'preferences' => json_encode($preferences), 'reactivate' => 1);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('organization');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['status'], 'Active');
    }

    public function testCreateWithoutContactPerson()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/logo.png", $tempFolder . "logo.png");
        $preferences = array('currency' => 'INR', 'timezone' => 'Asia/Calcutta', 'dateformat' => 'dd/mm/yyy');
        $data = array('name' => 'Test Organization', 'address1' => 'Ariyalur', 'city' => 'Chennai','state' => 'Tamil Nadu', 'zip' =>'560079', 'preferences' => json_encode($preferences), 'reactivate' => 1);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization', 'POST', $data);
        $this->assertResponseStatusCode(500);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('organization');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Contact Person details are required');
    }

    public function testCreateWithOutNameFailure()
    {
        $this->initAuthToken($this->adminUser);
        $contact = array('username' => 'goku', 'firstname' => 'Bharat', 'lastname' => 'Gogineni', 'email' => 'bharat@myvamla.com', 'phone' => '1234567890');
        $preferences = array('currency' => 'INR', 'timezone' => 'Asia/Calcutta', 'dateformat' => 'dd/mm/yyy');
        $data = ['address1' => 'Banshankari', 'city' => 'Bangalore', 'state' => 'Karnataka', 'country' => 'India', 'zip' => '23456', 'logo' => 'logo.png', 'status' => 'Active', 'contact' => json_encode($contact), 'preferences' => json_encode($preferences)];
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/organization', 'POST', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
    }

    public function testCreateAccess()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Cleveland Cavaliers', 'logo' => 'logo.png', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/organization', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertMatchedRouteName('organization');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdate()
    {
        $data = ['name' => 'Cleveland Cavaliers', 'address1' => '23811 Chagrin Blvd, Ste 244', 'city' => 'Beachwood', 'state' => 'OH', 'country' => 'US', 'zip' => '44122', 'logo' => 'logo.png', 'status' => 'InActive'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('new_orgname' => 'Cleveland Cavaliers', 'old_orgname' => 'Cleveland Black', 'status' => 'InActive')), 'ORGANIZATION_UPDATED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'InActive')), 'ORGANIZATION_DELETED')->once()->andReturn();
        }
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a', 'POST', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $select = "SELECT * from ox_address join ox_organization_profile on ox_address.id = ox_organization_profile.address_id where name = 'Cleveland Cavaliers'";
        $org = $this->executeQueryTest($select);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($org[0]['address1'], '23811 Chagrin Blvd, Ste 244');
    }

    public function testUpdateWithAddress()
    {
        $data = ['name' => 'Cleveland Cavaliers', 'logo' => 'logo.png', 'status' => 'InActive', 'address1' => 'Banshankari', 'city' => 'Bangalore', 'state' => 'Karnataka', 'country' => 'India', 'zip' => '23456'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('new_orgname' => 'Cleveland Cavaliers', 'old_orgname' => 'Cleveland Black', 'status' => 'InActive')), 'ORGANIZATION_UPDATED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'InActive')), 'ORGANIZATION_DELETED')->once()->andReturn();
        }
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a', 'POST', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        // print_r($content);exit;
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $select = "SELECT * from ox_address join ox_organization_profile on ox_address.id = ox_organization_profile.address_id where name = 'Cleveland Cavaliers'";
        $org = $this->executeQueryTest($select);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($org[0]['address1'], $data['address1']);
    }

    public function testUpdateRestricted()
    {
        $data = ['name' => 'Cleveland Cavaliers', 'logo' => 'logo.png', 'status' => 'Active'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/organization/1', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertMatchedRouteName('organization');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a', 'DELETE');
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'InActive')), 'ORGANIZATION_DELETED')->once()->andReturn();
        }
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        if (enableActiveMQ == 0) {
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
        $this->dispatch('/organization/' . $uuid . '/save', 'POST', $data);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'Active', 'username' => 'rakshith')), 'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='" . $uuid . "')";
        $orgResult = $this->executeQueryTest($select);
        $select = "SELECT count(id) from ox_user where orgid is NULL";
        $orgCount = $this->executeQueryTest($select);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($orgResult), 2);
        $this->assertEquals($orgResult[0]['user_id'], 1);
        $this->assertEquals($orgResult[0]['org_id'], 1);
        $this->assertEquals($orgResult[0]['default'], 1);
        $this->assertEquals($orgResult[1]['user_id'], 3);
        $this->assertEquals($orgResult[1]['org_id'], 1);
        $this->assertEquals($orgResult[1]['default'], 1);
        $this->assertEquals($orgCount[0]['count(id)'], 2);
    }

    public function testsaveUserWithUserAlreadyExistsInOtherOrg()
    {
        $this->initAuthToken($this->adminUser);
        $uuid = "53012471-2863-4949-afb1-e69b0891c98a";
        $data = ['userid' => array(['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => 'fbde2453-17eb-4d7f-909a-0fccc6d53e7a'])];
        $this->dispatch('/organization/' . $uuid . '/save', 'POST', $data);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'Active', 'username' => 'rakshith')), 'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='" . $uuid . "')";
        $orgResult = $this->executeQueryTest($select);
        $select = "SELECT count(id) from ox_user where orgid is NULL";
        $orgCount = $this->executeQueryTest($select);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($orgResult), 3);
        $this->assertEquals($orgResult[0]['user_id'], 1);
        $this->assertEquals($orgResult[0]['org_id'], 1);
        $this->assertEquals($orgResult[0]['default'], 1);
        $this->assertEquals($orgResult[1]['user_id'], 3);
        $this->assertEquals($orgResult[1]['org_id'], 1);
        $this->assertEquals($orgResult[1]['default'], 1);
        $this->assertEquals($orgResult[2]['user_id'], 5);
        $this->assertEquals($orgResult[2]['org_id'], 1);
        $this->assertEquals($orgResult[2]['default'], null);
        $this->assertEquals($orgCount[0]['count(id)'], 2);
    }

    public function testsaveUserWithUserToOtherOrg()
    {
        $this->initAuthToken($this->adminUser);
        $uuid = "53012471-2863-4949-afb1-e69b0891c98a";
        $data = ['userid' => array(['uuid' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '768d1fb9-de9c-46c3-8d5c-23e0e484ce2e'], ['uuid' => 'fbde2453-17eb-4d7f-909a-0fccc6d53e7a'])];
        $this->dispatch('/organization/' . $uuid . '/save', 'POST', $data);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Cleveland Black', 'status' => 'Active', 'username' => 'rakshith')), 'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='" . $uuid . "')";
        $orgResult = $this->executeQueryTest($select);
        $select = "SELECT count(id) from ox_user where orgid is NULL";
        $orgCount = $this->executeQueryTest($select);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($orgResult), 4);
        $this->assertEquals($orgResult[0]['user_id'], 1);
        $this->assertEquals($orgResult[0]['org_id'], 1);
        $this->assertEquals($orgResult[0]['default'], 1);
        $this->assertEquals($orgResult[1]['user_id'], 2);
        $this->assertEquals($orgResult[1]['org_id'], 1);
        $this->assertEquals($orgResult[1]['default'], 1);
        $this->assertEquals($orgResult[2]['user_id'], 4);
        $this->assertEquals($orgResult[2]['org_id'], 1);
        $this->assertEquals($orgResult[2]['default'], 1);
        $this->assertEquals($orgResult[3]['user_id'], 5);
        $this->assertEquals($orgResult[3]['org_id'], 1);
        $this->assertEquals($orgResult[3]['default'], null);
        $this->assertEquals($orgCount[0]['count(id)'], 1);
    }

    public function testToDeleteContactUserFromOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userid' => array(['uuid' => '4fd99e8e-758f-11e9-b2d5-68ecc57cde45'])];
        $uuid = "b6499a34-c100-4e41-bece-5822adca3844";
        $update = "update ox_organization set contactid = 6 where id = 3";
        $orgResult = $this->executeUpdate($update);
        $this->dispatch('/organization/' . $uuid . '/save', 'POST', $data);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Sample Organization', 'status' => 'Active', 'username' => 'abc134')), 'USERTOORGANIZATION_DELETED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('orgname' => 'Sample Organization', 'status' => 'Active', 'username' => 'bharatgtest')), 'USERTOORGANIZATION_ADDED')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('addUserToOrganization');
        $content = json_decode($this->getResponse()->getContent(), true);
        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='" . $uuid . "')";
        $orgResult = $this->executeQueryTest($select);
        $select = "SELECT count(id) from ox_user where orgid is NULL";
        $orgCount = $this->executeQueryTest($select);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($orgResult), 2);
        $this->assertEquals($orgResult[0]['user_id'], 6);
        $this->assertEquals($orgResult[0]['org_id'], 3);
        $this->assertEquals($orgResult[0]['default'], 1);
        $this->assertEquals($orgResult[1]['user_id'], 1);
        $this->assertEquals($orgResult[1]['org_id'], 3);
        $this->assertEquals($orgResult[1]['default'], null);
        $this->assertEquals($orgCount[0]['count(id)'], 1);
    }

    public function testAddUserToOrganizationWithDifferentUser()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userid' => array(['uuid' => '4fd99e8e-758f-11e9-b2d5'], ['uuid' => '4fd99e8e68ecc57cde4'])];
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
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(4, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], "4fd99e8e-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['data'][0]['is_admin'], 1);
        $this->assertEquals($content['data'][1]['uuid'], "4fd9ce37-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][1]['name'], 'Karan Agarwal');
        $this->assertEquals($content['total'], 4);
    }

    public function testgetUsersofOrgWithFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/users?filter=[{"skip":1,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], "4fd9ce37-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][0]['name'], 'Karan Agarwal');
        $this->assertEquals($content['data'][1]['uuid'], "4fd9f04d-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][1]['name'], 'rakshith amin');
        $this->assertEquals($content['total'], 4);
    }

    public function testgetUsersofOrgWithSortFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/users?filter=[{"sort":[{"field":"name","dir":"asc"}],"skip":2,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], "4fd9f04d-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][0]['name'], 'rakshith amin');
        $this->assertEquals($content['data'][1]['uuid'], "768d1fb9-de9c-46c3-8d5c-23e0e484ce2e");
        $this->assertEquals($content['data'][1]['name'], 'rohan kumar');
        $this->assertEquals($content['total'], 4);
    }

    public function testgetUsersofOrgWithFieldFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/users?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"gogineni"}]},"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], "4fd99e8e-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['total'], 1);
    }

    public function testgetUsersofOrgWithFieldCountryFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/users?filter=[{"filter":{"filters":[{"field":"country","operator":"endswith","value":"ana"}]},"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], "4fd9ce37-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][0]['name'], 'Karan Agarwal');
        $this->assertEquals($content['total'], 2);
    }

    public function testgetAdminUsersOrgWithFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/adminusers?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"gogineni"}]},"sort":[{"field":"name","dir":"asc"}],"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Bharat Gogineni');
        $this->assertEquals($content['total'], 1);
    }

    public function testgetAdminUsersOrg()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/adminusers?filter=[{"sort":[{"field":"name","dir":"asc"}],"skip":0,"take":20}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['uuid'], 'fbde2453-17eb-4d7f-909a-0fccc6d53e7a');
        $this->assertEquals($content['data'][0]['name'], 'rakesh kumar');
        $this->assertEquals($content['total'], 1);
    }

    public function testgetAdminUsersOrgByManager()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/adminusers?filter=[{"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":20}]', 'GET');
        $this->assertResponseStatusCode(403);
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertMatchedRouteName('getListofAdminUsers');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testgetAdminUsersOrgByEmployee()
    {
        $this->initAuthToken($this->employeeUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/adminusers?filter=[{"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":20}]', 'GET');
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testgetOrgGroups()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/groups', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de');
        $this->assertEquals($content['data'][0]['name'], 'Test Group');
        $this->assertEquals($content['data'][0]['description'], 'Description Test Data');
        $this->assertEquals($content['data'][0]['manager_id'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['parent_id'], null);
        $this->assertEquals($content['data'][0]['org_id'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['data'][1]['uuid'], '153f3e9e-eb07-4ca4-be78-34f715bd124');
        $this->assertEquals($content['data'][1]['name'], 'Test Group 5');
        $this->assertEquals($content['data'][1]['description'], 'Group Description');
        $this->assertEquals($content['data'][1]['manager_id'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgGroupsWithFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/groups?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"oup"},{"field":"description","operator":"startswith","value":"dEs"}]},"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de');
        $this->assertEquals($content['data'][0]['name'], 'Test Group');
        $this->assertEquals($content['data'][0]['description'], 'Description Test Data');
        $this->assertEquals($content['data'][0]['manager_id'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['parent_id'], null);
        $this->assertEquals($content['data'][0]['org_id'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['total'], 1);
    }

    public function testgetOrgGroupsWithSortFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/groups?filter=[{"sort":[{"field":"uuid","dir":"asc"}],"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '153f3e9e-eb07-4ca4-be78-34f715bd124');
        $this->assertEquals($content['data'][0]['name'], 'Test Group 5');
        $this->assertEquals($content['data'][1]['uuid'], '153f3e9e-eb07-4ca4-be78-34f715bd50db');
        $this->assertEquals($content['data'][1]['name'], 'Test Group Once Again');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgGroupsWithPagsize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/groups?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de');
        $this->assertEquals($content['data'][0]['name'], 'Test Group');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgGroupsWithPagination()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/groups?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '153f3e9e-eb07-4ca4-be78-34f715bd124');
        $this->assertEquals($content['data'][0]['name'], 'Test Group 5');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgGroupsWithInvalidOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-/groups?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Organization not found');
    }

    public function testgetOrgGroupsWithInvalidFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/groups?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"nbn"},{"field":"description","operator":"startswith","value":"ngjdg"}]},"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
        $this->assertEquals($content['total'], 0);
    }
//Project

    public function testgetOrgProjects()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/projects', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '3dbacd80-ff27-4169-a683-4a45d2a8fb8f');
        $this->assertEquals($content['data'][0]['name'], 'New Project');
        $this->assertEquals($content['data'][0]['manager_id'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['org_id'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['data'][1]['uuid'], '886d7eff-6bae-4892-baf8-6fefc56cbf0b');
        $this->assertEquals($content['data'][1]['name'], 'Test Project 1');
        $this->assertEquals($content['data'][1]['manager_id'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][2]['uuid'], 'ced672bb-fe33-4f0a-b153-f1d182a02603');
        $this->assertEquals($content['data'][2]['name'], 'Test Project 2');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgProjectWithFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/projects?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"ct"},{"field":"description","operator":"startswith","value":"dEs"}]},"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '3dbacd80-ff27-4169-a683-4a45d2a8fb8f');
        $this->assertEquals($content['data'][0]['name'], 'New Project');
        $this->assertEquals($content['data'][0]['description'], 'Description Test Data');
        $this->assertEquals($content['data'][0]['manager_id'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['org_id'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['total'], 1);
    }

    public function testgetOrgProjectsWithSortFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/projects?filter=[{"sort":[{"field":"uuid","dir":"asc"}],"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '3dbacd80-ff27-4169-a683-4a45d2a8fb8f');
        $this->assertEquals($content['data'][0]['name'], 'New Project');
        $this->assertEquals($content['data'][1]['uuid'], '886d7eff-6bae-4892-baf8-6fefc56cbf0b');
        $this->assertEquals($content['data'][1]['name'], 'Test Project 1');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgProjectsWithPagsize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/projects?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '3dbacd80-ff27-4169-a683-4a45d2a8fb8f');
        $this->assertEquals($content['data'][0]['name'], 'New Project');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgProjectsWithPagination()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/projects?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '886d7eff-6bae-4892-baf8-6fefc56cbf0b');
        $this->assertEquals($content['data'][0]['name'], 'Test Project 1');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgProjectsWithInvalidOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-/projects?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Organization not found');
    }

    public function testgetOrgProjectsWithInvalidFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/projects?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"nbn"},{"field":"description","operator":"startswith","value":"ngjdg"}]},"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
        $this->assertEquals($content['total'], 0);
    }
// Announcements

    public function testgetOrgAnnouncements()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcements', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '9068b460-2943-4508-bd4c-2b29238700f3');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 1');
        $this->assertEquals($content['data'][0]['org_id'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['data'][1]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][1]['name'], 'Announcement 2');
        $this->assertEquals(7, $content['total']);
    }

    public function testgetOrgAnnouncementsWithFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcements?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"2"},{"field":"description","operator":"startswith","value":"announ"}]},"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 2');
        $this->assertEquals($content['data'][0]['description'], 'Announcemnt Test');
        $this->assertEquals($content['data'][0]['org_id'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['total'], 1);
    }

    public function testgetOrgAnnouncementsWithSortFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcements?filter=[{"sort":[{"field":"uuid","dir":"asc"}],"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '36c8980d-48c2-45fc-b5bc-4407c80f6d71');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 7');
        $this->assertEquals($content['data'][1]['uuid'], '70d53bec-6e2d-4128-933b-96caa3d88e2a');
        $this->assertEquals($content['data'][1]['name'], 'Announcement 6');
        $this->assertEquals($content['total'], 7);
    }

    public function testgetOrgAnnouncementsWithPagsize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcements?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '9068b460-2943-4508-bd4c-2b29238700f3');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 1');
        $this->assertEquals($content['total'], 7);
    }

    public function testgetOrgAnnouncementsWithPagination()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcements?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 2');
        $this->assertEquals($content['total'], 7);
    }

    public function testgetOrgAnnouncementsWithInvalidOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-/announcements?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Organization not found');
    }

    public function testgetOrgAnnouncementsWithInvalidFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcements?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"nbn"},{"field":"description","operator":"startswith","value":"ngjdg"}]},"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
        $this->assertEquals($content['total'], 0);
    }
// Roles

    public function testgetOrgRoles()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/roles', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'], 'ADMIN');
        $this->assertEquals($content['data'][0]['org_id'], '53012471-2863-4949-afb1-e69b0891c98a');
        $this->assertEquals($content['data'][1]['name'], 'EMPLOYEE');
        $this->assertEquals($content['data'][2]['name'], 'MANAGER');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgRolesWithFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/roles?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"ger"},{"field":"description","operator":"startswith","value":"Must"}]},"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'], 'MANAGER');
        $this->assertEquals($content['total'], 1);
    }

    public function testgetOrgRolesWithSortFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/roles?filter=[{"sort":[{"field":"uuid","dir":"asc"}],"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'], 'ADMIN');
        $this->assertEquals($content['data'][1]['name'], 'MANAGER');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgRolesWithPagsize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/roles?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'], 'ADMIN');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgRolesWithPagination()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/roles?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'], 'EMPLOYEE');
        $this->assertEquals($content['total'], 3);
    }

    public function testgetOrgRolesWithInvalidOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-/roles?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Organization not found');
    }

    public function testgetOrgRolesWithInvalidFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/roles?filter=[{"filter":{"filters":[{"field":"name","operator":"endswith","value":"nbn"},{"field":"description","operator":"startswith","value":"ngjdg"}]},"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
        $this->assertEquals($content['total'], 0);
    }
}
