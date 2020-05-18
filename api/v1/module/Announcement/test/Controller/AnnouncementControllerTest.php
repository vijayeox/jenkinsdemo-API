<?php
namespace Announcement;

use Announcement\Controller\AnnouncementController;
use Oxzion\Test\ControllerTest;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Adapter\AdapterInterface;

class AnnouncementControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../../../Group/test/Dataset/Group.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Announcement.yml");
        return $dataset;
    }

    protected function createDummyFile()
    {
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/announcements/temp/";
        FileUtils::createDirectory($tempFolder);
        copy(dirname(__FILE__) . "/../files/test-oxzionlogo.png", $tempFolder . "test-oxzionlogo.png");
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 6);
        $this->assertEquals($content['data'][3]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033abc');
        $this->assertEquals($content['data'][3]['name'], 'Announcement 3');
        $this->assertEquals($content['data'][4]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][4]['name'], 'Announcement 2');
    }

    public function testGetListWithType(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/a/ANNOUNCEMENT', 'GET');
        $this->assertResponseStatusCode(200);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 3);
        $this->assertEquals($content['data'][2]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033abc');
        $this->assertEquals($content['data'][2]['name'], 'Announcement 3');
        $this->assertEquals($content['data'][1]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][1]['name'], 'Announcement 2');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 1');        
    }

    public function testGetListWithIncorrectType(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/a/something', 'GET');
        $this->assertResponseStatusCode(404);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Type must be ANNOUNCEMENT or HOMESCREEN only');
    }

    public function testGetListWithDifferentType() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/a/HOMESCREEN', 'GET');
        $this->assertResponseStatusCode(200);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '70d53bec-6e2d-4128-933b-96caa3d88e2a');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 6');
        $this->assertEquals($content['data'][1]['uuid'], '36c8980d-48c2-45fc-b5bc-4407c80f6d71');
        $this->assertEquals($content['data'][1]['name'], 'Announcement 7');
    }

    public function testGetListWithOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 6);
        $this->assertEquals($content['data'][3]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033abc');
        $this->assertEquals($content['data'][3]['name'], 'Announcement 3');
        $this->assertEquals($content['data'][4]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][4]['name'], 'Announcement 2');
        $this->assertEquals($content['data'][5]['name'], 'Announcement 1');
    }

    public function testGetListWithExpirationStartDate() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/a/HOMESCREEN', 'GET');
        $this->assertResponseStatusCode(200);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertNotContains('Announcement 8',array_column($content['data'],'name'));
        $this->assertEquals($content['status'], 'success');
    }

    public function testGetListWithExpirationEndDate() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/a/HOMESCREEN', 'GET');
        $this->assertResponseStatusCode(200);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertNotContains('Announcement 9',array_column($content['data'],'name'));
        $this->assertEquals($content['status'], 'success');
    }

    public function testGetListofAll()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement/a/ANNOUNCEMENT', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementList');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '9068b460-2943-4508-bd4c-2b29238700f3');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 1');
        $this->assertEquals($content['data'][1]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][1]['name'], 'Announcement 2');
    }

    public function testGetListofAllWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/a/ANNOUNCEMENT', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementList');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '9068b460-2943-4508-bd4c-2b29238700f3');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 1');
        $this->assertEquals($content['data'][1]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][1]['name'], 'Announcement 2');
    }

    public function testGetListofAllByManagerInvalidOrg()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/announcement/a/ANNOUNCEMENT', 'GET');
        $this->assertResponseStatusCode(403);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementList');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to get the announcement list');
    }

    public function testGetListofAllByManagerValidOrg()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/a/ANNOUNCEMENT', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementList');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '9068b460-2943-4508-bd4c-2b29238700f3');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 1');
        $this->assertEquals($content['data'][1]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][1]['name'], 'Announcement 2');
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement/9068b460-2943-4508-bd4c-2b29238700f3', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['uuid'], '9068b460-2943-4508-bd4c-2b29238700f3');
        $this->assertEquals($content['data']['name'], 'Announcement 1');
    }

    public function testGetListOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/9068b460-2943-4508-bd4c-2b29238700f3', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['uuid'], '9068b460-2943-4508-bd4c-2b29238700f3');
        $this->assertEquals($content['data']['name'], 'Announcement 1');
    }

    public function testGetListWithInvalidOrgID()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/announcement/9068b460-2943-4508-bd4c-2b29238700f3', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement/9068b460-2943-4508-bd4c', 'GET');
        $this->assertResponseStatusCode(200);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['data'], array());
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Announcement', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png', 'orgId' => '53012471-2863-4949-afb1-e69b0891c98a', 'type' => 'ANNOUNCEMENT'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['start_date'], $data['start_date']);
        $this->assertEquals($content['data']['end_date'], $data['end_date']);
    }

    public function testCreateWithoutOrgAsEmployeeUser()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Test Announcement', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png', 'type' => 'ANNOUNCEMENT'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(401);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testCreateWithoutOrgAsSuperUser()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Announcement', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png', 'type' => 'ANNOUNCEMENT'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['start_date'], $data['start_date']);
        $this->assertEquals($content['data']['end_date'], $data['end_date']);
    }

    public function testCreateWithOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Announcement', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png', 'type' => 'ANNOUNCEMENT'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);

        $select = "SELECT * from ox_announcement where name = 'Test Announcement'";
        $result = $this->executeQueryTest($select);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['start_date'], $data['start_date']);
        $this->assertEquals($content['data']['end_date'], $data['end_date']);
        $this->assertEquals($result[0]['name'], 'Test Announcement');
        $this->assertEquals($result[0]['org_id'], 1);
    }

    public function testCreateOtherOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Announcement', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png', 'type' => 'ANNOUNCEMENT'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/announcement', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $select = "SELECT * from ox_announcement where name = 'Test Announcement'";
        $result = $this->executeQueryTest($select);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['start_date'], $data['start_date']);
        $this->assertEquals($content['data']['end_date'], $data['end_date']);
        $this->assertEquals($result[0]['name'], 'Test Announcement');
        $this->assertEquals($result[0]['org_id'], 2);
    }

    public function testCreateWithOutNameFailure()
    {
        $this->initAuthToken($this->adminUser);
        $this->createDummyFile();
        $data = ['groups' => '[{"id":1},{"id":2}]', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day"))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateExistingAnnouncement()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Announcement 1', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Announcement already exists');
    }

    public function testCreateExistingAnnouncementWithoutOrg() {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Announcement 1', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Announcement already exists');   
    }

    public function testCreateExpiredAnnouncement()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Announcement 5', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png', 'type' => 'ANNOUNCEMENT'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $select = "SELECT * from ox_announcement where name = 'Announcement 5'";
        $result = $this->executeQueryTest($select);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['start_date'], $data['start_date']);
        $this->assertEquals($content['data']['end_date'], $data['end_date']);
        $this->assertEquals($result[0]['name'], 'Announcement 5');
        $this->assertEquals($result[0]['org_id'], 1);
    }

    public function testCreateWithOtherOrg()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Announcement 5', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png', 'type' => 'ANNOUNCEMENT'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/announcement', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);

        $select = "SELECT * from ox_announcement where name = 'Announcement 5'";
        $result = $this->executeQueryTest($select);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['start_date'], $data['start_date']);
        $this->assertEquals($content['data']['end_date'], $data['end_date']);
        $this->assertEquals($result[0]['name'], 'Announcement 5');
        $this->assertEquals($result[0]['org_id'], 1);
    }

    public function testCreateAccess()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Test Announcement', 'groups' => '[{"id":1},{"id":2}]', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day"))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdate()
    {
        $data = ['name' => 'Test Announcement', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png'];
        // $this->createDummyFile();
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/9068b460-2943-4508-bd4c-2b29238700f3', 'PUT', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateWithOrgId()
    {
        $data = ['name' => 'Test Announcement', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png'];
        // $this->createDummyFile();
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/9068b460-2943-4508-bd4c-2b29238700f3', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateWithDifferentOrg()
    {
        $data = ['name' => 'Test Announcement', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/announcement/9068b460-2943-4508-bd4c-2b29238700f3', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Announcement does not belong to the organization');
    }

    public function testUpdateWithInvalidAnnouncementId()
    {
        $data = ['name' => 'Test Announcement', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/announcement/9068b460-2943-4508-b', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Announcement not found');
    }

    public function testUpdateRestricted()
    {
        $data = ['name' => 'Test Announcement', 'groups' => '[{"id":1}]', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day")), 'media' => 'test-oxzionlogo.png'];
        // $this->createDummyFile();
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/9068b460-2943-4508-bd4c-2b29238700f3', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Test Announcement', 'groups' => '[{"id":1},{"id":2}]', 'status' => 1, 'start_date' => date('Y-m-d H:i:s'), 'end_date' => date('Y-m-d H:i:s', strtotime("+7 day"))];
        // $this->createDummyFile();
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/9068b460-2943-4508-b', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement/e66157ee-47de-4ed5-a78e-8a9195033f7a', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteWithOrg()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/e66157ee-47de-4ed5-a78e-8a9195033f7a', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();

        $select = "SELECT * from ox_attachment where uuid = 'test'";
        $result = $this->executeQueryTest($select);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($result, array());
    }

    public function testDeleteWithDifferentOrg()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/announcement/e66157ee-47de-4ed5-a78e-8a9195033f7a', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Announcement not found');
    }

    public function testDeleteWithInvalidAnnouncement()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/e66157ee-47de-4ed5-a78e7a', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Announcement not found');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement/e66157ee-47de-4ed5-a', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetListOfAnnouncementGroups()
    {
        $this->initAuthToken($this->adminUser);
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $query = "UPDATE `ox_announcement` SET `start_date` = now() ,`end_date` = '" . date('Y-m-d', strtotime("+0 day")) . "' where uuid ='9068b460-2943-4508-bd4c-2b29238700f3'";
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();

        $this->dispatch('/announcement/9068b460-2943-4508-bd4c-2b29238700f3/groups', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementgroups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de');
        $this->assertEquals($content['data'][0]['name'], 'Test Group');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListOfAnnouncementGroupsWithOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $query = "UPDATE `ox_announcement` SET `start_date` = now() ,`end_date` = '" . date('Y-m-d', strtotime("+0 day")) . "' where uuid ='9068b460-2943-4508-bd4c-2b29238700f3'";
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();

        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/9068b460-2943-4508-bd4c-2b29238700f3/groups', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementgroups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de');
        $this->assertEquals($content['data'][0]['name'], 'Test Group');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListOfAnnouncementGroupsWithInvalidAnnouncementID()
    {
        $this->initAuthToken($this->adminUser);
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $query = "UPDATE `ox_announcement` SET `start_date` = now() ,`end_date` = '" . date('Y-m-d', strtotime("+0 day")) . "' where uuid ='9068b460-2943-4508-bd4c-2b29238700f3'";
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();

        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/9068b460-29d4c-2b29238700f3/groups', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementgroups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetListOfAnnouncementGroupsWithInvalidOrgId()
    {
        $this->initAuthToken($this->adminUser);
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $query = "UPDATE `ox_announcement` SET `start_date` = now() ,`end_date` = '" . date('Y-m-d', strtotime("+0 day")) . "' where uuid ='9068b460-2943-4508-bd4c-2b29238700f3'";
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();

        $this->dispatch('/organization/b0971de7-0387-48ea-8f29-5d3704d96a46/announcement/9068b460-2943-4508-bd4c-2b29238700f3/groups', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementgroups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testsaveGroup()
    {
        $data = ['groups' => array(['uuid' => '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de'], ['uuid' => '153f3e9e-eb07-4ca4-be78-34f715bd50db'])];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/9068b460-2943-4508-bd4c-2b29238700f3/save', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementToGroup');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');

        $content = (array) json_decode($this->getResponse()->getContent(), true);

        $select = "SELECT id FROM ox_announcement where uuid = '9068b460-2943-4508-bd4c-2b29238700f3'";
        $result = $this->executeQueryTest($select);

        $select = "SELECT * from ox_announcement_group_mapper where announcement_id = " . $result[0]['id'];
        $announcementGroup = $this->executeQueryTest($select);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['groups']), 2);
        $this->assertEquals($announcementGroup[0]['announcement_id'], 1);
        $this->assertEquals($announcementGroup[0]['group_id'], 1);
        $this->assertEquals($announcementGroup[1]['announcement_id'], 1);
        $this->assertEquals($announcementGroup[1]['group_id'], 2);
    }

    public function testsaveGroupWithOrg()
    {
        $data = ['groups' => array(['uuid' => '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de'], ['uuid' => '153f3e9e-eb07-4ca4-be78-34f715bd50db'])];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/53012471-2863-4949-afb1-e69b0891c98a/announcement/9068b460-2943-4508-bd4c-2b29238700f3/save', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementToGroup');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');

        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $select = "SELECT id FROM ox_announcement where uuid = '9068b460-2943-4508-bd4c-2b29238700f3'";
        $result = $this->executeQueryTest($select);

        $select = "SELECT * from ox_announcement_group_mapper where announcement_id = " . $result[0]['id'];
        $announcementGroup = $this->executeQueryTest($select);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['groups']), 2);
        $this->assertEquals($announcementGroup[0]['announcement_id'], 1);
        $this->assertEquals($announcementGroup[0]['group_id'], 1);
        $this->assertEquals($announcementGroup[1]['announcement_id'], 1);
        $this->assertEquals($announcementGroup[1]['group_id'], 2);
    }

    public function testsaveGroupWithInvalidOrg()
    {
        $data = ['groups' => array(['uuid' => '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de'], ['uuid' => '153f3e9e-eb07-4ca4-be78-34f715bd50db'])];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/5301263-4949-afb1-e69b0891c98a/announcement/9068b460-2943-4508-bd4c-2b29238700f3/save', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementToGroup');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');

        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Announcement does not belong to the organization');
    }

    public function testsaveGroupWithInvalidAnnouncementId()
    {
        $data = ['groups' => array(['uuid' => '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de'], ['uuid' => '153f3e9e-eb07-4ca4-be78-34f715bd50db'])];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/5301263-4949-afb1-e69b0891c98a/announcement/90943-4508-bd4c-2b29238700f3/save', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementToGroup');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');

        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Announcement not found');
    }

    public function testsaveGroupOfDifferentOrg()
    {
        $data = ['groups' => array(['uuid' => '153f3e9e-eb07-4ca4-be78-34f715bd50sd'])];
        // $this->createDummyFile();
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/9068b460-2943-4508-bd4c-2b29238700f3/save', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementToGroup');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');

        $content = (array) json_decode($this->getResponse()->getContent(), true);

        $select = "SELECT id FROM ox_announcement where uuid = '9068b460-2943-4508-bd4c-2b29238700f3'";
        $result = $this->executeQueryTest($select);

        $select = "SELECT * from ox_announcement_group_mapper where announcement_id = " . $result[0]['id'];
        $announcementGroup = $this->executeQueryTest($select);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['groups']), 1);
        $this->assertEquals(count($announcementGroup), 0);
    }

}
