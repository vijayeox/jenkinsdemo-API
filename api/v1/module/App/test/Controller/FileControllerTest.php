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
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Encryption\Crypto;

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

    private function getFieldUuid(){
        $selctQuery = "SELECT * from ox_form where id=1";
        $selectResult = $this->executeQueryTest($selctQuery);
        return $selectResult;
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/465c8ff8-df82-11e9-8a34-2a2ae2dbcce4/file/d13d0c68-98c9-11e9-adc5-308d99c9145b', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['uuid'], 'd13d0c68-98c9-11e9-adc5-308d99c9145b');
    }
    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/465c8ff8-df82-11e9-8a34-2a2ae2dbcce4/file/202d5c14-df9a-11e9-9d36-2a2ae2dbcce4', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['field1' => '1','field2' => '2','entity_id' => 1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/465c8ff8-df82-11e9-8a34-2a2ae2dbcce4/file', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['field1'], $data['field1']);
        //TODO add ox_file_attribute table data verification
        //TODO add ox_file data column verification
    }

    public function testCreateAccess()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['field1' => '1','field2' => '2'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/465c8ff8-df82-11e9-8a34-2a2ae2dbcce4/file', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('App');
        $this->assertControllerName(FileController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileController');
        $this->assertMatchedRouteName('appfile');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    public function testUpdate()
    {
        $data = ['field1' => '2','field2' => '3'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $selectResult = $this->getFieldUuid();
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/'.$selectResult[0]['uuid'].'/file/d13d0c68-98c9-11e9-adc5-308d99c9145b', 'PUT', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['field1'], $data['field1']);
        $this->assertEquals($content['data']['field2'], $data['field2']);
        //TODO add ox_file_attribute table data 
        //TODO add ox_file data column verification
    }
    public function testUpdateRestricted()
    {
        $data = ['name' => 'Test File 1','app_id'=>1];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/465c8ff8-df82-11e9-8a34-2a2ae2dbcce4/file', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('App');
        $this->assertControllerName(FileController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileController');
        $this->assertMatchedRouteName('appfile');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Test File 1','app_id'=>1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/465c8ff8-df82-11e9-8a34-2a2ae2dbcce4/file/ef993a90-df86-11e9-8a34-2a2ae2dbcce4', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/form/465c8ff8-df82-11e9-8a34-2a2ae2dbcce4/file/d13d0c68-98c9-11e9-adc5-308d99c9145c', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testGetPdfFile()
    {
        $fileId ="d13d0c68-98c9-11e9-adc5-308d99c9145b";
        $this->initAuthToken($this->adminUser);
        $orgUuid = $this->testOrgUuid;
        
        $path1 = __DIR__.'/../../../../data/template/'.$orgUuid."/";
        if (!is_dir($path1)){
            mkdir($path1, 0777,true);
        }
        $path =$path1.$fileId;
        if (!is_link($path)) {
            symlink(__DIR__.'/../../../../../../clients/Dive Insurance/test/Files',$path);
        }
        $crypto = new Crypto();
        $documentName = $crypto->encryption($path."/dummy.pdf");
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/file/'.$fileId.'/document/'.$documentName, 'GET');
        $content = json_decode($this->getResponse()->getContent(), true); 
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(FileController::class);
        $this->assertControllerClass('FileController');
        $this->assertMatchedRouteName('getdocument');
        $this->assertNotEquals(strlen($this->getResponse()), 0);
        if (is_link($path)) {
            unlink($path);
        }
        FileUtils::rmDir($path1);
    }
}
