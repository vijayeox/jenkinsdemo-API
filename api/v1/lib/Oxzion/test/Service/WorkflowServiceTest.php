<?php
namespace Oxzion\Service;

use Oxzion\Test\AbstractServiceTest;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Symfony\Component\Yaml\Yaml;
use Oxzion\ServiceException;
use Oxzion\ValidationException;
use Oxzion\EntityNotFoundException;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use \Exception;
use Mockery;
use Zend\Db\ResultSet\ResultSet;
use Oxzion\Workflow\WorkflowFactory;
use Oxzion\Utils\RestClient;

class WorkflowServiceTest extends AbstractServiceTest{
    public $adapter = null;

    protected function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
        $this->workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowService::class);
        AuthContext::put(AuthConstants::ACCOUNT_ID, 1);
        AuthContext::put(AuthConstants::ACCOUNT_UUID, '53012471-2863-4949-afb1-e69b0891c98a');
        AuthContext::put(AuthConstants::USER_ID, 1);
        $this->adapter = $this->getDbAdapter();
        $this->adapter->getDriver()->getConnection()->setResource(static::$pdo);
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/Dataset/File.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../../../../module/User/test/Dataset/User.yml");
        return $dataset;
    }

    private function runQuery($query) {
        $statement = $this->adapter->query($query);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $result = $resultSet->initialize($result)->toArray();
        return $result;
    }

    public function testDeploy(){
        $file = __DIR__."/../WorkflowTest/Dataset/testwithparams.bpmn";
        $entityId = 1;
        $appId = "1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4";
        $data = array("name" => "TestProcess1");
        $workflowFactory = WorkflowFactory::getInstance($this->applicationConfig);
        $processManager = $workflowFactory->getProcessManager();
        $returnData = '{"tenantId":null,"deployedProcessDefinitions":{"main:2:2849099d-9e78-11e9-a32f-3efeb18f8381":{"id":"main:2:2849099d-9e78-11e9-a32f-3efeb18f8381","key":"main","category":"http://camunda.org/schema/1.0/bpmn","description":null,"name":"Task Form","version":2,"resource":"testwithparams.bpmn","deploymentId":"2846989b-9e78-11e9-a32f-3efeb18f8381","diagram":null,"suspended":false,"tenantId":null,"versionTag":null,"historyTimeToLive":null}},"deployedCaseDefinitions":null,"deployedDecisionDefinitions":null,"deployedDecisionRequirementsDefinitions":null}';
        if (enableCamunda==0) {
            $mockRestClient = Mockery::mock('RestClient');
            $mockRestClient->expects('postMultiPart')->with("deployment/create", array("deployment-name"=>$data['name']), array($file))->once()->andReturn($returnData);
            $processManager->setRestClient($mockRestClient);
            $this->workflowService->setProcessManager($processManager);
        }

        $result = $this->workflowService->deploy($file, $appId, $data, $entityId);
        $this->assertEquals(99, $result['app_id']);
        $this->assertEquals($data['name'], $result['name']);
        $returnData = json_decode($returnData, true)['deployedProcessDefinitions']['main:2:2849099d-9e78-11e9-a32f-3efeb18f8381'];
        $this->assertEquals($returnData['key'], $result['process_id']);
        $this->assertEquals($returnData['id'], $result['process_definition_id']);
        $this->assertEquals($entityId, $result['entity_id']);
        $this->assertEquals($file, $result['file']);
        $query = "select w.*, wd.id as workflow_deployment_id, wd.process_definition_id, wd.fields from ox_workflow w
                    inner join ox_workflow_deployment wd on wd.workflow_id = w.id 
                    where uuid = '".$result['uuid']."' and wd.latest = 1";
        $queryResult = $this->runQuery($query);
        $this->assertEquals(1, count($queryResult));
        $queryResult = $queryResult[0];
        $this->assertEquals($queryResult['id'], $result['id']);
        $this->assertEquals($queryResult['modified_by'], $result['modified_by']);
        $this->assertEquals($queryResult['created_by'], $result['created_by']);
        $this->assertEquals($queryResult['date_modified'], $result['date_modified']);
        $this->assertEquals($queryResult['date_created'], $result['date_created']);
        $this->assertEquals($queryResult['workflow_deployment_id'], $result['workflow_deployment_id']);
        $this->assertEquals($queryResult['process_definition_id'], $result['process_definition_id']);
        $this->assertEquals($queryResult['fields'], $result['fields']);
    }
}