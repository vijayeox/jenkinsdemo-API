<?php
namespace File;

use File\Controller\SubscriberController;
use Oxzion\Test\ControllerTest;
use Bos\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;

class SubscriberControllerTest extends ControllerTest {
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Subscriber.yml");
        return $dataset;
    }
    protected function setDefaultAsserts() {
        $this->assertModuleName('File');
        $this->assertControllerName(SubscriberController::class); // as specified in router's controller name alias
        $this->assertControllerClass('SubscriberController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
   public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1/subscriber', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['user_id'], 1);
        $this->assertEquals($content['data'][1]['id'], 2);
        $this->assertEquals($content['data'][1]['user_id'], 2);
    }
    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1/subscriber/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['user_id'], 1);
    }
    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1/subscriber/23', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['user_id' => 3];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_subscriber'));
        $this->dispatch('/file/1/subscriber', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['user_id'], $data['user_id']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_subscriber'));
    }
    public function testCreateWithOutUserFailure(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1/subscriber', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['user_id'], 'required');
    }

    public function testCreateAccess() {
        $this->initAuthToken($this->employeeUser);
        $data = ['user_id' => 3];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1/subscriber', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('File');
        $this->assertControllerName(SubscriberController::class); // as specified in router's controller name alias
        $this->assertControllerClass('SubscriberController');
        $this->assertMatchedRouteName('Subscriber');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
        
    public function testUpdate() {
        $data = ['user_id' => 2];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1/subscriber/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['user_id'], $data['user_id']);
    }
    public function testUpdateRestricted() {
        $data = ['user_id' => 3];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1/subscriber/1', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('File');
        $this->assertControllerName(SubscriberController::class); // as specified in router's controller name alias
        $this->assertControllerClass('SubscriberController');
        $this->assertMatchedRouteName('Subscriber');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    
    public function testUpdateNotFound(){
        $data = ['user_id' => 3];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1/subscriber/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/2/subscriber/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/2/subscriber/1222', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}
?>