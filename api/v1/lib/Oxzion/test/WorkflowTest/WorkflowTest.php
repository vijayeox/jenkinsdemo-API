<?php
namespace WorkflowTest;

use Oxzion\Workflow\WorkflowFactory;
use Oxzion\Utils\RestClient;
use Mockery;
use PHPUnit\Framework\TestCase;

class WorkflowTest extends TestCase{

    public function testDeploymentProcess(){
        $workflowFactory = WorkflowFactory::getInstance();
        $processManager = $workflowFactory->getProcessManager();
        $processEngine = $workflowFactory->getProcessEngine();
        if(enableCamunda==0){
            $mockRestClient = Mockery::mock('RestClient');
            $mockRestClient->expects('postMultiPart')->with("deployment/create",array("deployment-name"=>'TestProcess1'),array(__DIR__."/Dataset/testwithparams.bpmn"))->once()->andReturn('{"tenantId":null,"deployedProcessDefinitions":{"main:2:2849099d-9e78-11e9-a32f-3efeb18f8381":{"id":"main:2:2849099d-9e78-11e9-a32f-3efeb18f8381","key":"main","category":"http://camunda.org/schema/1.0/bpmn","description":null,"name":"Task Form","version":2,"resource":"ScriptTaskTest.bpmn","deploymentId":"2846989b-9e78-11e9-a32f-3efeb18f8381","diagram":null,"suspended":false,"tenantId":null,"versionTag":null,"historyTimeToLive":null}},"deployedCaseDefinitions":null,"deployedDecisionDefinitions":null,"deployedDecisionRequirementsDefinitions":null}');
            $processManager->setRestClient($mockRestClient);
        }
        $data = $processManager->deploy('TestProcess1',array(__DIR__."/Dataset/testwithparams.bpmn"));
        $this->assertNotEquals(0, $data);
        $deploymentId = $data['id'];
        if(enableCamunda==0){
            $mockRestClient->expects('post')->with('process-definition/12321/start')->once()->andReturn(json_encode(array('definitionId'=>12321)));
            $processEngine->setRestClient($mockRestClient);
        }
        $processStart = $processEngine->startProcess(12321);
        $definitionId = $processStart['definitionId'];
        $this->assertNotEquals(0, $definitionId);
        if(enableCamunda==0){
            $mockRestClient->expects('get')->with('process-definition/12321')->once()->andReturn(json_encode(array('key'=>'Process_1')));
            $processEngine->setRestClient($mockRestClient);
        }
        $processDef = $processEngine->getProcessDefinition($definitionId,1);
        $this->assertEquals($processDef['key'],'Process_1');
        if(enableCamunda==0){
            $mockRestClient->expects('delete')->with('process-definition/12321?cascade=true')->once()->andReturn(0);
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
// Check this
    public function testAssigneeProcess(){
        $workflowFactory = WorkflowFactory::getInstance();
        $processManager = $workflowFactory->getProcessManager();
        $processEngine = $workflowFactory->getProcessEngine();
        $activityManager = $workflowFactory->getActivity();
        if(enableCamunda==0){
            $mockRestClient = Mockery::mock('RestClient');
            $mockRestClient->expects('postMultiPart')->with("deployment/create",array("deployment-name"=>'TestProcess1'),array(__DIR__."/Dataset/testwithparams.bpmn"))->once()->andReturn('{"tenantId":null,"deployedProcessDefinitions":{"main:2:2849099d-9e78-11e9-a32f-3efeb18f8381":{"id":"main:2:2849099d-9e78-11e9-a32f-3efeb18f8381","key":"main","category":"http://camunda.org/schema/1.0/bpmn","description":null,"name":"Task Form","version":2,"resource":"ScriptTaskTest.bpmn","deploymentId":"2846989b-9e78-11e9-a32f-3efeb18f8381","diagram":null,"suspended":false,"tenantId":null,"versionTag":null,"historyTimeToLive":null}},"deployedCaseDefinitions":null,"deployedDecisionDefinitions":null,"deployedDecisionRequirementsDefinitions":null}');
            $processManager->setRestClient($mockRestClient);
        }
        $data = $processManager->deploy('TestProcess1',array(__DIR__."/Dataset/testwithparams.bpmn"));
        $this->assertNotEquals(0, $data);
        $deploymentId = $data['id'];
        if(enableCamunda==0){
            $mockRestClient->expects('post')->with('process-definition/12321/start')->once()->andReturn(json_encode(array('definitionId'=>12321)));
            $processEngine->setRestClient($mockRestClient);
        }
        $processStart = $processEngine->startProcess(12321);
        $definitionId = $processStart['definitionId'];
        $this->assertNotEquals(0, $definitionId);
        if(enableCamunda==0){
            $mockRestClient->expects('get')->withArgs(array('task?'.http_build_query(array("assignee"=>2))))->once()->andReturn(json_encode(array('id'=>123321)));
            $activityManager->setRestClient($mockRestClient);
        }
        $activityList = $activityManager->getActivitiesByUser(2);
        $this->assertNotEquals(0, count($activityList));
        if(enableCamunda==0){
            $mockRestClient->expects('delete')->with('deployment/'.$deploymentId)->once()->andReturn(0);
            $processManager->setRestClient($mockRestClient);
        }
        $delete = $processManager->remove($deploymentId);
        $this->assertEquals($delete, 1);
    }
    public function testAssignGroupProcess(){
        $workflowFactory = WorkflowFactory::getInstance();
        $processManager = $workflowFactory->getProcessManager();
        $processEngine = $workflowFactory->getProcessEngine();
        $activityManager = $workflowFactory->getActivity();
        if(enableCamunda==0){
            $mockRestClient = Mockery::mock('RestClient');
            $mockRestClient->expects('postMultiPart')->with("deployment/create",array("deployment-name"=>'TestProcess1'),array(__DIR__."/Dataset/testAssigntoGroup.bpmn"))->once()->andReturn('{"tenantId":null,"deployedProcessDefinitions":{"main:2:2849099d-9e78-11e9-a32f-3efeb18f8381":{"id":"main:2:2849099d-9e78-11e9-a32f-3efeb18f8381","key":"main","category":"http://camunda.org/schema/1.0/bpmn","description":null,"name":"Task Form","version":2,"resource":"ScriptTaskTest.bpmn","deploymentId":"2846989b-9e78-11e9-a32f-3efeb18f8381","diagram":null,"suspended":false,"tenantId":null,"versionTag":null,"historyTimeToLive":null}},"deployedCaseDefinitions":null,"deployedDecisionDefinitions":null,"deployedDecisionRequirementsDefinitions":null}');
            $processManager->setRestClient($mockRestClient);
        }
        $data = $processManager->deploy('TestProcess1',array(__DIR__."/Dataset/testAssigntoGroup.bpmn"));
        $this->assertNotEquals(0, $data);
        $deploymentId = $data['id'];
        if(enableCamunda==0){
            $mockRestClient->expects('post')->with('process-definition/12321/start')->once()->andReturn(json_encode(array('definitionId'=>12321)));
            $processEngine->setRestClient($mockRestClient);
        }
        $processStart = $processEngine->startProcess(12321);
        $definitionId = $processStart['definitionId'];
        $processId = $processStart['id'];
        // print_r($processStart);exit;
        $this->assertNotEquals(0, $definitionId);
        if(enableCamunda==0){
            $mockRestClient->expects('post')->with('task',array('candidateGroup'=>1))->once()->andReturn(json_encode(array(array('id'=>12321))));
            $activityManager->setRestClient($mockRestClient);
        }
        $activityList = $activityManager->getActivitiesByGroup(1);
        $this->assertNotEquals(0, count($activityList));
        $activityId = end($activityList)['id'];
        if(enableCamunda==0){
            $mockRestClient->expects('post')->withArgs(array('task/12321/claim', array('userId'=>1)))->once()->andReturn(json_encode(array('id'=>12321)));
            $activityManager->setRestClient($mockRestClient);
        }
        $claimActivityResponse = $activityManager->claimActivity($activityId,1);
        if(enableCamunda==0){
            $mockRestClient->expects('get')->with('task/12321')->once()->andReturn(json_encode(array('assignee'=>1)));
            $activityManager->setRestClient($mockRestClient);
        }
        $activityInfo = $activityManager->getActivity($activityId);
        $this->assertEquals(1, $activityInfo['assignee']);
        if(enableCamunda==0){
            $mockRestClient->expects('post')->withArgs(array('task/12321/resolve',array()))->once()->andReturn(json_encode(array()));
            $activityManager->setRestClient($mockRestClient);
        }
        $activityManager->resolveActivity($activityId);
        if(enableCamunda==0){
            $mockRestClient->expects('get')->with('task/12321')->once()->andReturn(json_encode(array('delegationState'=>'RESOLVED')));
            $activityManager->setRestClient($mockRestClient);
        }
        $resolveActivityInfo = $activityManager->getActivity($activityId);
        $this->assertEquals($resolveActivityInfo['delegationState'],'RESOLVED');
        if(enableCamunda==0){
            $mockRestClient->expects('post')->withArgs(array('task/12321/unclaim', array('userId'=>1)))->once()->andReturn(json_encode(array('id'=>12321)));
            $activityManager->setRestClient($mockRestClient);
        }
        $unclaimActivityResponse = $activityManager->unclaimActivity($activityId,1);
        if(enableCamunda==0){
            $mockRestClient->expects('get')->with('task/12321')->once()->andReturn(json_encode(array('assignee'=>NULL)));
            $activityManager->setRestClient($mockRestClient);
        }
        $activityInfo = $activityManager->getActivity($activityId);
        $this->assertNotEquals(1, $activityInfo['assignee']);
        if(enableCamunda==0){
            $mockRestClient->expects('delete')->with('process-definition/12321?cascade=true')->once()->andReturn(0);
            $activityManager->setRestClient($mockRestClient);
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

    public function testBPMNParsing(){
        $workflowFactory = WorkflowFactory::getInstance();
        $processManager = $workflowFactory->getProcessManager();
        $processEngine = $workflowFactory->getProcessEngine();
        $data = $processManager->parseBPMN(__DIR__."/Dataset/ScriptTaskTest.bpmn",1,1);
        $this->assertEquals($data[0]['form']['name'],'StartEvent_1');
        $this->assertEquals($data[0]['form']['app_id'],1);
        $this->assertEquals(count($data[0]['fields']),1);
        $this->assertEquals($data[0]['fields'][0]['name'],'FormField_3oot72p');
        $this->assertEquals($data[0]['fields'][0]['text'],'New Field');
        $this->assertEquals($data[0]['fields'][0]['data_type'],'string');
        $this->assertEquals($data[0]['start_form'],'StartEvent_1');
        $this->assertEquals(count($data[1]['fields']),3);
        $this->assertEquals($data[1]['activity']['activity_id'],'UserTask_1');
        $this->assertEquals($data[1]['activity']['app_id'],1);
        $this->assertEquals($data[1]['fields'][0]['name'],'a_val');
        $this->assertEquals($data[1]['fields'][0]['text'],'A Value');
        $this->assertEquals($data[1]['fields'][0]['constraints'],'[{"name":"required","config":"true"}]');
        $this->assertNotEquals(0, $data);
    }
}
?>