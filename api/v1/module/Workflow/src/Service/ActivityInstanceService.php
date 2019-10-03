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
    private $workflowInstanceService;
    protected $workflowFactory;
    protected $activityEngine;
    /**
    * @ignore __construct
    */

    public function __construct($config, $dbAdapter, ActivityInstanceTable $table,WorkflowInstanceService $workflowInstanceService,
        WorkflowFactory $workflowFactory, Logger $log)
    {
        parent::__construct($config, $dbAdapter, $log);
        $this->table = $table;
        $this->workflowInstanceService = $workflowInstanceService;
        $this->workFlowFactory = $workflowFactory;
        $this->activityEngine = $this->workFlowFactory->getActivity();
    }

    public function setActivityEngine($activityEngine)
    {
        $this->activityEngine = $activityEngine;
    }
    public function getActivityInstanceForm($data)
    {
        $activityQuery = "SELECT ox_activity_instance.*,ox_activity.task_id as task_id,
        ox_form.template as template,ox_activity.workflow_id FROM `ox_activity_instance` 
        LEFT JOIN ox_activity on ox_activity.id = ox_activity_instance.activity_id 
        LEFT JOIN ox_activity_form on ox_activity.id=ox_activity_form.activity_id 
        LEFT JOIN ox_form on ox_form.id=ox_activity_form.form_id 
        LEFT JOIN ox_workflow_instance on ox_workflow_instance.id = ox_activity_instance.workflow_instance_id
        WHERE ox_activity_instance.org_id =:orgId AND ox_workflow_instance.app_id=:appId AND 
        ox_activity_instance.activity_instance_id=:activityInstanceId;";
        $activityParams = array("orgId" => AuthContext::get(AuthConstants::ORG_ID),"appId" => $this->getIdFromUuid('ox_app', $data['appId']),"activityInstanceId" => $data['activityInstanceId']);
        $activityInstance = $this->executeQuerywithBindParameters($activityQuery,$activityParams)->toArray();
        if (count($activityInstance)==0) {
            return 0;
        }
        return $activityInstance[0];
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
            throw $e;
        }
        return $count;
    }
    public function claimActivityInstance($data){
        $activityQuery = "SELECT ox_activity_instance.*,ox_activity.task_id as task_id 
        FROM `ox_activity_instance` 
        LEFT JOIN ox_activity on ox_activity.id = ox_activity_instance.activity_id 
        LEFT JOIN ox_workflow_instance on ox_workflow_instance.id = ox_activity_instance.workflow_instance_id
        WHERE ox_activity_instance.org_id =:orgId 
        AND ox_workflow_instance.app_id=:appId
        AND ox_activity_instance.activity_instance_id=:activityInstanceId;";   
        $activityParams = array("orgId" => AuthContext::get(AuthConstants::ORG_ID),"appId" => $this->getIdFromUuid('ox_app', $data['appId']),"activityInstanceId" => $data['activityInstanceId']);
        $activityInstance = $this->executeQuerywithBindParameters($activityQuery,$activityParams)->toArray();
        if(isset($activityInstance)&&is_array($activityInstance) && !empty($activityInstance)){
            $taskId = str_replace($activityInstance[0]["task_id"].":", "", $activityInstance[0]['activity_instance_id']);
        } else {
            return 0;
        }
        try {
            $test = $this->activityEngine->claimActivity($taskId,AuthContext::get(AuthConstants::USERNAME));
        } catch(Exception $e){ 
            throw $e;
        }
        $selectQuery = "SELECT * FROM `ox_activity_instance_assignee` 
        WHERE activity_instance_id=:activityInstanceId and user_id=:userId;";
        $selectParams = array("userId" => AuthContext::get(AuthConstants::USER_ID),"activityInstanceId" => $data['activityInstanceId']);
        $activityInstanceAssignee = $this->executeQuerywithBindParameters($selectQuery,$selectParams)->toArray();
        if(isset($activityInstanceAssignee) && is_array($activityInstanceAssignee) && !empty($activityInstanceAssignee)){
            $this->beginTransaction();
            try {
                $updateQuery = "UPDATE ox_activity_instance_assignee SET assignee = :assignee
                where id = :id;";
                $updateParams = array("assignee" => 1,"id" => $activityInstanceAssignee[0]['id']);
                $update = $this->executeQuerywithBindParameters($updateQuery,$updateParams);
                $this->commit();
            } catch (Exception $e) {
                $this->logger->info(ActivityInstanceService::class."Creation of Activity Instance Entry Failed".$e->getMessage());
                $this->rollback();
                throw $e;
            }
        } else {
            $selectQuery = "SELECT * FROM ox_activity_instance 
            where activity_instance_id =:activityInstanceId
            AND org_id=:orgId;";
            $selectParams = array("activityInstanceId" => $data['activityInstanceId'], "orgId" => AuthContext::get(AuthConstants::ORG_ID));
            $resultSelect = $this->executeQuerywithBindParameters($selectQuery,$selectParams)->toArray();
            $insert = "INSERT INTO `ox_activity_instance_assignee` (`activity_instance_id`,`user_id`,`assignee`) 
            VALUES (:activityInstanceId,:userId,:assignee)";
            $insertParams = array("activityInstanceId" => $resultSelect[0]['id'], "userId" => AuthContext::get(AuthConstants::USER_ID), "assignee" => 1);
            $resultSet = $this->executeQuerywithBindParameters($insert,$insertParams);
        }
        return 1;
    }
    public function createActivityInstanceEntry(&$data)
    {
        if(!isset($data['processInstanceId'])){
            return;
        }
        // Org Id from workflow instance based on the Id
        $query = "SELECT * FROM `ox_workflow_instance` WHERE process_instance_id = :processInstanceId;";
        $queryParams = array("processInstanceId" => $data['processInstanceId']);
        $activityId = null;
        $resultSet = $this->executeQuerywithBindParameters($query,$queryParams)->toArray();
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
                    $activityQuery = "SELECT * FROM `ox_activity` WHERE task_id = :taskId;";
                    $activityParams = array("taskId" => $data['taskId']);
                    $activity = $this->executeQuerywithBindParameters($activityQuery,$activityParams)->toArray();
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
                $activityQuery = "SELECT * FROM `ox_activity` WHERE task_id = :taskId;";
                $activityParams = array("taskId" => $data['taskId']);
                $activity = $this->executeQuerywithBindParameters($activityQuery,$activityParams)->toArray();
                $activityId = $activity[0]['id'];
            }
        }
        $this->beginTransaction();
        try {
            $activityInstance = array('workflow_instance_id'=>$workflowInstanceId,'activity_id'=>$activityId,'activity_instance_id'=>$data['activityInstanceId'],'status'=>'In Progress','org_id'=>$orgId,'data'=>json_encode($data['processVariables']));
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
                            $insert = "INSERT INTO `ox_activity_instance_assignee` (`activity_instance_id`,`group_id`,`assignee`) 
                            VALUES (:activityInstanceId,:groupId,:assignee)";
                            $insertParams = array("activityInstanceId" => $activityInstance['id'],"groupId" => $groupQuery[0]['id'],"assignee" => $assignee);
                            $resultSet = $this->executeQuerywithBindParameters($insert,$insertParams);
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
                            $insert = "INSERT INTO `ox_activity_instance_assignee` (`activity_instance_id`,`user_id`,`assignee`) 
                            VALUES (:activityInstanceId,:userId,:assignee)";
                            $insertParams = array("activityInstanceId" => $activityInstance['id'],"userId" => $userQuery[0]['id'],"assignee" => $assignee);
                            $resultSet = $this->executeQuerywithBindParameters($insert,$insertParams);
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
            throw $e;
        }
        return $data;
    }
    public function completeActivityInstance(&$data)
    {
        $query = "SELECT * FROM `ox_workflow_instance` WHERE process_instance_id = :processInstanceId;";
        $queryParams = array("processInstanceId" => $data['processInstanceId']);
        $activityId = null;
        $resultSet = $this->executeQuerywithBindParameters($query,$queryParams)->toArray();
        if($resultSet){
            $workflowInstanceId = $resultSet[0]['id'];
        } else {
            return 0;
        }
        // Org Id from workflow instance based on the Id
        if(isset($data['processVariables'])){
            $variables = $data['processVariables'];
            if(isset($variables['workflow_id']) || isset($variables['workflowId'])){
                $workflowId = isset($variables['workflow_id'])?$variables['workflow_id']:$variables['workflowId'];
            } else {
                return 0;
            }
            if(isset($data['taskId'])){
                $activityQuery = "SELECT * FROM `ox_activity` WHERE task_id = :taskId;";
                $queryParams = array("taskId" => $data['taskId']);
                $activity = $this->executeQuerywithBindParameters($activityQuery,$queryParams)->toArray();
                $activityId = $activity[0]['id'];
            }
            if(isset($variables['orgid'])){
                if ($org = $this->getIdFromUuid('ox_organization', $variables['orgid'])) {
                    $orgId = $org;
                } else {
                    $orgId = $variables['orgid'];
                }
            }
        }
        $selectQuery = "SELECT * FROM `ox_activity_instance`
        WHERE activity_id =:activityId and activity_instance_id=:activityInstanceId
        and workflow_instance_id=:workflowInstanceId;";
        $selectParams = array("activityId" => $activityId, "activityInstanceId" => $data['activityInstanceId'], "workflowInstanceId" => $workflowInstanceId);
        $activityInstance = $this->executeQuerywithBindParameters($selectQuery,$selectParams)->toArray();
        if(!empty($activityInstance)){
            $this->beginTransaction();
            try {
                $updateQuery = "UPDATE ox_activity_instance SET status =:instanceStatus where id =:activityInstanceId ;";
                $updateParams = array("instanceStatus" => 'completed',"activityInstanceId" => $activityInstance[0]['id']);
                $update = $this->executeQuerywithBindParameters($updateQuery,$updateParams);
                $this->commit();
            } catch (Exception $e) {
                $this->logger->info(ActivityInstanceService::class."Creation of Activity Instance Entry Failed".$e->getMessage());
                $this->rollback();
                throw $e;
            }
        }
        return $activityInstance;
    }
}
