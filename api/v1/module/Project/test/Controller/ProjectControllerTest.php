<?php
namespace Project;

use Project\Controller\ProjectController;
use Project\Model;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class ProjectControllerTest extends ControllerTest {
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Project.yml");
        return $dataset;
    }

    protected function setDefaultAsserts() {
        $this->assertModuleName('Project');
        $this->assertControllerName(ProjectController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ProjectController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    public function testGetList() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['name'], 'Test Project 1');
        $this->assertEquals($content['data'][1]['id'], 3);
        $this->assertEquals($content['data'][1]['name'], 'Test Project 2');
    }
    public function testGet() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Test Project 1');
    }
    public function testGetNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate() {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Project 3','description'=>'Project Description'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_project'));
        $this->dispatch('/project', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_project'));
    }
    public function testCreateWithOutNameFailure() {
        $this->initAuthToken($this->adminUser);
        $data = ['description'=>'Project Description'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateAccess() {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Test Project 1','description'=>'Project Description'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Project');
        $this->assertControllerName(ProjectController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ProjectController');
        $this->assertMatchedRouteName('project');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    public function testUpdate() {
        $data = ['name' => 'Test Project','description'=>'Project Description'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
    }
    public function testUpdateRestricted() {
        $data = ['name' => 'Test Project 1','description'=>'Project Description'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project/1', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Project');
        $this->assertControllerName(ProjectController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ProjectController');
        $this->assertMatchedRouteName('project');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound() {
        $data = ['name' => 'Test Project 1','description'=>'Project Description'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/project/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/project/1222', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }

    public function testSaveUser() {
    	$this->initAuthToken($this->adminUser);
    	$this->dispatch('/project/1/save','POST',array('userid' => '[{"id":2},{"id":3}]')); 
    	$this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
    	$content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success'); 
    }

    public function testSaveUserWithoutUser() {
    	$this->initAuthToken($this->adminUser);
    	$this->dispatch('/project/1/save','POST'); 
    	$this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
    	$content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error'); 
    }

    public function testSaveUserNotFound() {
    	$this->initAuthToken($this->adminUser);
    	$this->dispatch('/project/1/save','POST',array('userid' => '[{"id":1},{"id":23}]')); 
    	$this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
    	$content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetListOfUsers() {
    	$this->initAuthToken($this->adminUser);
    	$this->dispatch('/project/1/users','GET'); 
    	$this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
    	$content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success'); 
    }

    public function testGetListOfUsersNotFound() {
    	$this->initAuthToken($this->adminUser);
    	$this->dispatch('/project/64/users','GET'); 
    	$this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
    	$content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error'); 
    }
}
?>