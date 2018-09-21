<?php
namespace Attachment;

use Attachment\Controller\AttachmentController;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class AttachmentControllerTest extends ControllerTest{
    
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        return new DefaultDataSet();
    }
    
    public function testCreate(){
        $this->initAuthToken('bharatg');
        $data = file_get_contents(__DIR__."/../files/oxzionlogo.png");
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
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['startdate'], $data['startdate']);
        $this->assertEquals($content['data']['enddate'], $data['enddate']);
    }
    
}