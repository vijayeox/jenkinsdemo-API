<?php
namespace App;

use App\Controller\FileController;
use File\Model;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;

class FileControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Workflow.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('App');
        $this->assertControllerName(FileController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileController');
        $this->assertMatchedRouteName('appfile');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    // public function testGet()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $this->dispatch('/app/99/form/1/file/1', 'GET');
    //     $this->assertResponseStatusCode(200);
    //     $this->setDefaultAsserts();
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'success');
    //     $this->assertEquals($content['data']['id'], 1);
    // }
    // public function testGetNotFound()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $this->dispatch('/app/99/form/1/file/9999', 'GET');
    //     $this->assertResponseStatusCode(404);
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    // }
    // public function testCreate()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $data = ['field1' => '1','field2' => '2'];
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/app/99/form/1/file', 'POST', $data);
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertResponseStatusCode(201);
    //     $this->setDefaultAsserts();
    //     $this->assertEquals($content['status'], 'success');
    //     $this->assertEquals($content['data']['field1'], $data['field1']);
    // }

    // public function testCreateAccess()
    // {
    //     $this->initAuthToken($this->employeeUser);
    //     $data = ['field1' => '1','field2' => '2'];
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/app/99/form/1/file', 'POST', null);
    //     $this->assertResponseStatusCode(401);
    //     $this->assertModuleName('App');
    //     $this->assertControllerName(FileController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('FileController');
    //     $this->assertMatchedRouteName('appfile');
    //     $this->assertResponseHeaderContains('content-type', 'application/json');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    //     $this->assertEquals($content['message'], 'You have no Access to this API');
    // }
    public function testUpdate()
    {
        $data = ['field1' => '2','field2' => '3'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/form/1/file/1', 'PUT', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['field1'], $data['field1']);
        $this->assertEquals($content['data']['field2'], $data['field2']);
    }
    // public function testUpdateRestricted()
    // {
    //     $data = ['name' => 'Test File 1','app_id'=>1];
    //     $this->initAuthToken($this->employeeUser);
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/app/99/form/1/file', 'PUT', null);
    //     $this->assertResponseStatusCode(401);
    //     $this->assertModuleName('App');
    //     $this->assertControllerName(FileController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('FileController');
    //     $this->assertMatchedRouteName('appfile');
    //     $this->assertResponseHeaderContains('content-type', 'application/json');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    //     $this->assertEquals($content['message'], 'You have no Access to this API');
    // }

    // public function testUpdateNotFound()
    // {
    //     $data = ['name' => 'Test File 1','app_id'=>1];
    //     $this->initAuthToken($this->adminUser);
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/app/99/form/1/file/22', 'PUT', null);
    //     $this->assertResponseStatusCode(404);
    //     $this->setDefaultAsserts();
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    // }

    // public function testDelete()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $this->dispatch('/app/99/form/1/file/2', 'DELETE');
    //     $this->assertResponseStatusCode(200);
    //     $this->setDefaultAsserts();
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'success');
    // }

    // public function testDeleteNotFound()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $this->dispatch('/app/99/form/1/file/222', 'DELETE');
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertResponseStatusCode(404);
    //     $this->setDefaultAsserts();
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    // }
}
