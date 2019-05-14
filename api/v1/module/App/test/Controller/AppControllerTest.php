<?php
namespace App;

use App\Controller\AppController;
use App\Controller\AppRegisterController;
use App\Model;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Test\MainControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\ArrayUtils;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Utils\FileUtils;

    

class AppControllerTest extends MainControllerTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }


    public function testAppRegister(){
        $this->initAuthToken($this->adminUser);
        $data = ['applist' => json_encode(array(["name" => "CRM","category" => "organization","options" => ["autostart" => "false","hidden" => "false" ]],["name"=>"Calculator","category" =>  "office","options" => ["autostart" =>  "false","hidden" => "false"]],["name" => "Calendar","category" =>  "collaboration","options" =>  ["autostart" => "false","hidden" => "false"]],["name" => "Chat","category" => "collaboration","options" => ["autostart" => "true","hidden" => "true"]],["name" => "FileManager","category" => "office","options" => ["autostart" => "false","hidden" => "false"]],["name" => "Mail","category" => "collaboration","options" => ["autostart" => "true","hidden" => "true"]],["name" => "MailAdmin","category" => "utilities","options" => ["autostart" => "false","hidden" => "false"]],["name" => "MyTodo","category" => "null","options" => ["autostart" => "false","hidden" => "true"]],["name" => "Textpad","category" => "office","options" => ["autostart" => "false","hidden" => "false"]]))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/register', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppRegisterController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppRegisterController');
        $this->assertMatchedRouteName('appregister');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');     
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }



    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app', 'GET');
        $data = ['data' => array([
            "name"=> "Admin",
            "uuid"=> "946fd092-b4f7-4737-b3f5-14086541492e",
            "description"=> null,
            "type"=> "1",
            "logo"=> "app.png",
            "category"=> "utilities",
            "date_created"=> "2019-04-03 17:13:40",
            "date_modified"=> "2019-04-03 11:49:16",
            "created_by"=> "1",
            "modified_by"=> "1",
            "status"=> 0,
            "org_id"=> "1",
            "start_options"=>null
        ])];
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $diff=array_diff($data, $content);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($diff, array());
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content['data'][0]['uuid']);
        $this->assertEquals($content['data'][0]['name'], 'Admin');
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetAppList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/a', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applist');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 4);
        $this->assertEquals($content['data']['data'][0]['name'], 'Admin');
        $this->assertEquals($content['data']['pagination']['page'], 1);
        $this->assertEquals($content['data']['pagination']['noOfPages'], 1);
        $this->assertEquals($content['data']['pagination']['pageSize'], 20);
    }

    public function testGetAppTypeList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/type/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applisttype');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'App1', 'type' => 2, 'category' => 'EXAMPLE_CATEGORY'];
        $this->dispatch('/app', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'], $data[0]['name']);
    }

    public function testCreateWithOutTextFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['org_id' => 4];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateAccess()
    {
        $this->initAuthToken($this->employeeUserId);
        $data = ['name' => '5c822d497f44n', 'type' => 2, 'category' => 'EXAMPLE_CATEGORY', 'logo' => 'app.png'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app', 'POST', $data);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('App');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdate()
    {
        $data = ['name' => 'Admin App', 'type' => 2, 'category' => 'Admin', 'logo' => 'app.png'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateRestricted()
    {
        $data = ['name' => 'Admin App', 'type' => 2, 'category' => 'EXAMPLE_CATEGORY', 'logo' => 'app.png'];
        $this->initAuthToken($this->employeeUserId);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1', 'PUT', $data);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('App');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Admin App', 'type' => 2, 'category' => 'EXAMPLE_CATEGORY', 'logo' => 'app.png'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/64', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/24783', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testDeploy(){
        $this->initAuthToken($this->adminUser);
        $_FILES = array(
            'files'    =>  array(
                'name'      =>  'ScriptTaskTest.bpmn',
                'tmp_name'  =>  __DIR__."/../Dataset/ScriptTaskTest.bpmn",
                'size'      =>  filesize(__DIR__."/../Dataset/ScriptTaskTest.bpmn"),
                'error'     =>  0
            )
        );
        $data = array('name'=>'NewWorkflow');
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1/deployworkflow', 'POST',$data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
    }
    public function testDeployWithOutName(){
        $this->initAuthToken($this->adminUser);
        $_FILES = array(
            'files'    =>  array(
                'name'      =>  'ScriptTaskTest.bpmn',
                'tmp_name'  =>  __DIR__."/../Dataset/ScriptTaskTest.bpmn",
                'size'      =>  filesize(__DIR__."/../Dataset/ScriptTaskTest.bpmn"),
                'error'     =>  0
            )
        );
        $data = array();
        $this->dispatch('/app/1/deployworkflow', 'POST',$data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
    }
    public function testWithOutFile(){
        $_FILES =array();
        $this->initAuthToken($this->adminUser);
        $data = array('name'=>'NewWorkflow');
        $this->dispatch('/app/1/deployworkflow', 'POST',$data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
    }
}