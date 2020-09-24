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
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'3f6622fd-0124-11ea-a8a0-22e8105c0779','activityId'=>1 , 'candidates' => array(array('groupid'=>'HR Group','type'=>'candidate'),array('userid'=>'admintest','type'=>'assignee')),'processInstanceId'=>'3f20b5c5-0124-11ea-a8a0-22e8105c0778','name'=>'Recruitment Request Created', 'status' => 'Active','taskId'=>"Task_1s7qzh3",'processVariables'=>array('workflowId'=>1,'accountId'=>$this->testAccountUuid)];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/activityinstance', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $sqlQuery1 = "Select * from ox_activity_instance";
        $statement1 = $dbAdapter->query($sqlQuery1);
        $result1 = $statement1->execute();
        $this->assertEquals($result1->count(), 5);
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['workflow_instance_id'], $data['workflow_instance_id']);
        $this->assertEquals($tableFieldName[4]['activity_instance_id'], $data['activityInstanceId']);
        $sqlQuery2 = "Select * from ox_activity_instance_assignee";
        $statement2 = $dbAdapter->query($sqlQuery2);
        $result2 = $statement2->execute();
        $this->assertEquals(4, $result2->count());
        while ($result2->next()) {
            $tableFieldName1[] = $result2->current();
        }
        $this->assertEquals(1, $tableFieldName1[0]['activity_instance_id']);
        $this->assertEquals(1, $tableFieldName1[0]['user_id']);
        $this->assertEquals(1, $tableFieldName1[0]['assignee']);
        $this->assertEquals(null, $tableFieldName1[0]['group_id']);
        $this->assertEquals(null, $tableFieldName1[0]['role_id']);
        $this->assertEquals(3, $tableFieldName1[1]['activity_instance_id']);
        $this->assertEquals(1, $tableFieldName1[1]['user_id']);
        $this->assertEquals(1, $tableFieldName1[1]['assignee']);
        $this->assertEquals(null, $tableFieldName1[1]['group_id']);
        $this->assertEquals(null, $tableFieldName1[1]['role_id']);
        $this->assertEquals($tableFieldName1[2]['activity_instance_id'], $tableFieldName1[3]['activity_instance_id']);
        $this->assertEquals(null, $tableFieldName1[2]['user_id']);
        $this->assertEquals(0, $tableFieldName1[2]['assignee']);
        $this->assertEquals(1, $tableFieldName1[2]['group_id']);
        $this->assertEquals(null, $tableFieldName1[2]['role_id']);
        $this->assertEquals(1, $tableFieldName1[3]['user_id']);
        $this->assertEquals(1, $tableFieldName1[3]['assignee']);
        $this->assertEquals(null, $tableFieldName1[3]['group_id']);
        $this->assertEquals(null, $tableFieldName1[3]['role_id']);
    }
    public function testaddactivityinstanceWithoutProcessId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'c99ac426-90ee-11e9-b683-526af7764f64','activityId'=>1 , 'assignee' => 'admintest', 'group_name' => 'HR Group','name'=>'Recruitment Request Created', 'status' => 'Active','taskId'=>1,'processVariables'=>array('workflowId'=>1,'accountId'=>$this->testAccountUuid)];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/activityinstance', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals('Workflow Instance Id not provided', $content['message']);
    }
    public function testCompleteinstanceWithNoProcessInstanceId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'[activityInstanceId]','activityId'=>1 , 'assignee' => 'admintest', 'group_name' => 'HR Group','name'=>'Recruitment Request Created', 'status' => 'Active','taskId'=>1,'processVariables'=>array('workflowId'=>1,'accountId'=>$this->testAccountUuid)];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflowinstance/complete', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals('Process Instance Id not set', $content['message']);   
    }
    
    public function testCompleteinstance()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'[activityInstanceId]','activityId'=>1 ,'processInstanceId'=>'3f20b5c5-0124-11ea-a8a0-22e8105c0778', 'assignee' => 'admintest', 'group_name' => 'HR Group','name'=>'Recruitment Request Created', 'status' => 'completed','taskId'=>1];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflowinstance/complete', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertEquals('success', $content['status']);
    }
}
