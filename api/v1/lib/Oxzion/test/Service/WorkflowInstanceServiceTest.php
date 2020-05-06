<?php
namespace Oxzion\Service;

use Oxzion\Test\AbstractServiceTest;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Symfony\Component\Yaml\Yaml;
use Oxzion\ServiceException;
use Oxzion\Workflow\WorkflowFactory;
use Oxzion\ValidationException;
use Oxzion\EntityNotFoundException;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use \Exception;
use Mockery;
use Zend\Db\ResultSet\ResultSet;

class WorkflowInstanceServiceTest extends AbstractServiceTest
{
    public $dataset = null;

    public $adapter = null;

    protected function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
        $this->workflowInstanceService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
        AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, '53012471-2863-4949-afb1-e69b0891c98a');
        AuthContext::put(AuthConstants::USER_ID, 1);
        if (enableCamunda == 1) {
            $workflowFactory = WorkflowFactory::getInstance();
            $processManager = $workflowFactory->getProcessManager();
            $data = $processManager->deploy('TestProcess1', array(dirname(__FILE__) . "/Dataset/testBpmn.bpmn"));
            $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
            $sqlQuery1 = "Update ox_workflow set process_ids='" . $data[0] . "' where id=1";
            $this->runQuery($sqlQuery1);
            $this->processId = $data[0];
        }
        $this->dataset = $this->parseYaml();
        $this->adapter = $this->getDbAdapter();
        $this->adapter->getDriver()->getConnection()->setResource(static::$pdo);
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/Dataset/File.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../../../../module/User/test/Dataset/User.yml");
        return $dataset;
    }

    private function parseYaml(){
        $dataset = Yaml::parseFile(dirname(__FILE__)."/Dataset/File.yml");
        return $dataset;
    }

    private function runQuery($query) {
        $statement = $this->adapter->query($query);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $result = $resultSet->initialize($result)->toArray();
        return $result;
    }

    public function testStartWorkflowSetupIdentityField() {
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4' ,'identifier_field' =>'id_field','id_field' => '2020', 'email' => 'brian@gmail.com', 'address1' => 'addr1',
          'address2' => "", 'city' => 'city', 'state' => 'state', 'country' => 'country', 'zip' => 2323 , 'firstname' => 'brian', 'lastname' => 'test');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT * FROM ox_user order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals('brian',$newQueryResult[0]['firstname']);
        $this->assertEquals('test',$newQueryResult[0]['lastname']);
        $this->assertEquals('brian@gmail.com',$newQueryResult[0]['email']);
    }

    public function testStartWorkflowWithWrongAppId() {
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4', 'app_id' => '8ab30b2d-d1da-427a-8e40-bc954b2b0f87');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        try {
            $result = $this->workflowInstanceService->startWorkflow($params);
        }
        catch(EntityNotFoundException $e) {
            $this->assertEquals('No workflow found for workflow '.$params['workflowId'],$e->getMessage());
        }
    }

    public function testStartWorkflowWithCorrectAppId() {
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4', 'app_id' => '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT app_id FROM ox_workflow_instance order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(99,$newQueryResult[0]['app_id']);
    }

    public function testStartWorkflowWithoutWorkflowId() {
    	$dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2);
        try{
            $result = $this->workflowInstanceService->startWorkflow($params);
        }
        catch(EntityNotFoundException $e) {
            $this->assertEquals('No workflow or workflow instance id provided', $e->getMessage());
        }
    }

    public function testStartWorkflowWithInvalidWorkflowId() {
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => 'b3d97877-9e1f-484a-907d-fb798179e43a');
        try{
            $result = $this->workflowInstanceService->startWorkflow($params);
        }
        catch(EntityNotFoundException $e) {
            $this->assertEquals('No workflow found for workflow '.$params['workflowId'], $e->getMessage());
        }
    }

    public function testStartWorkflowWithWorkflowId() {
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT * FROM ox_file order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals('{"field1":1,"field2":2,"appId":null}',$newQueryResult[0]['data']);
        $this->assertEquals(1,$newQueryResult[0]['created_by']);
    }

    public function testStartWorkflowWithOrgId(){
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4', 'orgId' => 'b0971de7-0387-48ea-8f29-5d3704d96a46');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT org_id FROM ox_workflow_instance order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(2,$newQueryResult[0]['org_id']);
    }

    public function testStartWorkflowWithoutOrgId(){
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT org_id FROM ox_workflow_instance order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(1,$newQueryResult[0]['org_id']);
    }

    public function testStartWorkflowWithCreatedBy(){
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4', 'created_by' => 'd9890624-8f42-4201-bbf9-675ec5dc8400');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT created_by FROM ox_workflow_instance order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(7,$newQueryResult[0]['created_by']);
    }

    public function testStartWorkflowWithoutCreatedBy(){
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT created_by FROM ox_workflow_instance order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(1,$newQueryResult[0]['created_by']);
    }

    public function testStartWorkflowWithEntityId(){
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4', 'entity_id' => 2);
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT entity_id FROM ox_file order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(2,$newQueryResult[0]['entity_id']);
    }

    public function testStartWorkflowWithoutEntityId(){
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT entity_id FROM ox_file order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(1,$newQueryResult[0]['entity_id']);
    }

    public function testStartWorkflowCleanData() {
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4', 'uuid' => '31447b1d-c49a-4545-9b26-8d6873a0c5b9');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT data FROM ox_file order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertArrayNotHasKey('workflowId',$newQueryResult[0]);
        $this->assertArrayNotHasKey('uuid',$newQueryResult[0]);
    }

    public function testStartWorkflowWithParentWorkflowInstance() {
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbccpo', 'parentWorkflowInstanceId' => 'd321b276-9e1c-4bdf-8238-7340f9599383');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT data FROM ox_file where id = 18';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals('{"firstname":"brian","email":"brian@gmail.com","field1":1,"field2":2,"appId":null}',$newQueryResult[0]['data']);
    }

    public function testStartWorkflowUpdateWorkflowInstanceScenario(){
        $dataset = $this->dataset;
        $params = array('field1' => 1, 'field2' => 2, 'workflowId' => '1141cd2e-cb14-11e9-a32f-2a2ae2dbcce4');
        if (enableCamunda == 0) {
            $mockProcessEngine = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessEngineImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowInstanceService::class);
            $mockProcessEngine->expects('startProcess')->withAnyArgs()->once()->andReturn(array('id' => 1));
            $workflowService->setProcessEngine($mockProcessEngine);
            $this->processId = 1;
        }
        $result = $this->workflowInstanceService->startWorkflow($params);
        $sqlQuery = 'SELECT process_instance_id FROM ox_workflow_instance order by id DESC LIMIT 1';
        $newQueryResult = $this->runQuery($sqlQuery);
        $this->assertEquals(1,$newQueryResult[0]['process_instance_id']);
    }

}