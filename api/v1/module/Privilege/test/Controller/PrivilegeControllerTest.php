<?php
namespace Privilege;

use Privilege\Controller\PrivilegeController;
use Oxzion\Test\ControllerTest;
use Privilege\Model;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;


class PrivilegeControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Privilege.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Privilege');
        $this->assertControllerName(PrivilegeController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PrivilegeController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGetUserPrivileges()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/privilege/app/5c767e4297115', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('userprivileges');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'], 'MANAGE_ANNOUNCEMENT');
        $this->assertEquals($content['data'][0]['permission_allowed'], 3);
    }

    public function testGetUserPrivilegesWithWrongApps()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/privilege/app/23435WR34APPS', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('userprivileges');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}