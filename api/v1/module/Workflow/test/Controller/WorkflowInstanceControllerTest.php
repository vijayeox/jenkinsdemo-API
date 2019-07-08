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
use Oxzion\Workflow\WorkflowFactory;
use Zend\Db\Adapter\AdapterInterface;
use Mockery;

class WorkflowInstanceControllerTest extends ControllerTest{
    private $processId;
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
        if(enableCamunda == 1){
            $workflowFactory = WorkflowFactory::getInstance();
            $processManager = $workflowFactory->getProcessManager();
            $data = $processManager->deploy('TestProcess1',array(__DIR__."/../Dataset/ScriptTaskTest.bpmn"));
            $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
            $sqlQuery1 = "Update ox_workflow set process_ids='".$data[0]."' where id=1";
            $statement1 = $dbAdapter->query($sqlQuery1);
            $result1 = $statement1->execute();
        }
        $this->processId = $data[0];
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Workflow.yml");
        return $dataset;
    }

    public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/workflow/1/activity/2', 'GET');
        $this->assertResponseStatusCode(405);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/workflow/1/instance/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id']>0, true);
    }

    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/workflow/1/instance/122', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }


    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'workflow3','app_id'=>1];
        $this->setJsonContent(json_encode($data));
        if(enableCamunda==0){
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Workflow\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->with('sampleProcessId',array('workflowId'=>1,'form_id'=>1))->once()->andReturn(array('id'=>1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $this->dispatch('/workflow/1', 'POST', null);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
    }

    public function testCreateFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['sequence'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/workflow/99', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    // public function testUpdate(){
    //     $this->initAuthToken($this->adminUser);
    //     $data = ['name' => 'Sample2','app_id' => 1];
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/workflow/1', 'PUT', null);
    //     $this->assertResponseStatusCode(200);
    //     $this->assertModuleName('App');
    //     $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('WorkflowController');
    //     $this->assertMatchedRouteName('appworkflow');
    //     $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'success');
    //     $this->assertEquals($content['data']['id'], 1);
    //     $this->assertEquals($content['data']['name'], $data['name']);
    // }

    // public function testUpdateNotFound(){
    //     $this->initAuthToken($this->adminUser);
    //     $data = ['name' => 'Sample2'];
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/workflow/122', 'PUT', null);
    //     $this->assertResponseStatusCode(404);
    //     $this->assertModuleName('App');
    //     $this->assertControllerName(WorkflowController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('WorkflowController');
    //     $this->assertMatchedRouteName('appworkflow');
    //     $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    // }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/workflow/1/instance/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');        
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/workflow/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}
