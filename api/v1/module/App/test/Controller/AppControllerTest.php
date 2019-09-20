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
use Oxzion\Workflow\ProcessManager;
use Oxzion\Workflow\WorkflowFactory;
use Mockery;
use Camunda\ProcessManagerImpl;

class AppControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Workflow.yml");
        return $dataset;
    }


    public function testAppRegister()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['applist' => json_encode([["name" => "CRM","category" => "organization","options" => ["autostart" => "false","hidden" => "false" ]],["name"=>"Calculator","category" =>  "office","options" => ["autostart" =>  "false","hidden" => "false"]],["name" => "Calendar","category" =>  "collaboration","options" =>  ["autostart" => "false","hidden" => "false"]],["name" => "Chat","category" => "collaboration","options" => ["autostart" => "true","hidden" => "true"]],["name" => "FileManager","category" => "office","options" => ["autostart" => "false","hidden" => "false"]],["name" => "Mail","category" => "collaboration","options" => ["autostart" => "true","hidden" => "true"]],["name" => "MailAdmin","category" => "utilities","options" => ["autostart" => "false","hidden" => "false"]],["name" => "MyTodo","category" => "null","options" => ["autostart" => "false","hidden" => "true"]],["name" => "Textpad","category" => "office","options" => ["autostart" => "false","hidden" => "false"]]])];
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

    public function testAppRegisterInvaliddata()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['applist' => json_encode([["" => "CRM","category" => "organization","options" => ["autostart" => "false","hidden" => "false" ]],["name"=>"Calculator","category" =>  "office","options" => ["autostart" =>  "false","hidden" => "false"]],["name" => "Calendar","category" =>  "collaboration","" =>  ["autostart" => "false","hidden" => "false"]],["name" => "Chat","category" => "collaboration","options" => ["autostart" => "true","hidden" => "true"]],["name" => "FileManager","category" => "office","options" => ["autostart" => "false","hidden" => "false"]],["name" => "Mail","category" => "collaboration","options" => ["autostart" => "true","hidden" => "true"]],["name" => "MailAdmin","category" => "utilities","options" => ["autostart" => "false","hidden" => "false"]],["name" => "MyTodo","category" => "null","options" => ["autostart" => "false","hidden" => "true"]],["name" => "Textpad","category" => "office","options" => ["autostart" => "false","hidden" => "false"]]])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/register', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(AppRegisterController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppRegisterController');
        $this->assertMatchedRouteName('appregister');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }



    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEquals($content['data'], array());
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
        $this->assertEquals(count($content['data']), 6);
        $this->assertEquals($content['data'][0]['name'], 'Admin');
        $this->assertEquals($content['total'], 6);
    }


    public function testGetAppListWithQuery()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/a?filter=[{"filter":{"logic":"and","filters":[{"field":"name","operator":"startswith","value":"a"},{"field":"category","operator":"contains","value":"utilities"}]},"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":1}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applist');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['name'], 'Admin');
        $this->assertEquals($content['total'], 1);
    }


    public function testGetAppListWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/a?filter=[{"skip":0,"take":2}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applist');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['name'], 'Admin');
        $this->assertEquals($content['data'][1]['name'], 'AppBuilder');
        $this->assertEquals($content['total'], 6);
    }

    public function testGetAppListWithPageSize2()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/a?filter=[{"skip":2,"take":2}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applist');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['name'], 'CRM');
        $this->assertEquals($content['data'][1]['name'], 'MailAdmin');
        $this->assertEquals($content['total'], 6);
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
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testCreateWithOutTextFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = [ 'type' => 2, 'org_id' => 4];
        $this->dispatch('/app', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateAccess()
    {
        $this->initAuthToken($this->employeeUser);
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
        $this->initAuthToken($this->employeeUser);
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
    public function testDeploy()
    {
        $this->initAuthToken($this->adminUser);
        $_FILES = array(
            'files'    =>  array(
                'name'      =>  'ScriptTaskTest.bpmn',
                'tmp_name'  =>  __DIR__."/../Dataset/ScriptTaskTest.bpmn",
                'size'      =>  filesize(__DIR__."/../Dataset/ScriptTaskTest.bpmn"),
                'error'     =>  0
            )
        );
        $workflowFactory = WorkflowFactory::getInstance();
        $processManager = $workflowFactory->getProcessManager();
        $config = $this->getApplicationConfig();
        $baseFolder = $config['UPLOAD_FOLDER'];
        if (enableCamunda==0) {
            $mockProcessManager = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessManagerImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowService::class);
            $mockProcessManager->expects('deploy')->with('NewWorkflow', array('/app/api/v1/config/autoload/../../data/uploads/app/99/workflow/ScriptTaskTest.bpmn'))->once()->andReturn(array(1));
            $mockProcessManager->expects('parseBPMN')->withAnyArgs()->once()->andReturn(null);
            $workflowService->setProcessManager($mockProcessManager);
        }
        $data = array('name'=>'NewWorkflow');
        $this->dispatch('/app/somerandom123/deployworkflow', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        // print_r($content);exit;
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
    }
    public function testDeployWithOutName()
    {
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
        $this->dispatch('/app/somerandom123/deployworkflow', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
    }
    public function testDeployFailedBpmn()
    {
        $this->initAuthToken($this->adminUser);
        $_FILES = array(
            'files'    =>  array(
                'name'      =>  'ScriptTaskTestFail.bpmn',
                'tmp_name'  =>  __DIR__."/../Dataset/ScriptTaskTestFail.bpmn",
                'size'      =>  filesize(__DIR__."/../Dataset/ScriptTaskTestFail.bpmn"),
                'error'     =>  0
            )
        );
        $config = $this->getApplicationConfig();
        $baseFolder = $config['UPLOAD_FOLDER'];
        if (enableCamunda==0) {
            $mockProcessManager = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessManagerImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowService::class);
            $mockProcessManager->expects('deploy')->with('NewWorkflow1', array('/app/api/v1/config/autoload/../../data/uploads/app/99/workflow/ScriptTaskTestFail.bpmn'))->once()->andReturn(0);
            $mockProcessManager->expects('parseBPMN')->withAnyArgs()->once()->andReturn(null);
            $workflowService->setProcessManager($mockProcessManager);
        }
        $data = array('name'=>'NewWorkflow1');
        $this->dispatch('/app/somerandom123/deployworkflow', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
    }
    public function testWithOutFile()
    {
        $_FILES =array();
        $this->initAuthToken($this->adminUser);
        $data = array('name'=>'NewWorkflow');
        $this->dispatch('/app/somerandom123/deployworkflow', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
    }
    public function testAddToAppRegistry(){
        $data = ['app_name' => 'Admin'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/org/b0971de7-0387-48ea-8f29-5d3704d96a46/addtoappregistry', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppRegisterController::class);
        $this->assertControllerClass('AppRegisterController');
        $this->assertMatchedRouteName('addtoappregistry');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['app_name'], $data['app_name']);
    }

    public function testAddToAppRegistryWithoutOrg(){
        $data = ['app_name' => 'Admin'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/addtoappregistry', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppRegisterController::class);
        $this->assertControllerClass('AppRegisterController');
        $this->assertMatchedRouteName('addtoappregistry');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['app_name'], $data['app_name']);
    }

    public function testGetListOfAssignments()
    {
        $this->initAuthToken($this->adminUser);
        $workflowName = 'Test Workflow 1';
        $this->dispatch('/app/somerandom123/assignments?filter=[{"filter":{"filters":[{"field":"workflow_name","operator":"eq","value":"'.$workflowName.'"}]},"sort":[{"field":"workflow_name","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200); 
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class);
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('assignments');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['workflow_name'], $workflowName);
        $this->assertEquals($content['total'],1);
    }

    public function testGetListOfAssignmentsWithoutFiltersValues()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/somerandom123/assignments?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200); 
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class);
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('assignments');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListOfAssignmentsWithoutFilters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/somerandom123/assignments', 'GET');
        $this->assertResponseStatusCode(200); 
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class);
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('assignments');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['total'], 1);
    }
}
