<?php
namespace Organization;

use Organization\Controller\OrganizationController;
use Organization\Model;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;

class OrganizationControllerTest extends ControllerTest{
    
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Organization.yml");
        return $dataset;
    }

    protected function setDefaultAsserts(){
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertMatchedRouteName('organization');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['name'], 'Cleveland Cavaliers');
        $this->assertEquals($content['data'][1]['id'], 2);
        $this->assertEquals($content['data'][1]['name'], 'Golden State Warriors');
    }
    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Cleveland Cavaliers');
    }
    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Cleveland Cavaliers','logo'=>'logo.png','status'=>'Active'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_organization'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
        $this->assertEquals($content['data']['startdate'], $data['startdate']);
        $this->assertEquals($content['data']['enddate'], $data['enddate']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_organization'));
    }
    public function testCreateWithOutNameFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['logo'=>'logo.png','status'=>'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateAccess(){
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Cleveland Cavaliers','logo'=>'logo.png','status'=>'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertMatchedRouteName('organization');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    public function testUpdate(){
        $data = ['name' => 'Cleveland Cavaliers','logo'=>'logo.png','status'=>'InActive'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['status'], $data['status']);
    }
    public function testUpdateRestricted(){
        $data = ['name' => 'Cleveland Cavaliers','logo'=>'logo.png','status'=>'Active'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/1', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Organization');
        $this->assertControllerName(OrganizationController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OrganizationController');
        $this->assertMatchedRouteName('organization');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound(){
        $data = ['name' => 'Cleveland Cavaliers','logo'=>'logo.png','status'=>'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/organization/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/organization/1222', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}