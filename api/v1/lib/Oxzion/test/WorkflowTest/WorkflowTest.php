<?php
namespace WorkflowTest;

use Oxzion\Workflow\WorkflowFactory;
use Oxzion\Utils\RestClient;
use Mockery;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class WorkflowTest extends AbstractHttpControllerTestCase{
    static private $pdo = null;
    public function setUp() : void{
        parent::setUp();
    }

    public function testDeploymentProcess(){
        $workflowFactory = WorkflowFactory::getInstance();
        $processManager = $workflowFactory->getProcessManager();
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
            $mockRestClient->expects('post')->with('process-definition/key/Process_1/tenant-id/1/start')->once()->andReturn(json_encode(array('definitionId'=>12321)));
            $processEngine->setRestClient($mockRestClient);
        }
        $processStart = $processEngine->startProcess('Process_1',1);
        $definitionId = $processStart['definitionId'];
        $this->assertNotEquals(0, $definitionId);
        if(enableCamunda==0){
            $mockRestClient->expects('get')->with('process-definition/12321')->once()->andReturn(json_encode(array('key'=>'Process_1')));
            $processEngine->setRestClient($mockRestClient);
        }
        $processDef = $processEngine->getProcessDefinition($definitionId,1);
        $this->assertEquals($processDef['key'],'Process_1');
        if(enableCamunda==0){
            $mockRestClient->expects('delete')->with('process-definition/12321')->once()->andReturn(0);
            $processEngine->setRestClient($mockRestClient);
        }
        $processDel = $processEngine->stopProcess($definitionId);
        $this->assertEquals($processDel, 1);
        if(enableCamunda==0){
            $mockRestClient->expects('get')->with('deployment/'.$deploymentId)->once()->andReturn(json_encode(array('id'=>$deploymentId)));
            $processManager->setRestClient($mockRestClient);
        }
        $getResponse = $processManager->get($deploymentId);
        $this->assertEquals($getResponse['id'], $deploymentId);
        if(enableCamunda==0){
            $mockRestClient->expects('delete')->with('deployment/'.$deploymentId)->once()->andReturn(0);
            $processManager->setRestClient($mockRestClient);
        }
        $delete = $processManager->remove($deploymentId);
        $this->assertEquals($delete, 1);
    }
}
?>