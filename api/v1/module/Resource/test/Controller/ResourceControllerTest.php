<?php
namespace Resource;

use Resource\Controller\ResourceController;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\Utils\FileUtils;


class ResourceControllerTest extends ControllerTest{
    
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Attachment.yml");
        return $dataset;
    }
    public function testResourceGet(){
        $this->initAuthToken($this->adminUser);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['UPLOAD_FOLDER']."organization/".$this->testOrgId."/announcements/";
        FileUtils::createDirectory($tempFolder);
        copy(__DIR__."/../files/oxzionlogo.png", $tempFolder."oxzionlogo.png");
        $this->dispatch('/resource/test', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Resource');
        $this->assertControllerName(ResourceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ResourceController');
        $this->assertMatchedRouteName('resource');
        $this->assertNotEquals(strlen($this->getResponse()),0);
    }
}