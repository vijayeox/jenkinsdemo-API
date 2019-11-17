<?php
/**
* File Api
*/
namespace Workflow\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Workflow\WorkFlowFactory;
use Workflow\Model\ActivityInstanceTable;
use Oxzion\Model\ActivityInstance;
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
            $insert = "INSERT INTO `ox_activity_instance` (`workflow_instance_id`,`activity_id`,`activity_instance_id`,`assignee`,`group_id`,`status`,`start_date`,`org_id`) VALUES ('" .$workflowInstanceId."','".$activityId."','" .$data['activityInstanceId']."','" .$data['assignee']."','" .$data['group_id']."','created',now(),'" .$orgId."');";
            $resultSet = $this->runGenericQuery($insert);
            $this->commit();
        } catch (Exception $e) {
            $this->logger->info(ActivityInstanceService::class."Creation of Activity Instance Entry Failed".$e->getMessage());
            $this->rollback();
            return 0;
        }
        return $data;
    }
    public function completeActivityInstance(&$data)
    {
        $query = "SELECT * FROM `ox_workflow_instance` WHERE process_instance_id = '".$data['processInstanceId']."';";
        $activityId = null;
        $resultSet = $this->executeQuerywithParams($query)->toArray();
        if($resultSet){
            $workflowInstanceId = $resultSet[0]['id'];
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
        }
        $selectQuery = "SELECT * FROM `ox_activity_instance` WHERE activity_id = '".$activityId."' and activity_instance_id='".$data['activityInstanceId']."' and workflow_instance_id=".$workflowInstanceId.";";
        $activityInstance = $this->executeQuerywithParams($selectQuery)->toArray();
        $this->beginTransaction();
        try {
            $updateQuery = "UPDATE ox_activity_instance SET status = 'completed' where id = ".$activityInstance[0]['id'].";";
            $update = $this->executeQuerywithParams($updateQuery);
            $this->commit();
        } catch (Exception $e) {
            $this->logger->info(ActivityInstanceService::class."Creation of Activity Instance Entry Failed".$e->getMessage());
            $this->rollback();
            return 0;
        }
        return $activityInstance;
    }
}
