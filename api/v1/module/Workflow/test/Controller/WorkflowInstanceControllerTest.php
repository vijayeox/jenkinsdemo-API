<?php
namespace Workflow;

use Workflow\Controller\WorkflowInstanceController;
use Workflow\Controller\ActivityInstanceController;
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

class WorkflowInstanceControllerTest extends ControllerTest
{
    private $processId;
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
        if (enableCamunda == 1) {
            $workflowFactory = WorkflowFactory::getInstance();
            $processManager = $workflowFactory->getProcessManager();
            $data = $processManager->deploy('TestProcess1', array(__DIR__."/../Dataset/ScriptTaskTest.bpmn"));
            $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
            $sqlQuery1 = "Update ox_workflow set process_ids='".$data[0]."' where id=1";
            $statement1 = $dbAdapter->query($sqlQuery1);
            $result1 = $statement1->execute();
            $this->processId = $data[0];
        }
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Workflow.yml");
        return $dataset;
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/workflow/1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4/activity/2', 'GET');
        $this->assertResponseStatusCode(405);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'workflow3','app_id'=>1,'field2'=>1];
        $fileCount = $this->getConnection()->getRowCount('ox_file');
        $fileAttributeCount = $this->getConnection()->getRowCount('ox_file_attribute');
        $this->setJsonContent(json_encode($data));
        if (enableCamunda==0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Workflow\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id'=>1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $this->dispatch('/workflow/1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4', 'POST', $data);
        $this->assertEquals($fileAttributeCount+3, $this->getConnection()->getRowCount('ox_file_attribute'));
        $this->assertEquals($fileCount+1, $this->getConnection()->getRowCount('ox_file'));
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('workflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
    }

    public function testCreateFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['sequence'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/workflow/1141cd2e-cb14-11e9-a32f-2a2ae2dbcc89', 'POST', null);
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

    public function testGetListOfFilesWithUserId()
    {
        $this->initAuthToken($this->adminUser);
        $date = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($date. ' + 1 days'));
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/workflow/1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4/4fd99e8e-758f-11e9-b2d5-68ecc57cde45/file?filter=[{"filter":{"filters":[{"field":"expiry_date","operator":"lt","value":"'.$currentDate.'"}]},"sort":[{"field":"expiry_date","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class);
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('filelisting');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true); 
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['data'], 'New File Data - Latest Completed');
        $this->assertEquals($content['data'][0]['status'], 'Completed');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListOfFilesWithUserIdNoData()
    {
        $this->initAuthToken($this->adminUser);
        $date = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($date. ' + 1 days'));
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/workflow/1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4/4fd9ce37-758f-11e9-b2d5-68ecc57cde45/file?filter=[{"filter":{"filters":[{"field":"expiry_date","operator":"lt","value":"'.$currentDate.'"}]},"sort":[{"field":"expiry_date","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class);
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('filelisting');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true); 
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']),0);
        $this->assertEquals($content['total'],0);
    }

    public function testGetListOfFilesWithQueryParameter()
    {
        $this->initAuthToken($this->adminUser);
        $date = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($date. ' + 1 days'));
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/file?filter=[{"filter":{"filters":[{"field":"expiry_date","operator":"lt","value":"'.$currentDate.'"}]},"sort":[{"field":"expiry_date","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class);
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('filelisting');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['data'], 'New File Data - Latest Completed');
        $this->assertEquals($content['data'][0]['status'], 'Completed');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListOfFilesWithWorkflowParameter()
    {
        $this->initAuthToken($this->adminUser);
        $date = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($date. ' + 1 days'));
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/workflow/1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4/file?filter=[{"filter":{"filters":[{"field":"expiry_date","operator":"lt","value":"'.$currentDate.'"}]},"sort":[{"field":"expiry_date","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class);
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('filelisting');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['data'], 'New File Data - Latest Completed');
        $this->assertEquals($content['data'][0]['status'], 'Completed');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListOfFilesWithoutFilters()
    {
        $this->initAuthToken($this->adminUser);
        $date = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($date. ' + 1 days'));
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/file?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class);
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('filelisting');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['data'], 'New File Data - Latest Completed');
        $this->assertEquals($content['data'][0]['status'], 'Completed');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListOfFiles()
    {
        $this->initAuthToken($this->adminUser);
        $date = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($date. ' + 1 days'));
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/file', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class);
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('filelisting');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['data'], 'New File Data - Latest Completed');
        $this->assertEquals($content['data'][0]['status'], 'Completed');
        $this->assertEquals($content['total'], 1);
    }
    
    public function testClaimActivityInstance()
    {
        $this->initAuthToken($this->adminUser);
        if (enableCamunda==0) {
            $mockActivityEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ActivityImpl');
            $activityInstanceService = $this->getApplicationServiceLocator()->get(\Workflow\Service\ActivityInstanceService::class);
            $mockActivityEngine->expects('claimActivity')->with('[activityInstanceId]', $this->adminUser)->once()->andReturn(1);
            $activityInstanceService->setActivityEngine($mockActivityEngine);
        }
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/workflowinstance/1/activityinstance/[activityInstanceId]/claim', 'POST');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('claimActivityInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
    public function testClaimActivityInstanceNotFound()
    {
        $this->initAuthToken($this->employeeUser);
        if (enableCamunda==0) {
            $mockActivityEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ActivityImpl');
            $activityInstanceService = $this->getApplicationServiceLocator()->get(\Workflow\Service\ActivityInstanceService::class);
            $mockActivityEngine->expects('claimActivity')->with('[activityInstanceId]', $this->employeeUser)->once()->andReturn(1);
            $activityInstanceService->setActivityEngine($mockActivityEngine);
        }
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/workflowinstance/1/activityinstance/2/claim', 'POST');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('claimActivityInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testClaimActivityInstanceNewUser()
    {
        $this->initAuthToken($this->employeeUser);
        if (enableCamunda==0) {
            $mockActivityEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ActivityImpl');
            $activityInstanceService = $this->getApplicationServiceLocator()->get(\Workflow\Service\ActivityInstanceService::class);
            $mockActivityEngine->expects('claimActivity')->with('[activityInstanceId]', $this->employeeUser)->once()->andReturn(1);
            $activityInstanceService->setActivityEngine($mockActivityEngine);
        }
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/workflowinstance/1/activityinstance/[activityInstanceId]/claim', 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('claimActivityInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
    public function testGetActivityInstance()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/workflowinstance/1/activityinstance/[activityInstanceId]/form', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('activityInstanceForm');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data'])>0, true);
    }
    public function testGetActivityInstanceNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/workflowinstance/1/activityinstance/99999/form', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('activityInstanceForm');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testcompleteActivityInstance()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'[activityInstanceId]','activityId'=>1 , 'candidates' => array(array('groupid'=>'HR Group','type'=>'candidate'),array('userid'=>'bharatgtest','type'=>'assignee')),'processInstanceId'=>1,'name'=>'Recruitment Request Created', 'status' => 'Active','taskId'=>1,'processVariables'=>array('workflowId'=>1,'orgid'=>$this->testOrgId)];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/activitycomplete', 'POST',$data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(ActivityInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ActivityInstanceController');
        $this->assertMatchedRouteName('completeActivityInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
    public function testcompleteActivityInstanceFail()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'csasdassd','activityId'=>1 , 'candidates' => array(array('groupid'=>'HR Group','type'=>'candidate'),array('userid'=>'bharatgtest','type'=>'assignee')),'processInstanceId'=>1,'name'=>'Recruitment Request Created', 'status' => 'Active','taskId'=>1,'processVariables'=>array('workflowId'=>1,'orgid'=>$this->testOrgId)];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/activitycomplete', 'POST',$data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(ActivityInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ActivityInstanceController');
        $this->assertMatchedRouteName('completeActivityInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testgetFileDocumentList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/file/d13d0c68-98c9-11e9-adc5-308d99c9145b/document', 'GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('filedocumentlisting');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data'])>0, true);
    }

    public function testgetFileDocumentListNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/9fc99df0-d91b-11e9-8a34-2a2ae2dbcce4/file/d13d0c68-98c9-11e9-adc5-308d99c91422/document', 'GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceController');
        $this->assertMatchedRouteName('filedocumentlisting');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
    }
}
