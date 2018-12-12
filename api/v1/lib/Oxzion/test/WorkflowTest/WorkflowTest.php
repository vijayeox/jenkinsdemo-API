<?php
namespace WorkflowTest;
use Oxzion\Test\ControllerTest;
use Oxzion\Workflow\ProcessManager;
use Oxzion\Workflow\WorkflowFactory;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Workflow\Camunda\Config;
use Oxzion\Workflow\Camunda\ProcessManagerImpl;
use Oxzion\Utils\RestClient;
use Mockery;

class WorkflowTest extends ControllerTest{
    use TestCaseTrait;
    static private $pdo = null;
    private $processEngineDb = 'process-engine';
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/Dataset/Workflow.yml");
        return $dataset;
    }
    public function getConnection() {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                $config = $this->getApplicationConfig();
                $config = $config['db'];
                $originalDbName = $config['database'];
                $config['database'] = $this->processEngineDb;
                $config['dsn'] = str_replace($originalDbName, $this->processEngineDb, $config['dsn']);
                self::$pdo = new \PDO( $config['dsn'], $config['username'], $config['password'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $config['database']);
        }
        return $this->conn;
    }
    public function testDeploymentProcess(){
      $workflowFactory = WorkflowFactory::getInstance();
      $processManager  = $workflowFactory->getProcessManager();
      $processEngine = $workflowFactory->getProcessEngine();
      if(enableCamunda==0){
        $mockRestClient = Mockery::mock('RestClient');
        $mockRestClient->expects('postMultiPart')->with("deployment/create",array("deployment-name"=>'TestProcess1',"tenant-id"=>1),array(__DIR__."/Dataset/testwithparams.bpmn"))->once()->andReturn(json_encode(array('id'=>1,'name'=>"TestProcess1","tenantId"=>1)));
        $processManager->setRestClient($mockRestClient);
    }
    $data = $processManager->deploy(1,'TestProcess1',array(__DIR__."/Dataset/testwithparams.bpmn"));
    $this->assertNotEquals(0, $data);
    $deploymentId = $data['id'];
    if(enableCamunda==0){
        $mockRestClient = Mockery::mock('RestClient');
        $mockRestClient->expects('post')->with('process-definition/key/Process_1/tenant-id/1/start',null)->once()->andReturn(json_encode(array('definitionId'=>12321)));
        $processManager->setRestClient($mockRestClient);
    }
    $processStart = $processEngine->startProcess('Process_1',1);
    $definitionId = $processStart['definitionId'];
    $this->assertNotEquals(0, $definitionId);
    if(enableCamunda==0){
        $mockRestClient = Mockery::mock('RestClient');
        $mockRestClient->expects('get')->with('process-definition/12321',null)->once()->andReturn(json_encode(array('key'=>'Process_1')));
        $processManager->setRestClient($mockRestClient);
    }
    $processDef = $processEngine->getProcessDefinition($definitionId,1);
    $this->assertEquals($processDef['key'],'Process_1');
    if(enableCamunda==0){
        $mockRestClient = Mockery::mock('RestClient');
        $mockRestClient->expects('delete')->with('process-definition/12321')->once()->andReturn(0);
        $processManager->setRestClient($mockRestClient);
    }
    $processDel = $processEngine->stopProcess($definitionId);
    $this->assertEquals($processDel, 1);
    if(enableCamunda==0){
        $mockRestClient = Mockery::mock('RestClient');
        $mockRestClient->expects('get')->with('deployment/'.$deploymentId)->once()->andReturn(json_encode(array('id'=>$deploymentId)));
        $processManager->setRestClient($mockRestClient);
    }
    $getResponse = $processManager->get($deploymentId);
    $this->assertEquals($getResponse['id'], $deploymentId);
    if(enableCamunda==0){
        $mockRestClient = Mockery::mock('RestClient');
        $mockRestClient->expects('delete')->with('deployment/'.$deploymentId)->once()->andReturn(0);
        $processManager->setRestClient($mockRestClient);
    }
    $delete = $processManager->remove($deploymentId);
    $this->assertEquals($delete, 1);
}


}
?>