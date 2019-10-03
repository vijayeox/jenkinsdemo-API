<?php
namespace Workflow;

use Workflow\Controller\WorkflowInstanceCallbackController;
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

class WorkflowInstanceCallbackControllerTest extends ControllerTest
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

    public function testcompleteWorkflowInstance()
    {
        $data = ['processInstanceId'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflowinstance/complete', 'POST',$data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceCallbackController');
        $this->assertMatchedRouteName('completeWorkflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
    public function testcompleteWorkflowInstanceFail()
    {
        $data = ['processInstanceId'=>5];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflowinstance/complete', 'POST',$data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceCallbackController');
        $this->assertMatchedRouteName('completeWorkflowInstance');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
