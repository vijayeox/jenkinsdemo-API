<?php
namespace Workflow;

use Workflow\Controller\ActivityInstanceController;
use Zend\Stdlib\ArrayUtils;
use Oxzion\Test\ControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Adapter\AdapterInterface;
use Mockery;

class ActivityInstanceControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/ActivityInstance.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Workflow.yml");
        return $dataset;
    }

    public function testaddactivityinstance()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'Task_1s7qzh3:8c1318d8-ee65-11e9-bb94-36ce75a0ce0e','activityId'=>1 , 'candidates' => array(array('groupid'=>'HR Group','type'=>'candidate'),array('userid'=>'bharatgtest','type'=>'assignee')),'processInstanceId'=>1,'name'=>'Recruitment Request Created', 'status' => 'Active','taskId'=>1,'processVariables'=>array('workflowId'=>1,'orgid'=>$this->testOrgId)];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/activityinstance', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $sqlQuery1 = "Select * from ox_activity_instance";
        $statement1 = $dbAdapter->query($sqlQuery1);
        $result1 = $statement1->execute();
        $this->assertEquals($result1->count(), 2);
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['workflow_instance_id'], $data['workflow_instance_id']);
        $this->assertEquals($tableFieldName[0]['activity_instance_id'], $data['activityInstanceId']);
        $sqlQuery2 = "Select * from ox_activity_instance_assignee";
        $statement2 = $dbAdapter->query($sqlQuery2);
        $result2 = $statement2->execute();
        $this->assertEquals($result2->count(), 3);
        while ($result2->next()) {
            $tableFieldName1[] = $result2->current();
        }
        $this->assertEquals($tableFieldName1[0]['assignee'], 1);
        $this->assertEquals($tableFieldName1[0]['group_id'], null);
        $this->assertEquals($tableFieldName1[1]['assignee'], 0);
        $this->assertEquals($tableFieldName1[1]['group_id'], 1);
        $this->assertEquals($tableFieldName1[2]['assignee'], 1);
        $this->assertEquals($tableFieldName1[2]['group_id'], null);
    }
    public function testaddactivityinstanceWithoutProcessId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'c99ac426-90ee-11e9-b683-526af7764f64','activityId'=>1 , 'assignee' => 'bharatgtest', 'group_name' => 'HR Group','name'=>'Recruitment Request Created', 'status' => 'Active','taskId'=>1,'processVariables'=>array('workflowId'=>1,'orgid'=>$this->testOrgId)];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/activityinstance', 'POST', $data);
        $this->assertResponseStatusCode(404);
    }
    public function testCompleteinstance()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'[activityInstanceId]','activityId'=>1 , 'assignee' => 'bharatgtest', 'group_name' => 'HR Group','name'=>'Recruitment Request Created', 'status' => 'Active','taskId'=>1,'processVariables'=>array('workflowId'=>1,'orgid'=>$this->testOrgId)];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/complete', 'POST', $data);
        $this->assertResponseStatusCode(404);
    }
    public function testCompleteinstanceWithoutProcessId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'[activityInstanceId]','activityId'=>1 ,'processInstanceId'=>1, 'assignee' => 'bharatgtest', 'group_name' => 'HR Group','name'=>'Recruitment Request Created', 'status' => 'completed','taskId'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/complete', 'POST', $data);
        $this->assertResponseStatusCode(404);
    }
}
