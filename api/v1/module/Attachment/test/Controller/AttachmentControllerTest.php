<?php
namespace Attachment;

use Attachment\Controller\AttachmentController;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\Utils\FileUtils;

class AttachmentControllerTest extends ControllerTest
{
    
    // public $testFile = array('name'=>'oxzionlogo.png','tmp_name'=>__DIR__."/../files/oxzionlogo.png",'type'=>'image/png','size'=>sizeof(__DIR__."/../files/oxzionlogo.png"),'error'=>0);
    public function setUp() : void
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
        $tempFolder = $config['UPLOAD_FOLDER']."organization/".$this->testOrgId."/announcements/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__."/../files/oxzionlogo.png", $tempFolder."oxzionlogo.png");
        $data = array('type'=>'ANNOUNCEMENT','files'=>array(array('extension'=>'png','uuid'=>'test','file_name'=>'oxzionlogo')));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/attachment', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Attachment');
        $this->assertControllerName(AttachmentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AttachmentController');
        $this->assertMatchedRouteName('attachment');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
}
