<?php
/**
* File Api
*/
namespace Workflow\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Workflow\WorkFlowFactory;
use Workflow\Model\ActivityInstanceTable;
use Workflow\Model\ActivityInstance;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Zend\Log\Logger;
use Exception;

class ActivityInstanceService extends AbstractService
{
    /**
    * @var ActivityInstanceService Instance of Task Service
    */
    private $activityinstanceService;
    /**
    * @ignore __construct
    */

    public function __construct($config, $dbAdapter, ActivityInstanceTable $table, Logger $log)
    {
        parent::__construct($config, $dbAdapter, $log);
        $this->table = $table;
    }

    public function createActivityInstanceEntry(&$data)
    {
        if(!isset($data['processInstanceId'])){
            return;
        }
        // Org Id from workflow instance based on the Id
        $query = "SELECT * FROM `ox_workflow_instance` WHERE process_instance_id = '".$data['processInstanceId']."';";
        $activityId = null;
        $resultSet = $this->executeQuerywithParams($query)->toArray();

        if(!$resultSet){
            if(isset($data['processVariables'])){
                $variables = $data['processVariables'];
                if(isset($variables['workflow_id']) || isset($variables['workflowId'])){
                    $workflowId = isset($variables['workflow_id'])?$variables['workflow_id']:$variables['workflowId'];
                } else {
                    return 0;
                }
                $workflowInstance = $this->workflowInstanceService->setupWorkflowInstance($workflowId,$data['processInstanceId'],$variables);
                if(isset($data['taskId'])){
                    $activityQuery = "SELECT * FROM `ox_activity` WHERE task_id = '".$data['taskId']."';";
                    $activity = $this->executeQuerywithParams($activityQuery)->toArray();
                    $activityId = $activity[0]['id'];
                }
                if(isset($variables['orgid'])){
                    if ($org = $this->getIdFromUuid('ox_organization', $variables['orgid'])) {
                        $orgId = $org;
                    } else {
                        $orgId = $variables['orgid'];
                    }
                }
                $workflowInstanceId = $workflowInstance['id'];
            }
        } else {
            $workflowInstanceId = $resultSet[0]['id'];
            $orgId = $resultSet[0]['org_id'];
            if(isset($data['taskId'])){
                $activityQuery = "SELECT * FROM `ox_activity` WHERE task_id = '".$data['taskId']."';";
                $activity = $this->executeQuerywithParams($activityQuery)->toArray();
                $activityId = $activity[0]['id'];
            }
        }
        $this->beginTransaction();
        try {
            $activityInstance = array('workflow_instance_id'=>$workflowInstanceId,'activity_id'=>$activityId,'activity_instance_id'=>$data['activityInstanceId'],'status'=>'created','org_id'=>$orgId,'data'=>json_encode($data['processVariables']));

            $activityCreated = $this->createActivityInstance($activityInstance);
            if (isset($data['candidates'])) {
                foreach ($data['candidates'] as $candidate) {
                    $assignee = 0;
                    if(isset($candidate['groupid'])){
                        if($candidate['type']=='assignee'){
                            $assignee = 1;
                        }
                        $groupQuery = $this->executeQuerywithParams("SELECT * FROM `ox_group` WHERE `name` = '".$candidate['groupid']."';")->toArray();
                        if($groupQuery){
                            $insert = "INSERT INTO `ox_activity_instance_assignee` (`activity_instance_id`,`group_id`,`assignee`) VALUES (".$activityInstance['id'].",".$groupQuery[0]['id'].",".$assignee.")";
                             $resultSet = $this->runGenericQuery($insert);
                            unset($resultSet);
                            unset($insert);
                        }
                    }
                    if(isset($candidate['userid'])){
                        if($candidate['type']=='assignee'){
                            $assignee = 1;
                        }
                        $userQuery = $this->executeQuerywithParams("SELECT * FROM `ox_user` WHERE `username` = '".$candidate['userid']."';")->toArray();
                        if($userQuery){
                            $insert = "INSERT INTO `ox_activity_instance_assignee` (`activity_instance_id`,`user_id`,`assignee`) VALUES (".$activityInstance['id'].",".$userQuery[0]['id'].",".$assignee.")";
                            $resultSet = $this->runGenericQuery($insert);
                            unset($resultSet);
                            unset($insert);
                        }
                    }
                }
            }
            $this->commit();
        } catch (Exception $e) {
            $this->logger->info(ActivityInstanceService::class."Creation of Activity Instance Entry Failed".$e->getMessage());
            $this->rollback();
            return 0;
        }
        return $data;
    }

    public function createActivityInstance(&$data)
    {
        $page = new ActivityInstance();
        $data['start_date'] = date('Y-m-d H:i:s');
        $page->exchangeArray($data);
        $page->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($page);
            $select = "SELECT * from ox_activity_instance";
            $result = $this->executeQuerywithParams($select)->toArray();
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            if (!isset($data['id'])) {
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
            }
            $this->commit();
        } catch (Exception $e) {
                $this->rollback();
                return 0;
            }
        return $count;
    }
}
