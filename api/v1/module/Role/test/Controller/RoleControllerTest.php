<?php
namespace Role;

use Role\Controller\RoleController;
use Oxzion\Test\MainControllerTest;
use Role\Model;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;


class RoleControllerTest extends MainControllerTest {
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
   
    protected function setDefaultAsserts() {
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['name'], 'ADMIN');
        $this->assertEquals($content['data']['data'][1]['id'], 2);
        $this->assertEquals($content['data']['data'][1]['name'], 'EMPLOYEE');
        $this->assertEquals($content['data']['data'][2]['id'], 3);
        $this->assertEquals($content['data']['data'][2]['name'], 'MANAGER');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 1);
        $this->assertEquals($content['data']['pagination']['pageSize'], 20);
    }

    public function testGetListWithQuery(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role?f=name&pg=2&psz=2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data']['data'][0]['id'], 3);
        $this->assertEquals($content['data']['data'][0]['name'], 'MANAGER');
        $this->assertEquals($content['data']['pagination']['page'], 2);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 2);
        $this->assertEquals($content['data']['pagination']['pageSize'], 2);
    }

    public function testGetListWithQueryPageNo(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role?f=name&pg=1&psz=2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data']['data'][0]['id'], 1);
        $this->assertEquals($content['data']['data'][0]['name'], 'ADMIN');
        $this->assertEquals($content['data']['data'][1]['id'], 2);
        $this->assertEquals($content['data']['data'][1]['name'], 'EMPLOYEE');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 2);
        $this->assertEquals($content['data']['pagination']['pageSize'], 2);
    }

    public function testGetListWithQueryByName(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role?f=name&q=emp', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(2, count($content['data']));
        $this->assertEquals($content['data']['data'][0]['id'], 2);
        $this->assertEquals($content['data']['data'][0]['name'], 'EMPLOYEE');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 1);
        $this->assertEquals($content['data']['pagination']['pageSize'], 20);
    }

    public function testRolePrivilege(){
        $this->initAuthToken($this->adminUser);
        $data = ['data' => array([
            "id"=> "1",
            "role_id"=> "1",
            "privilege_name"=> "MANAGE_ANNOUNCEMENT",
            "permission"=> "3",
            "org_id"=> "1",
            "app_id"=> "5ca5bca3b735a",
            "name"=> "Admin App"
        ],
        [
            "id"=> "16",
            "role_id"=> "1",
            "privilege_name"=> "MANAGE_GROUP",
            "permission"=> "15",
            "org_id"=> "1",
            "app_id"=> "5ca5bca3b735a",
            "name"=> "Admin App"
        ],
        [
            "id"=> "17",
            "role_id"=> "1",
            "privilege_name"=> "MANAGE_ORGANIZATION",
            "permission"=> "15",
            "org_id"=> "1",
            "app_id"=> "5ca5bca3b735a",
            "name"=> "Admin App"
        ],
        [
            "id"=> "18",
            "role_id"=> "1",
            "privilege_name"=> "MANAGE_USER",
            "permission"=> "15",
            "org_id"=> "1",
            "app_id"=> "5ca5bca3b735a",
            "name"=> "Admin App"
        ],
        [
            "id"=> "19",
            "role_id"=> "1",
            "privilege_name"=> "MANAGE_PROJECT",
            "permission"=> "15",
            "org_id"=> "1",
            "app_id"=> "5ca5bca3b735a",
            "name"=> "Admin App"
        ],
        [
            "id"=> "30",
            "role_id"=> "1",
            "privilege_name"=> "MANAGE_ROLE",
            "permission"=> "3",
            "org_id"=> "1",
            "app_id"=> "5ca5bca3b735a",
            "name"=> "Admin App"
        ])];
        $this->dispatch('/role/1/privilege', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertMatchedRouteName('roleprivilege');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $diff=array_diff($data, $content);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($diff, array());
    }

    public function testRolePrivilegeNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role/12345/privilege', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertMatchedRouteName('roleprivilege');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }


    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['name'], 'ADMIN');
    }
    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'ADMIN_SUPER','org_id' => 2];
        $this->dispatch('/role', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'], $data[0]['name']);
    }
    public function testCreateWithOutTextFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['org_id' => 4];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateAccess() {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'ADMIN_SUPER 1','org_id' => 4];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertMatchedRouteName('Role');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
        
    public function testUpdate() {
        $data = ['name' => 'ADMINs'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateRestricted() {
        $data = ['name' => 'ADMINs'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role/1', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Role');
        $this->assertControllerName(RoleController::class); // as specified in router's controller name alias
        $this->assertControllerClass('RoleController');
        $this->assertMatchedRouteName('Role');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    
    public function testUpdateNotFound(){
        $data = ['name' => 'ADMINs'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/role/64', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/role/24783', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}