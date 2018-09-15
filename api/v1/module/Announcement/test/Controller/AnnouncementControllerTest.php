<?php
namespace Announcement;

use Announcement\Controller\AnnouncementController;
use Zend\Stdlib\ArrayUtils;
use Announcement\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class AnnouncementControllerTest extends ControllerTest{
    static private $pdo = null;

    public function setUp() : void{
        $configOverrides = [include __DIR__ . '/../../../../config/autoload/global.php'];
        $this->setApplicationConfig(ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php',$configOverrides));
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Announcement.yml");
        return $dataset;
    }
    public function testGetList(){
        $this->initAuthToken('bharatg');
        $this->dispatch('/announcement', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['name'], 'Announcement 1');
        $this->assertEquals($content['data'][1]['id'], 2);
        $this->assertEquals($content['data'][1]['name'], 'Announcement 2');
    }
    public function testGet(){
        $this->initAuthToken('bharatg');
        $this->dispatch('/announcement/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Announcement 1');
    }
    public function testGetNotFound(){
        $this->initAuthToken('bharatg');
        $this->dispatch('/announcement/64', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $data = ['name' => 'Test Announcement','group_id'=>'1,2','org_id'=>1];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_announcement'));
        $this->initAuthToken('bharatg');
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['org_id'], $data['org_id']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_announcement'));
    }
    public function testCreateFailure(){
        $this->initAuthToken('bharatg');
        $data = ['name' => 'Test Announcement','org_id'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
    }
    public function testUpdate(){
        $data = ['id'=>1,'name' => 'Test Announcement 2', 'description' => 'Test Announcement Description'];
        $this->initAuthToken('bharatg');
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $data['id']);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
    }

    public function testUpdateNotFound(){
        $data = ['name' => 'Test Announcement 2', 'description' => 'Test Announcement Description'];
        $this->initAuthToken('bharatg');
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    // public function testUpdateFailure(){
    //     $this->initAuthToken('bharatg');
    //     $data = ['name' => 'Test Announcement 2', 'description' => 'Test Announcement Description'];
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/announcement/122', 'PUT', null);
    //     $this->assertResponseStatusCode(200);
    //     $this->assertModuleName('Announcement');
    //     $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('AnnouncementController');
    //     $this->assertMatchedRouteName('announcement');
    //     $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    //     $this->assertEquals($content['data']['name'], $data['name']);
    //     $this->assertEquals($content['data']['description'], $data['description']);
    // }

    public function testDelete(){
        $this->initAuthToken('bharatg');
        $this->dispatch('/announcement/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken('bharatg');
        $this->dispatch('/announcement/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}