<?php
/**
 * File Api
 */
namespace Oxzion\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\EntityNotFoundException;
use Oxzion\Model\ActivityInstance;
use Oxzion\Model\ActivityInstanceTable;
use Oxzion\Service\AbstractService;
use Oxzion\Workflow\WorkFlowFactory;

class ActivityInstanceService extends AbstractService
{
    /**
     * @var ActivityInstanceService Instance of Task Service
     */
    private $fileExt = ".json";
    protected $workflowFactory;
    protected $activityEngine;
    /**
     * @ignore __construct
     */

    public function __construct($config, $dbAdapter, ActivityInstanceTable $table, WorkflowFactory $workflowFactory)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->formsFolder = $this->config['FORM_FOLDER'];
        $this->workFlowFactory = $workflowFactory;
        $this->activityEngine = $this->workFlowFactory->getActivity();
    }

    public function setActivityEngine($activityEngine)
    {
        $this->activityEngine = $activityEngine;
    }
    public function getActivityInstanceForm($data)
    {
        $selectQuery = "SELECT oxa.* from ox_activity_instance_assignee as oxa join ox_activity_instance as oxi on oxa.activity_instance_id = oxi.id Left JOIN ox_user_group as oug on oxa.group_id = oug.group_id WHERE oxi.status = 'In Progress' and oxi.activity_instance_id =:activityInstanceId AND (oxa.user_id =:userId OR oug.avatar_id =:userId)";
        $queryParams = array("activityInstanceId" => $data['activityInstanceId'], 'userId' => AuthContext::get(AuthConstants::USER_ID));
        $result = $this->executeQuerywithBindParameters($selectQuery, $queryParams)->toArray();
        if (isset($result[0])) {
            $activityQuery = "SELECT ox_workflow_instance.process_instance_id as workflow_instance_id,
            ox_activity_instance.activity_instance_id,
            ox_activity_instance.status as status,
            ox_file.data,ox_app.uuid as app_id,ox_file.uuid,
            ox_activity_instance.org_id,ox_activity_instance.activity_id,ox_form.uuid as form_id,ox_activity.task_id as task_id,
            ox_form.name as formName FROM `ox_activity_instance`
            LEFT JOIN ox_activity on ox_activity.id = ox_activity_instance.activity_id
            LEFT JOIN ox_activity_form on ox_activity.id=ox_activity_form.activity_id
            LEFT JOIN ox_form on ox_form.id=ox_activity_form.form_id
            LEFT JOIN ox_workflow_instance on ox_workflow_instance.id = ox_activity_instance.workflow_instance_id
            LEFT JOIN ox_file on ox_file.id=ox_workflow_instance.file_id
            LEFT JOIN ox_app on ox_app.id = ox_form.app_id
            WHERE ox_activity_instance.org_id =:orgId AND ox_workflow_instance.app_id=:appId AND
            ox_activity_instance.activity_instance_id=:activityInstanceId;";
            $activityParams = array("orgId" => AuthContext::get(AuthConstants::ORG_ID), "appId" => $this->getIdFromUuid('ox_app', $data['appId']), "activityInstanceId" => $data['activityInstanceId']);
            $activityInstance = $this->executeQuerywithBindParameters($activityQuery, $activityParams)->toArray();
            if (count($activityInstance) == 0) {
                return 0;
            }

            $filePath = $this->formsFolder . $data['appId'] . "/" . $activityInstance[0]['formName'] . $this->fileExt;
            if (file_exists($filePath)) {
                $activityInstance[0]['template'] = file_get_contents($filePath);
            }

            $activityform = $activityInstance[0];
            $data = json_decode($activityform['data'], true);
            $data['fileId'] = $activityform['uuid'];
            unset($activityform['uuid']);
            $activityform['data'] = json_encode($data);
            return $activityform;
        } else {
            throw new EntityNotFoundException("Do not have access to this form");
        }
    }
    public function createActivityInstance(&$data)
    {
        $activityInstance = new ActivityInstance();
        $data['start_date'] = date('Y-m-d H:i:s');
        $this->logger->info("ActivityInstance BEFCHANGE" . print_r($data, true));
        $activityInstance->exchangeArray($data);
        $activityInstance->validate();
        $this->logger->info("ActivityInstance AFTERFCHANGE" . print_r($activityInstance, true));
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($activityInstance);
            $this->logger->info("ActivityInstance CREATED");
            if ($count == 0) {
                $this->logger->info("ActivityInstance ROLLBACK");
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
    public function claimActivityInstance($data)
    {
        $activityQuery = "SELECT ox_activity_instance.*,ox_activity.task_id as task_id
        FROM `ox_activity_instance`
        INNER JOIN ox_activity on ox_activity.id = ox_activity_instance.activity_id
        INNER JOIN ox_workflow_instance on ox_workflow_instance.id = ox_activity_instance.workflow_instance_id
        INNER JOIN ox_app on ox_app.id = ox_workflow_instance.app_id
        WHERE ox_activity_instance.org_id =:orgId
        AND ox_app.uuid=:appId
        AND ox_activity_instance.activity_instance_id=:activityInstanceId;";
        $activityParams = array("orgId" => AuthContext::get(AuthConstants::ORG_ID),
            "appId" => $data['appId'],
            "activityInstanceId" => $data['activityInstanceId']);
        $this->logger->info("Executing query - $activityQuery with params " . json_encode($activityParams));
        $activityInstance = $this->executeQuerywithBindParameters($activityQuery, $activityParams)->toArray();

        if (isset($activityInstance) && is_array($activityInstance) && !empty($activityInstance)) {
            $taskId = str_replace($activityInstance[0]["task_id"] . ":", "", $activityInstance[0]['activity_instance_id']);
            //print "taskId - $taskId";
        } else {
            $this->logger->info("No data found");
            return 0;
        }
        try {
            $test = $this->activityEngine->claimActivity($taskId, AuthContext::get(AuthConstants::USERNAME));
        } catch (Exception $e) {
            // print($e->getMessage());
            throw $e;
        }
        $selectQuery = "SELECT aia.* FROM `ox_activity_instance_assignee` as aia
        inner join ox_activity_instance ai on ai.id = aia.activity_instance_id
        WHERE ai.activity_instance_id=:activityInstanceId and aia.user_id=:userId;";
        $selectParams = array("userId" => AuthContext::get(AuthConstants::USER_ID), "activityInstanceId" => $data['activityInstanceId']);
        $activityInstanceAssignee = $this->executeQuerywithBindParameters($selectQuery, $selectParams)->toArray();
        if (isset($activityInstanceAssignee) && is_array($activityInstanceAssignee) && !empty($activityInstanceAssignee)) {
            $this->beginTransaction();
            try {
                $updateQuery = "UPDATE ox_activity_instance_assignee SET assignee = :assignee, user_id=:userId
                where id = :id;";
                $updateParams = array("assignee" => 1, "userId" => AuthContext::get(AuthConstants::USER_ID), "id" => $activityInstanceAssignee[0]['id']);
                $update = $this->executeQuerywithBindParameters($updateQuery, $updateParams);
                $this->commit();
            } catch (Exception $e) {
                $this->logger->info("Creation of Activity Instance Entry Failed" . $e->getMessage());
                $this->rollback();
                throw $e;
            }
        } else {
            $selectQuery = "SELECT * FROM ox_activity_instance
            where activity_instance_id =:activityInstanceId
            AND org_id=:orgId;";
            $selectParams = array("activityInstanceId" => $data['activityInstanceId'], "orgId" => AuthContext::get(AuthConstants::ORG_ID));
            $resultSelect = $this->executeQuerywithBindParameters($selectQuery, $selectParams)->toArray();
            $insert = "INSERT INTO `ox_activity_instance_assignee` (`activity_instance_id`,`user_id`,`assignee`)
            VALUES (:activityInstanceId,:userId,:assignee)";
            $insertParams = array("activityInstanceId" => $resultSelect[0]['id'], "userId" => AuthContext::get(AuthConstants::USER_ID), "assignee" => 1);
            $resultSet = $this->executeQuerywithBindParameters($insert, $insertParams);
        }
        return 1;
    }
    public function createActivityInstanceEntry(&$data, $commandService)
    {
        if (!isset($data['processInstanceId'])) {
            return;
        }
        // Org Id from workflow instance based on the Id
        $query = "SELECT * FROM `ox_workflow_instance` WHERE process_instance_id = :processInstanceId;";
        $queryParams = array("processInstanceId" => $data['processInstanceId']);
        $activityId = null;
        $resultSet = $this->executeQuerywithBindParameters($query, $queryParams)->toArray();
        if (count($resultSet) == 0) {
            throw new EntityNotFoundException("WorkflowInstance Not Found");
        }

        $workflowInstanceId = $resultSet[0]['id'];
        $orgId = $resultSet[0]['org_id'];
        if (isset($data['taskId'])) {
            $activityQuery = "SELECT * FROM `ox_activity` WHERE workflow_deployment_id = :workflowId and task_id=:taskId;";
            $activityParams = array("workflowId" => $resultSet[0]['workflow_deployment_id'], "taskId" => $data['taskId']);
            $activity = $this->executeQuerywithBindParameters($activityQuery, $activityParams)->toArray();
            $activityId = $activity[0]['id'];
        }

        $this->beginTransaction();
        try {
            if (isset($data['activityInstanceId'])) {
                $activity_instance_id = $data['activityInstanceId'];
            } else {
                $activity_instance_id = $data['executionActivityinstanceId'];
            }
            $activityInstance = array('workflow_instance_id' => $workflowInstanceId, 'activity_id' => $activityId, 'activity_instance_id' => $activity_instance_id, 'status' => 'In Progress', 'org_id' => $orgId, 'data' => json_encode($data['processVariables']));
            $activityCreated = $this->createActivityInstance($activityInstance);
            if (isset($data['assignee'])) {
                if (!isset($data['candidates'])) {
                    $data['candidates'] = array();
                }
                $data['candidates'][] = array('type' => 'assignee', 'userid' => $data['assignee']);
            }
            if (isset($data['candidates'])) {
                foreach ($data['candidates'] as $candidate) {
                    $assignee = 0;
                    if (isset($candidate['groupid'])) {
                        if ($candidate['type'] == 'assignee') {
                            $assignee = 1;
                        }
                        $groupQuery = $this->executeQuerywithParams("SELECT * FROM `ox_group` WHERE `name` = '" . $candidate['groupid'] . "';")->toArray();
                        if ($groupQuery) {
                            $insert = "INSERT INTO `ox_activity_instance_assignee` (`activity_instance_id`,`group_id`)
                            VALUES (:activityInstanceId,:groupId)";
                            $insertParams = array("activityInstanceId" => $activityInstance['id'], "groupId" => $groupQuery[0]['id']);
                            $resultSet = $this->executeQuerywithBindParameters($insert, $insertParams);
                            unset($resultSet);
                            unset($insert);
                        }
                    }
                    if (isset($candidate['userid'])) {
                        if ($candidate['type'] == 'assignee') {
                            $assignee = 1;
                        }
                        $userQuery = $this->executeQuerywithParams("SELECT * FROM `ox_user` WHERE `username` = '" . $candidate['userid'] . "';")->toArray();
                        if (isset($userQuery) && count($userQuery) > 0) {
                            $userId = $userQuery[0]['id'];
                        }
                        if ($candidate['userid'] == 'owner') {
                            $getOwner = $this->executeQuerywithParams("SELECT ox_file.created_by FROM `ox_file` join ox_workflow_instance on ox_workflow_instance.file_id = ox_file.id WHERE ox_workflow_instance.`id` = '" . $workflowInstanceId . "';")->toArray();
                            if (isset($getOwner) && count($getOwner) > 0) {
                                $userId = $getOwner[0]['created_by'];
                            }
                        }
                        if ($candidate['userid'] == 'manager') {
                            $manager = $this->executeQuerywithParams("SELECT manager_id FROM `ox_user_manager` inner join ox_user on ox_user_manager.user_id=ox_user.id inner join ox_file on ox_user.id=ox_file.created_by WHERE `user_id` = '" . $workflowInstanceId . "';")->toArray();
                            if (isset($manager) && count($manager) > 0) {
                                $userId = $manager[0]['manager_id'];
                            }
                        }
                        if (isset($userId)) {
                            $insert = "INSERT INTO `ox_activity_instance_assignee` (`activity_instance_id`,`user_id`,`assignee`)
                            VALUES (:activityInstanceId,:userId,:assignee)";
                            $insertParams = array("activityInstanceId" => $activityInstance['id'], "userId" => $userId, "assignee" => $assignee);
                            $resultSet = $this->executeQuerywithBindParameters($insert, $insertParams);
                            unset($resultSet);
                            unset($insert);
                        }
                    }
                }
            }
            if (isset($data['variables']) && isset($data['variables']['postCreate'])) {
                $commandData = $data['variables'];
                $fileQuery = "SELECT ox_file.data FROM `ox_file` join ox_workflow_instance on ox_workflow_instance.file_id = ox_file.id WHERE ox_workflow_instance.id = :workflow_instance_id;";
                $resultSet = $this->executeQuerywithBindParameters($fileQuery, array("workflow_instance_id" => $commandData['workflow_instance_id']))->toArray();
                if (count($resultSet) > 0) {
                    $fileData = json_decode($resultSet[0]['data'], true);
                    $commandData = array_merge($commandData, $fileData);
                    $commandData['activityInstanceId'] = $activity_instance_id;
                    $commandData['workflowInstanceId'] = $data['processInstanceId'];
                    $commandData['commands'] = $data['variables']['postCreate'];
                    $data['variables'] = $commandService->runCommand($commandData, null);
                }
            }
            $this->commit();
        } catch (Exception $e) {
            $this->logger->info("Creation of Activity Instance Entry Failed" . $e->getMessage());
            $this->rollback();
            throw $e;
        }
        return $data;
    }
    public function completeActivityInstance(&$data)
    {
        try {
            $query = "SELECT * FROM `ox_workflow_instance` WHERE process_instance_id = :processInstanceId;";
            $queryParams = array("processInstanceId" => $data['processInstanceId']);
            $activityId = null;
            $this->logger->info("Executing workflow instance query - $query with params " . json_encode($queryParams));
            $resultSet = $this->executeQuerywithBindParameters($query, $queryParams)->toArray();
            if ($resultSet) {
                $workflowInstanceId = $resultSet[0]['id'];
            } else {
                return 0;
            }
        } catch (Exception $e) {
            $this->logger->info("Complete Activity Instance - WorkflowInstance Does not Exist " . $e->getMessage());
            throw $e;
        }
        // Org Id from workflow instance based on the Id
        if (isset($data['processVariables'])) {
            $variables = $data['processVariables'];
            if (isset($variables['workflow_id']) || isset($variables['workflowId'])) {
                $workflowId = isset($variables['workflow_id']) ? $variables['workflow_id'] : $variables['workflowId'];
            } else {
                return 0;
            }
            if (isset($workflowId)) {
                $activityQuery = "SELECT a.* FROM `ox_activity` as a
                        inner join ox_activity_instance as ai on ai.activity_id = a.id
                        WHERE ai.activity_instance_id = :activityInstanceId and a.task_id=:taskId;";
                $queryParams = array("activityInstanceId" => $data['activityInstanceId'], "taskId" => $data['taskId']);
                $this->logger->info("Executing Activity query - $activityQuery with params " . json_encode($queryParams));
                $activity = $this->executeQuerywithBindParameters($activityQuery, $queryParams)->toArray();
                if (count($activity) > 0) {
                    $activityId = $activity[0]['id'];
                } else {
                    return 0;
                }
            }
            if (isset($variables['orgid'])) {
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
        $activityInstance = $this->executeQuerywithBindParameters($selectQuery, $selectParams)->toArray();
        $this->logger->info("Executing Activity instance query - $selectQuery with params " . json_encode($selectParams));
        if (count($activityInstance) > 0) {
            $this->beginTransaction();
            try {
                $updateQuery = "UPDATE ox_activity_instance SET status =:instanceStatus where id =:activityInstanceId;";
                $updateParams = array("instanceStatus" => 'Completed', "activityInstanceId" => $activityInstance[0]['id']);
                $this->logger->info("Updating Activity instance - $updateQuery with params " . json_encode($updateParams));
                $update = $this->executeUpdateWithBindParameters($updateQuery, $updateParams);
                $this->logger->info("Updated Records - " . $update->getAffectedRows());
                $this->commit();
                return $activityInstance;
            } catch (Exception $e) {
                $this->logger->info("Completion of Activity Instance Entry Failed" . $e->getMessage());
                $this->rollback();
                return $e;
            }
        } else {
            return 0;
        }
    }

    public function getActivityInstance($activityInstanceId, $workflowInstanceId)
    {
        try {
            $this->logger->info("getActivityInstance - ");
            $query = "select ox_activity_instance.*, ox_activity.task_id as task_id
                      FROM `ox_activity_instance`
                      LEFT JOIN ox_activity on ox_activity.id = ox_activity_instance.activity_id
                      LEFT JOIN ox_workflow_instance on ox_workflow_instance.id = ox_activity_instance.workflow_instance_id
                      WHERE ox_activity_instance.activity_instance_id=? and ox_activity_instance.org_id=? and ox_workflow_instance.process_instance_id=?";
            $queryParams = array($activityInstanceId, AuthContext::get(AuthConstants::ORG_ID), $workflowInstanceId);
            $activityInstance = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            $this->logger->info("getActivityInstance -> activityInstance - " . print_r($activityInstance, true));

            if (isset($activityInstance) && (is_array($activityInstance) && !empty($activityInstance))) {
                return $activityInstance[0];
            } else {
                throw new EntityNotFoundException("activity instance not found for " . $activityInstanceId);
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);

            throw $e;
        }
    }
}
