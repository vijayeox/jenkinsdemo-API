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
        $this->assertEquals($content['data'][0]['firstname'], 'Admin');
        $this->assertEquals($content['data'][0]['user_id'], "4fd99e8e-758f-11e9-b2d5-68ecc57cde45");
        $this->assertEquals($content['data'][1]['firstname'], 'Manager');
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
        $this->assertEquals($content['data']['firstname'], 'Admin');
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
    
}
