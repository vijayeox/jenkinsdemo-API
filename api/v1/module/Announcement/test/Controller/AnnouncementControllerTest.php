<?php
namespace Announcement;

use Announcement\Controller\AnnouncementController;
use Announcement\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;
use Oxzion\Transaction\TransactionManager;
use Oxzion\Service\AbstractService;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;


class AnnouncementControllerTest extends ControllerTest{
    
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../../../Group/test/Dataset/Group.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Announcement.yml");
        return $dataset;
    }

    protected function createDummyFile(){
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER']."organization/".$this->testOrgId."/announcements/temp/";
        FileUtils::createDirectory($tempFolder);
        copy(dirname(__FILE__)."/../files/test-oxzionlogo.png", $tempFolder."test-oxzionlogo.png");
    }
    protected function setDefaultAsserts(){
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
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


    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '9068b460-2943-4508-bd4c-2b29238700f3');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 1');
        $this->assertEquals($content['data'][1]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][1]['name'], 'Announcement 2');
    }

    public function testGetListofAll(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement/a', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcementList');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['uuid'], '9068b460-2943-4508-bd4c-2b29238700f3');
        $this->assertEquals($content['data'][0]['name'], 'Announcement 1');
        $this->assertEquals($content['data'][1]['uuid'], 'e66157ee-47de-4ed5-a78e-8a9195033f7a');
        $this->assertEquals($content['data'][1]['name'], 'Announcement 2');
    }

    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement/9068b460-2943-4508-bd4c-2b29238700f3', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['uuid'], '9068b460-2943-4508-bd4c-2b29238700f3');
        $this->assertEquals($content['data']['name'], 'Announcement 1');
    }
    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement/9068b460-2943-4508-bd4c', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        // $this->createDummyFile();
        $data = ['name' => 'Test Announcement','status'=>1,'start_date'=>date('Y-m-d H:i:s'),'end_date'=>date('Y-m-d H:i:s',strtotime("+7 day")),'media'=>'test-oxzionlogo.png'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['startdate'], $data['startdate']);
        $this->assertEquals($content['data']['enddate'], $data['enddate']);
    }

    public function testCreateWithOutNameFailure(){
        $this->initAuthToken($this->adminUser);
        $this->createDummyFile();
        $data = ['groups'=>'[{"id":1},{"id":2}]','status'=>1,'start_date'=>date('Y-m-d H:i:s'),'end_date'=>date('Y-m-d H:i:s',strtotime("+7 day"))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }
    public function testCreateAccess(){
        $this->initAuthToken($this->employeeUser);
        $this->createDummyFile();
        $data = ['name' => 'Test Announcement','groups'=>'[{"id":1},{"id":2}]','status'=>1,'start_date'=>date('Y-m-d H:i:s'),'end_date'=>date('Y-m-d H:i:s',strtotime("+7 day"))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    public function testUpdate(){
        $data = ['name' => 'Test Announcement','status'=>1,'start_date'=>date('Y-m-d H:i:s'),'end_date'=>date('Y-m-d H:i:s',strtotime("+7 day")),'media'=>'test-oxzionlogo.png'];
        // $this->createDummyFile();
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/9068b460-2943-4508-bd4c-2b29238700f3', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
    }
    public function testUpdateRestricted(){
        $data = ['name' => 'Test Announcement','groups'=>'[{"id":1}]','status'=>1,'start_date'=>date('Y-m-d H:i:s'),'end_date'=>date('Y-m-d H:i:s',strtotime("+7 day")),'media'=>'test-oxzionlogo.png'];
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
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound(){
        $data = ['name' => 'Test Announcement','groups'=>'[{"id":1},{"id":2}]','status'=>1,'start_date'=>date('Y-m-d H:i:s'),'end_date'=>date('Y-m-d H:i:s',strtotime("+7 day"))];
        // $this->createDummyFile();
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/9068b460-2943-4508-b', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    
    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement/e66157ee-47de-4ed5-a78e-8a9195033f7a', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/announcement/e66157ee-47de-4ed5-a', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }

     public function testGetListOfAnnouncementGroups() {
        $this->initAuthToken($this->adminUser);
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $query = "UPDATE `ox_announcement` SET `start_date` = now() ,`end_date` = '".date('Y-m-d',strtotime("+0 day"))."' where uuid ='9068b460-2943-4508-bd4c-2b29238700f3'";
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();

        $this->dispatch('/announcement/9068b460-2943-4508-bd4c-2b29238700f3/groups','GET');
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

    public function testsaveGroup(){
         $data = ['groups' => array(['uuid' => '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de'],['uuid' => '153f3e9e-eb07-4ca4-be78-34f715bd50db'])];
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

        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $select = "SELECT id FROM ox_announcement where uuid = '9068b460-2943-4508-bd4c-2b29238700f3'";
        $result = $this->executeQueryTest($select);

        $select = "SELECT * from ox_announcement_group_mapper where announcement_id = ".$result[0]['id'];
        $announcementGroup = $this->executeQueryTest($select);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['groups']), 2);
        $this->assertEquals($announcementGroup[0]['announcement_id'], 1);
        $this->assertEquals($announcementGroup[0]['group_id'], 1);
        $this->assertEquals($announcementGroup[1]['announcement_id'], 1);
        $this->assertEquals($announcementGroup[1]['group_id'], 2);       
    }

    public function testsaveGroupOfDifferentOrg(){
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

        $content = (array)json_decode($this->getResponse()->getContent(), true);
       

        $select = "SELECT id FROM ox_announcement where uuid = '9068b460-2943-4508-bd4c-2b29238700f3'";
        $result = $this->executeQueryTest($select);

        $select = "SELECT * from ox_announcement_group_mapper where announcement_id = ".$result[0]['id'];
        $announcementGroup = $this->executeQueryTest($select);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['groups']), 1);
        $this->assertEquals(count($announcementGroup), 0);     
    }

}