<?php
namespace File;

use File\Controller\SubscriberController;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;

class SubscriberControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Subscriber.yml");
        return $dataset;
    }
    protected function setDefaultAsserts()
    {
        $this->assertModuleName('File');
        $this->assertControllerName(SubscriberController::class); // as specified in router's controller name alias
        $this->assertControllerClass('SubscriberController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/subscriber', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['firstname'], 'Bharat');
        $this->assertEquals($content['data'][0]['user_id'], "4fd99e8e-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][1]['firstname'], 'Karan');
        $this->assertEquals($content['data'][1]['user_id'], "4fd9ce37-758f-11e9-b2d5-68ecc57cde45");
    }
    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/subscriber/3ff78f56-5748-406b-9ce9-426242c5afc5', 'GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['firstname'], 'Bharat');
        $this->assertEquals($content['data']['user_id'], "4fd99e8e-758f-11e9-b2d5-68ecc57cde45");
    }
    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/subscriber/3ff78f56-5748-406b-9ce9-426242c5a222', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['user_id' => "4fd9f04d-758f-11e9-b2d5-68ecc57cde45"];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_subscriber'));
        $this->dispatch('/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/subscriber', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['user_id'], $data['user_id']);
        $this->assertEquals(true, isset($content['data']['commentId']));
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_subscriber'));
    }
    public function testCreateWithOutUserFailure()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/subscriber', 'POST', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User does not exist');
    }

    // public function testCreateAccess()
    // {
    //     $this->initAuthToken($this->employeeUser);
    //     $data = ['user_id' => 3];
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/subscriber', 'POST', null);
    //     $this->assertResponseStatusCode(401);
    //     $this->assertModuleName('File');
    //     $this->assertControllerName(SubscriberController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('SubscriberController');
    //     $this->assertMatchedRouteName('Subscriber');
    //     $this->assertResponseHeaderContains('content-type', 'application/json');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    //     $this->assertEquals($content['message'], 'You have no Access to this API');
    // }
        
    public function testUpdate()
    {
        $data = ['user_id' => "4fd9ce37-758f-11e9-b2d5-68ecc57cde45"];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/subscriber/3ff78f56-5748-406b-9ce9-426242c5afc5', 'PUT', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['user_id'], $data['user_id']);
    }
    
    // public function testUpdateRestricted()
    // {
    //     $data = ['user_id' => "4fd9f04d-758f-11e9-b2d5-68ecc57cde45"];
    //     $this->initAuthToken($this->employeeUser);
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/subscriber/3ff78f56-5748-406b-9ce9-426242c5afc5', 'PUT', null);
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertResponseStatusCode(401);
    //     $this->assertModuleName('File');
    //     $this->assertControllerName(SubscriberController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('SubscriberController');
    //     $this->assertMatchedRouteName('Subscriber');
    //     $this->assertResponseHeaderContains('content-type', 'application/json');
    //     $this->assertEquals($content['status'], 'error');
    //     $this->assertEquals($content['message'], 'You have no Access to this API');
    // }
    
    public function testUpdateNotFound()
    {
        $data = ['user_id' => "4fd9f04d-758f-11e9-b2d5-68ecc57cde45"];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/subscriber/3ff78f56-5748-406b-9ce9-426242c5a222', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/subscriber/43f78f56-5748-406b-9ce9-426242c5afd6', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/43f78f56-5748-406b-9ce9-426242c5afd6/subscriber/43f78f56-5748-406b-9ce9-426242c5a222', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
