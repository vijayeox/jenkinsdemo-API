<?php
namespace ErrorLog;

use ErrorLog\Controller\ErrorController;
use Oxzion\Test\ControllerTest;
use Oxzion\Model\ErrorTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;

class ErrorControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Error.yml");
        return $dataset;
    }
    protected function setDefaultAsserts()
    {
        $this->assertModuleName('ErrorLog');
        $this->assertControllerName(ErrorController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ErrorController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/errorlog', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
    }
    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = '{"type":"form","payload":"{\"cache_id\":\"58\",\"app_id\":\"d77ea120-b028-479b-8c6e-60476b6a4456\"}"}';
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/errorlog', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
}
