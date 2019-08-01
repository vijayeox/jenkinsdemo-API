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

class ActivityInstanceControllerTest extends ControllerTest{
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/ActivityInstance.yml");
        return $dataset;
    }

    public function testaddactivityinstance() {
        $this->initAuthToken($this->adminUser);
        $data = ['workflow_instance_id' => 1, 'activityInstanceId' =>'c99ac426-90ee-11e9-b683-526af7764f64','activityId'=>1 , 'assignee' => 'bharatgtest', 'group_name' => 'HR Group','processInstanceId'=>1,'name'=>'Recruitment Request Created', 'status' => 'Active'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/activityinstance','POST',$data);
        $this->assertResponseStatusCode(200);
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $sqlQuery1 = "Select * from ox_activity_instance";
        $statement1 = $dbAdapter->query($sqlQuery1);
        $result1 = $statement1->execute();
        $this->assertEquals($result1->count(),1);
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['workflow_instance_id'],$data['workflow_instance_id']);
        $this->assertEquals($tableFieldName[0]['activity_instance_id'],$data['activityInstanceId']);
        $this->assertEquals($tableFieldName[0]['group_id'],1);
    }
}
