<?php
namespace Workflow;

use Workflow\Controller\WorkflowInstanceController;
use App\Controller\WorkflowController;
use Zend\Stdlib\ArrayUtils;
use Oxzion\Test\ControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Workflow\WorkFlowFactory;
use Mockery;

class WorkflowInstanceControllerTest extends ControllerTest{
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Workflow.yml");
        return $dataset;
    }

    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/workflow', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('appworkflow');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id']>0, true);
        $this->assertEquals($content['data'][0]['name'], 'Test Workflow 1');
        $this->assertEquals($content['data'][1]['id']>1, true);
        $this->assertEquals($content['data'][1]['name'], 'Test Workflow 2');
    }

    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/workflow/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('appworkflow');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id']>0, true);
        $this->assertEquals($content['data']['name'], 'Test Workflow 1');
        
    }

    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/workflow/122', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('appworkflow');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
    }


    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'workflow3','app_id'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/workflow', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('appworkflow');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'] > 2, true);
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testCreateFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['sequence'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/workflow', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('appworkflow');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testUpdate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Sample2','app_id' => 1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/workflow/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('appworkflow');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateNotFound(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Sample2'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/99/workflow/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('appworkflow');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/workflow/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('appworkflow');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');        
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/workflow/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('appworkflow');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
    public function testGetFieldsList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/workflow/1/fields', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('workflowfields');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id']>0, true);
        $this->assertEquals($content['data'][0]['name'], 'field1');
        $this->assertEquals($content['data'][1]['id']>1, true);
        $this->assertEquals($content['data'][1]['name'], 'field2');
    }
    public function testGetFormsList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/99/workflow/1/forms', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowController');
        $this->assertMatchedRouteName('workflowform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id']>0, true);
        $this->assertEquals($content['data'][0]['name'], 'Task');
        $this->assertEquals($content['data'][1]['id']>1, true);
        $this->assertEquals($content['data'][1]['name'], 'Test Form 2');
    }
    public function testFileInstanceGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/workflow/1/activity/1', 'GET');
        $this->assertResponseStatusCode(405);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Method Not Found');
    }

    public function testFileInstanceGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/workflow/1/activity/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['name'], 'Test Task 1');
    }

    public function testFileInstanceGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/workflow/1/activity/1', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testFileInstanceCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test File', 'field1' => 1, 'field2' => 1, 'form_id' => 1];
        $this->setJsonContent(json_encode($data));
        if(enableCamunda==0){
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowService::class);
            $processEngineImpl = Mockery::mock('Oxzion\Workflow\ProcessEngine\ProcessEngineImpl');
            $processEngineImpl->expects('startProcess')->with(1,json_decode('{"name":"Test File","field1":1,"field2":1,"form_id":"1","workflowId":"1","formId":"1"}',true))->once()->andReturn(json_encode(array('definitionId'=>12321)));
            $workflowService->setProcessEngine($processEngineImpl);
        }
        $this->dispatch('/workflow/1/activity/1', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['startdate'], $data['startdate']);
        $this->assertEquals($content['data']['enddate'], $data['enddate']);
    }

    public function testFileInstanceCreateWithOutNameFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['field1' => 1, 'field2' => 1, 'form_id' => 1];
        $this->setJsonContent(json_encode($data));
        if(enableCamunda==0){
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowService::class);
            $processEngineImpl = Mockery::mock('Oxzion\Workflow\ProcessEngine\ProcessEngineImpl');
            $processEngineImpl->expects('startProcess')->with(1,json_decode('{"field1":1,"field2":1,"form_id":"1","workflowId":"1","formId":"1"}',true))->once()->andReturn(json_encode(array('definitionId'=>12321)));
            $workflowService->setProcessEngine($processEngineImpl);
        }
        $this->dispatch('/workflow/1/activity/1', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testFileInstanceUpdate()
    {
        $data = ['name' => 'Test File', 'status' => 1, 'field1' => 1, 'field2' => 2];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/workflow/1/activity/1', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['field1'], $data['field1']);
        $this->assertEquals($content['data']['field2'], $data['field2']);
    }

    public function testFileInstanceUpdateNotFound()
    {
        $data = ['name' => 'Test File', 'status' => 1, 'field1' => 1, 'field2' => 1, 'form_id' => 1];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/workflow/1/activity/1', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    // public function testFileInstanceDelete()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $this->dispatch('/workflow/1/activity/1', 'DELETE');
    //     $this->assertResponseStatusCode(200);
    //     $this->assertModuleName('workflow');
    //     $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('WorkflowInstanceController');
    //     $this->assertMatchedRouteName('workflowInstance');
    //     $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'success');
    // }

    // public function testFileInstanceDeleteNotFound()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $this->dispatch('/workflow/1/activity/1', 'DELETE');
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertResponseStatusCode(404);
    //     $this->assertModuleName('workflow');
    //     $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('WorkflowInstanceController');
    //     $this->assertMatchedRouteName('workflowInstance');
    //     $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    // }
    // public function testAssignments(){
    //     $this->initAuthToken($this->adminUser);
    //     $this->dispatch('/app/99/assignments', 'GET');
    //     $this->assertResponseStatusCode(200);
    //     $this->assertModuleName('workflow');
    //     $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('WorkflowController');
    //     $this->assertMatchedRouteName('assignments');
    //     $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'success');
    //     $this->assertEquals(count($content['data']), 2);
    //     $this->assertEquals($content['data'][0]['id']>0, true);
    //     $this->assertEquals($content['data'][0]['name'], 'Test Workflow 1');
    //     $this->assertEquals($content['data'][1]['id']>1, true);
    //     $this->assertEquals($content['data'][1]['name'], 'Test Workflow 2');
    // }
}
