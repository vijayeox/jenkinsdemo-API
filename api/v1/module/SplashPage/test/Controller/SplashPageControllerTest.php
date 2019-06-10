<?php
namespace SplashPage;

use SplashPage\Controller\SplashPageController;
use SplashPage\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;

class SplashPageControllerTest extends ControllerTest{
    
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/SplashPage.yml");
        // $dataset->addYamlFile(dirname(__FILE__) . "/../../../Group/test/Dataset/Group.yml");
        return $dataset;
    }

    protected function createDummyFile(){
        // $config = $this->getApplicationConfig();
        // $tempFolder = $config['UPLOAD_FOLDER']."splashpage/".$this->testOrgId."/splashpage/temp/";
        // FileUtils::createDirectory($tempFolder);
        // copy(dirname(__FILE__)."/../files/test-oxzionlogo.png", $tempFolder."test-oxzionlogo.png");
    }
    protected function setDefaultAsserts($route=null){
        if ($route == null)
            $route = "splashpage";
        $this->assertModuleName('SplashPage');
        $this->assertControllerName(SplashPageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('SplashPageController');
        $this->assertMatchedRouteName($route);
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    /* getting the splashpage for this user */
    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/splashpage', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['org_id'], 1);
        $this->assertEquals($content['data'][0]['content'], 'Splash for org 1');
        $this->assertEquals($content['data'][0]['enabled'], 1);
    }

    /* getting a splash page for a specific organization id*/
    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/splashpage/organization/2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts("splashpageOrg");
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['id'], 2);
        $this->assertEquals($content['data'][0]['org_id'], 2);
        $this->assertEquals($content['data'][0]['enabled'], 0);
    }

    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $this->createDummyFile();
        $data = ['content' => 'Test Splashpage for org 63','org_id'=>63,'enabled'=>0];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_splashpage'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/splashpage', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['content'], $data['content']);
        $this->assertEquals($content['data']['org_id'], $data['org_id']);
        $this->assertEquals($content['data']['enabled'], $data['enabled']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_splashpage'));
    }

    public function testUpdate(){
        $data = ['content' => 'Update/enable Splashpage for org 2','org_id'=>2,'enabled'=>1];
        $this->createDummyFile();
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/splashpage', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['content'], $data['content']);
        $this->assertEquals($content['data']['org_id'], $data['org_id']);
        $this->assertEquals($content['data']['enabled'], $data['enabled']);
    }

    /* There is no api for delete */
    public function testDisable(){
        $data = ['content' => 'Disable Splashpage for org 2','org_id'=>2,'enabled'=>0];
        $this->createDummyFile();
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/splashpage', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['content'], $data['content']);
        $this->assertEquals($content['data']['org_id'], $data['org_id']);
        $this->assertEquals($content['data']['enabled'], $data['enabled']);
    }

    // error tests
    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/splashpage/organization/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals("There is no splash page for org_id of 64.",$content['data']['errors']['No_Splashpage']);
    }

    public function testCreateWithOutContentFailure(){
        $this->initAuthToken($this->adminUser);
        $this->createDummyFile();
        $data = ['org_id'=>22,'enabled'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/splashpage', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['content'], 'required');
    }

    public function testUpdateWithOutOrgIdFailure(){
        $this->initAuthToken($this->adminUser);
        $this->createDummyFile();
        $data = ['content'=>"No org id!",'enabled'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/splashpage', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['org_id'], 'required');
    }

    public function testCreateRestricted(){
        $this->initAuthToken($this->employeeUser);
        $this->createDummyFile();
        $data = ['content' => 'Create Splashpage for org 62','org_id'=>63,'enabled'=>0];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/splashpage', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Splashpage');
        $this->assertControllerName(SplashPageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('SplashPageController');
        $this->assertMatchedRouteName('splashpage');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateRestricted(){
        $this->initAuthToken($this->managerUser);
        $this->createDummyFile();
        $data = ['content' => 'Update Splashpage for org 1','org_id'=>1,'enabled'=>0];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/splashpage', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Splashpage');
        $this->assertControllerName(SplashPageController::class); // as specified in router's controller name alias
        $this->assertControllerClass('SplashPageController');
        $this->assertMatchedRouteName('splashpage');
        $this->assertResponseHeaderContains('content-type', 'application/json');

        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->createDummyFile();
        $data = ['content' => 'Update Splashpage for org 64','org_id'=>64,'enabled'=>0];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/splashpage', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals("There is no splash page for org_id of 64.",$content['data']['errors']['No_Splashpage']);
    }

    public function estinsertAnnouncementForGroup(){
        // $this->initAuthToken($this->adminUser);
        // $this->dispatch('/announcement/1/group','POST',array('groupid' => '[{"id":1},{"id":2}]')); 
        // $this->assertResponseStatusCode(200);
        //  $this->assertModuleName('Announcement');
        // $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        // $this->assertControllerClass('AnnouncementController');
        // $this->assertMatchedRouteName('announcementToGroup');
        // $content = json_decode($this->getResponse()->getContent(), true);
        // $this->assertEquals($content['status'], 'success'); 
    }

    public function estinsertAnnouncementForGroupIdNotFound(){
        // $this->initAuthToken($this->adminUser);
        // $this->dispatch('/announcement/1/group','POST',array('groupid' => '[{"id":4},{"id":5}]')); 
        // $this->assertResponseStatusCode(404);
        //  $this->assertModuleName('Announcement');
        // $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        // $this->assertControllerClass('AnnouncementController');
        // $this->assertMatchedRouteName('announcementToGroup');
        // $content = json_decode($this->getResponse()->getContent(), true);
        // $this->assertEquals($content['status'], 'error');
        // $this->assertEquals($content['message'], 'Entity not found'); 
    }

    public function estinsertAnnouncementForGroupWithoutId(){
        // $this->initAuthToken($this->adminUser);
        // $this->dispatch('/announcement/1/group','POST'); 
        // $this->assertResponseStatusCode(404);
        //  $this->assertModuleName('Announcement');
        // $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        // $this->assertControllerClass('AnnouncementController');
        // $this->assertMatchedRouteName('announcementToGroup');
        // $content = json_decode($this->getResponse()->getContent(), true);
        // $this->assertEquals($content['status'], 'error'); 
        // $this->assertEquals($content['message'], 'Enter Group Ids');
    }

    
    
    
    
    public function estAddGroupUpdate(){
        // $data = ['name' => 'Test Announcement','groups'=>'[{"id":1},{"id":2}]','status'=>1,'start_date'=>date('Y-m-d H:i:s'),'end_date'=>date('Y-m-d H:i:s',strtotime("+7 day")),'media'=>'test-oxzionlogo.png'];
        // $this->initAuthToken($this->adminUser);
        // // $this->createDummyFile();
        // $this->setJsonContent(json_encode($data));
        // $this->dispatch('/announcement/1', 'PUT', null);
        // $this->assertResponseStatusCode(200);
        // $this->setDefaultAsserts();
        // $content = (array)json_decode($this->getResponse()->getContent(), true);
        // $this->assertEquals($content['status'], 'success');
        // $this->assertEquals($content['data']['id'], 1);
        // $this->assertEquals($content['data']['name'], $data['name']);
        // $this->assertEquals($content['data']['description'], $data['description']);
    }
    public function estRemoveGroupUpdate(){
        // $data = ['name' => 'Test Announcement','groups'=>'[{"id":2}]','status'=>1,'start_date'=>date('Y-m-d H:i:s'),'end_date'=>date('Y-m-d H:i:s',strtotime("+7 day")),'media'=>'test-oxzionlogo.png'];
        // // $this->createDummyFile();
        // $this->initAuthToken($this->adminUser);
        // $this->setJsonContent(json_encode($data));
        // $this->dispatch('/announcement/1', 'PUT', null);
        // $this->assertResponseStatusCode(200);
        // $this->setDefaultAsserts();
        // $content = (array)json_decode($this->getResponse()->getContent(), true);
        // $this->assertEquals($content['status'], 'success');
        // $this->assertEquals($content['data']['id'], 1);
        // $this->assertEquals($content['data']['name'], $data['name']);
        // $this->assertEquals($content['data']['description'], $data['description']);
    }

    

    public function estDeleteNotFound(){
        // $this->initAuthToken($this->adminUser);
        // $this->dispatch('/announcement/122', 'DELETE');
        // $this->assertResponseStatusCode(404);
        // $this->setDefaultAsserts();
        // $content = json_decode($this->getResponse()->getContent(), true);
        // $this->assertEquals($content['status'], 'error');        
    }
}