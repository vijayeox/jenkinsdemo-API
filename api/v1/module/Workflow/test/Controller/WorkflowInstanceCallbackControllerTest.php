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
use Oxzion\Utils\StringUtils;
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

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Workflow');
        $this->assertControllerName(WorkflowInstanceCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WorkflowInstanceCallbackController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testcompleteWorkflowInstance()
    {
        $data = ['processInstanceId'=>'3f20b5c5-0124-11ea-a8a0-22e8105c0790'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflowinstance/complete', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('completeWorkflowInstance');
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $query = "SELECT ox_file.data from ox_file inner join ox_workflow_instance on ox_workflow_instance.file_id = ox_file.id where process_instance_id = '".$data['processInstanceId']."'";
        $queryResult = $this->executeQueryTest($query);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($queryResult[0]['data'], '{"firstname" : "Neha","policy_period" : "1year","card_expiry_date" : "10/24","city" : "Bangalore","accountId" : "53012471-2863-4949-afb1-e69b0891c98a","isequipmentliability" : "1","card_no" : "1234","state" : "karnataka","app_id" : "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4","zip" : "560030","coverage" : "100000","product" : "Individual Professional Liability","address2" : "dhgdhdh","address1" : "hjfjhfjfjfhfg","expiry_date" : "2020-06-30","form_id" : "0","entity_id" : "1","expiry_year" : "2019","lastname" : "Rai","isexcessliability" : "1","credit_card_type" : "credit","workflowId" : "a01a6776-431a-401e-9288-6acf3b2f3925","email" : "bharat@gmail.com"}');
    }
    public function testcompleteWorkflowInstanceFail()
    {
        $data = ['processInstanceId'=>5];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflowinstance/complete', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertMatchedRouteName('completeWorkflowInstance');
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testinitiateWorkflow()
    {
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd","processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd","variables" => array("firstname" => "Neha","policy_period" => "1year","card_expiry_date" => "10/24","city" => "Bangalore","accountId" => "53012471-2863-4949-afb1-e69b0891c98a","isequipmentliability" => "1","fileId"=>"d13d0c68-98c9-11e9-adc5-308d99c9146d","uuid"=>"d13d0c68-98c9-11e9-adc5-308d99c9146d","card_no" => "1234","state" => "karnataka","app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4","zip" => "560030","coverage" => "100000","product" => "Individual Professional Liability","address2" => "dhgdhdh","address1" => "hjfjhfjfjfhfg","expiry_date" => "2020-06-30","form_id" =>"0","entity_id" => "1","created_by"=> "1","expiry_year" => "2019","orgId" => "53012471-2863-4949-afb1-e69b0891c98a","lastname" => "Rai","isexcessliability" => "1","workflow_instance_id" => "5","credit_card_type" => "credit","workflowId" => "1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4","email" => 'bharat@gmail.com'),"parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd","parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflowinstance/start', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('initiateWorkflow');
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        $query = "select * from ox_workflow_instance where id = 5";
        $result = $this->executeQueryTest($query);
        $this->assertEquals(1, count($result));
        $this->assertEquals($data['processInstanceId'], $result[0]['process_instance_id']);
        $this->assertEquals(14, $result[0]['file_id']);
    }

    public function testinitiateWorkflowInvalidData()
    {
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd","processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd","parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd","parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflowinstance/start', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->assertMatchedRouteName('initiateWorkflow');
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals(StringUtils::startsWith($content['message'], 'Invalid Data'), true);
    }

    public function testcompleteWorkflowInstanceWithoutProcessInstanceId()
    {
        $data = ['name'=>'Test Workflow'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflowinstance/complete', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertMatchedRouteName('completeWorkflowInstance');
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
