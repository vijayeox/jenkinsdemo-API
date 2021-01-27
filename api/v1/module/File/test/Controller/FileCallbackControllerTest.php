<?php
namespace File;

use Mockery;
use File\Controller\FileCallbackController;
use Oxzion\Test\ControllerTest;
use Oxzion\Service\FileService;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\ServiceException;
use Oxzion\EntityNotFoundException;
use \Exception;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
class FileCallbackControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function tearDown() : void{
        parent::tearDown();
    } 

    public function getDataSet()
    {
        if($this->getName() == 'testUpdateRYGJob') {
            $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/CallBackFile.yml");
            return $dataset;
        } 
        return new DefaultDataSet();
    }

    public function testFileUpdate()
    {
        $data = array("id" => "10", 'uuid' => 'f13d0c68-98c9-11e9-adc5-308d99c9145b');
        $mockFileService = Mockery::mock('\Oxzion\Service\FileService');
        $mockFileService->expects('updateFileAttributes')->with($data['id'])->once()->andReturn();
        $this->setService(FileService::class, $mockFileService);
        $this->dispatch('/callback/file/update', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('File');
        $this->assertControllerName(FileCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileCallbackController');
        $this->assertMatchedRouteName('fileCallbackUpdate');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
    }

    public function testFileUpdateWithServiceException()
    {
        $data = array("id" => "10", 'uuid' => 'f13d0c68-98c9-11e9-adc5-308d99c9145b');
        $mockFileService = Mockery::mock('\Oxzion\Service\FileService');
        $mockFileService->expects('updateFileAttributes')->with($data['id'])->once()->andThrow(new ServiceException("Some Error", "some.error"));
        $this->setService(FileService::class, $mockFileService);
        $this->dispatch('/callback/file/update', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(500);
        $this->assertModuleName('File');
        $this->assertControllerName(FileCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileCallbackController');
        $this->assertMatchedRouteName('fileCallbackUpdate');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Some Error');
    }

    public function testFileUpdateWithNoId()
    {
        $data = array();
        $this->dispatch('/callback/file/update', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('File');
        $this->assertControllerName(FileCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileCallbackController');
        $this->assertMatchedRouteName('fileCallbackUpdate');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Invalid File Id');
    }

    public function testFileUpdateWithInvalidFileId()
    {
        $data = array("id" => "12123213231");
        $mockFileService = Mockery::mock('\Oxzion\Service\FileService');
        $mockFileService->expects('updateFileAttributes')->with($data['id'])->once()->andThrow(new EntityNotFoundException("Invalid File Id"));
        $this->setService(FileService::class, $mockFileService);
        $this->dispatch('/callback/file/update', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('File');
        $this->assertControllerName(FileCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileCallbackController');
        $this->assertMatchedRouteName('fileCallbackUpdate');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Invalid File Id');
    }

    public function testFileUpdateWithException()
    {
        $data = array("id" => "10", 'uuid' => 'InvalidFileId');
        $mockFileService = Mockery::mock('\Oxzion\Service\FileService');
        $mockFileService->expects('updateFileAttributes')->with($data['id'])->once()->andThrow(new Exception("Some System Exception"));
        $this->setService(FileService::class, $mockFileService);
        $this->dispatch('/callback/file/update', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(500);
        $this->assertModuleName('File');
        $this->assertControllerName(FileCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileCallbackController');
        $this->assertMatchedRouteName('fileCallbackUpdate');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Unexpected error.');
    }

        public function testUpdateRYGJob()
    {
        $selctQuery = "SELECT rygStatus from ox_file where id=11";
        $selectResult = $this->executeQueryTest($selctQuery);
        $this->assertEquals('GREEN',$selectResult[0]['rygStatus']);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/updateryg', 'POST', []);
        $content = json_decode($this->getResponse()->getContent(), true);
        $selctQuery = "SELECT rygStatus from ox_file where id=11";
        $selectResult = $this->executeQueryTest($selctQuery);
        $this->assertEquals('RED',$selectResult[0]['rygStatus']);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('File');
        $this->assertControllerName(FileCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileCallbackController');
        $this->assertMatchedRouteName('fileRygStatusUpdate');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
    }
}