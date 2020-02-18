<?php

namespace Attachment;

use Attachment\Controller\AttachmentController;
use Oxzion\Test\ControllerTest;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;

class AttachmentControllerTest extends ControllerTest
{

    // public $testFile = array('name'=>'oxzionlogo.png','tmp_name'=>__DIR__."/../files/oxzionlogo.png",'type'=>'image/png','size'=>sizeof(__DIR__."/../files/oxzionlogo.png"),'error'=>0);

    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    public function testAnnouncementCreate()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/announcements/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/oxzionlogo.png", $tempFolder . "oxzionlogo.png");
        $data = array('type' => 'ANNOUNCEMENT', 'files' => array(array('extension' => 'png', 'uuid' => 'test', 'file_name' => 'oxzionlogo')));

        $_FILES['file'] = array();
        $_FILES['file']['name'] = 'oxzionlogo.png';
        $_FILES['file']['type'] = 'png';
        $_FILES['file']['tmp_name'] = __DIR__ . '/../files/oxzionlogo.png';
        $_FILES['file']['error'] = 0;
        $_FILES['file']['size'] = 1007;

        $this->dispatch('/attachment', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Attachment');
        $this->assertControllerName(AttachmentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AttachmentController');
        $this->assertMatchedRouteName('attachment');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAnnouncementWithoutFileCreate()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/announcements/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/oxzionlogo.png", $tempFolder . "oxzionlogo.png");

        $_FILES = null; // Since this is a global variable, we need to set it to null
        $data = array('type' => 'ANNOUNCEMENT');
        $this->dispatch('/attachment', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Attachment');
        $this->assertControllerName(AttachmentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AttachmentController');
        $this->assertMatchedRouteName('attachment');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'File Not attached');
    }

    public function testAnnouncementWithoutDataSetCreate()
    {
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER'] . "organization/" . $this->testOrgId . "/announcements/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__ . "/../files/oxzionlogo.png", $tempFolder . "oxzionlogo.png");
        $data = array('type' => 'ANNOUNCEMENT');
        $_FILES['file'] = array();
        $_FILES['file']['name'] = 'oxzionlogo.png';
        $_FILES['file']['type'] = 'png';
        $_FILES['file']['tmp_name'] = __DIR__ . '/../files/oxzionlogo.png';
        $_FILES['file']['error'] = 0;
        $_FILES['file']['size'] = 1007;

        $data = null;
        $this->dispatch('/attachment', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Attachment');
        $this->assertControllerName(AttachmentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AttachmentController');
        $this->assertMatchedRouteName('attachment');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        // print_r($content);exit;
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Empty Dataset Sent');
    }
}
