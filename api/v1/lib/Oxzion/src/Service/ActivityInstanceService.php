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
use Oxzion\Utils\ArrayUtils;

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

    public function unclaimActivityInstance($data)
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
            try {
                $test = $this->activityEngine->unclaimActivity($taskId, AuthContext::get(AuthConstants::USERNAME));
            } catch (Exception $e) {
                throw $e;
            }
        } else {
            $this->logger->info("No data found");
            return 0;
        }
        try {
            $updateQuery = "UPDATE ox_activity_instance_assignee SET assignee = :assignee WHERE activity_instance_id = (SELECT id from ox_activity_instance WHERE activity_instance_id = :activityInstanceId)";
            $updateParams = array("assignee" => 0, "activityInstanceId" => $data['activityInstanceId']);
            $update = $this->executeQuerywithBindParameters($updateQuery, $updateParams);
            $this->commit();
            return 1;
        } catch (Exception $e) {
            $this->logger->info("Creation of Activity Instance Entry Failed" . $e->getMessage());
            $this->rollback();
            throw $e;
        }
    }



    public function reclaimActivityInstance($data){
        try{
            $result = $this->unclaimActivityInstance($data);
            if($result == 1){
                $result = $this->claimActivityInstance($data);   
                return $result;
            }
        }catch(Exception $e){
            throw $e;
        }
         
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
            $fileRecord = "SELECT data from ox_file where id=:fileID";
            $fileParams = array('fileID' => $resultSet[0]['file_id']);
            $fileResult = $this->executeQuerywithBindParameters($fileRecord, $fileParams)->toArray();

            $activityInstance = array('workflow_instance_id' => $workflowInstanceId, 'activity_id' => $activityId, 'activity_instance_id' => $activity_instance_id, 'status' => 'In Progress', 'org_id' => $orgId, 'start_data' => $fileResult[0]['data']);
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
                    if (isset($candidate['roleid'])) {
                        $this->logger->info("Creation of Activity Instance role".print_r($candidate['roleid'],true));
                        if ($candidate['type'] == 'assignee') {
                            $assignee = 1;
                        }
                        $groupQuery = $this->executeQuerywithParams("SELECT * FROM `ox_role` WHERE `name` = '" . $candidate['roleid'] . "';")->toArray();
                        if ($groupQuery) {
                            $insert = "INSERT INTO `ox_activity_instance_assignee` (`activity_instance_id`,`role_id`)
                            VALUES (:activityInstanceId,:roleId)";
                            $insertParams = array("activityInstanceId" => $activityInstance['id'], "roleId" => $groupQuery[0]['id']);
                            $resultSet = $this->executeQuerywithBindParameters($insert, $insertParams);
                            unset($resultSet);
                            unset($insert);
                        }
                    }
                    if (isset($candidate['groupid'])) {
                         $this->logger->info("Creation of Activity Instance group".print_r($candidate['groupid'],true));
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
                        $this->logger->info("Creation of Activity Instance user".print_r($candidate['userid'],true));
                        $userId = null;
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
                            $this->logger->info("Creation of Activity Instance userID".print_r($userId,true));
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
            $this->logger->info("query " .$query);
            $this->logger->info("query params" . print_r($queryParams,true));
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


     public function getActivityChangeLog($activityInstanceId,$labelMapping=null){
        $selectQuery = "SELECT oai.start_data, oai.completion_data ,oaf.form_id from ox_activity_instance oai inner join ox_activity_form oaf on oai.activity_id = oaf.activity_id where oai.activity_instance_id = :activityInstanceId ";
        $selectQueryParams = array('activityInstanceId' => $activityInstanceId);
        $result = $this->executeQueryWithBindParameters($selectQuery, $selectQueryParams)->toArray();

        $startData = json_decode($result[0]['start_data'],true);
        $completionData = json_decode($result[0]['completion_data'],true);

        $fieldSelect = "SELECT ox_field.name,ox_field.template,ox_field.type,ox_field.text,ox_field.data_type,COALESCE(parent.name,'') as parentName,COALESCE(parent.text,'') as parentText,parent.data_type as parentDataType FROM ox_field 
                    left join ox_field as parent on ox_field.parent_id = parent.id 
                    inner join ox_form_field off on off.field_id = ox_field.id WHERE off.form_id=:formId AND ox_field.type NOT IN ('hidden','file','document','documentviewer') ORDER BY parentName, ox_field.name ASC";
        $fieldParams = array('formId' => $result[0]['form_id']);
        $resultSet = $this->executeQueryWithBindParameters($fieldSelect,$fieldParams)->toArray();
        
        $resultData = array();
        $gridResult = array();
        foreach ($resultSet as $key => $value) {
            if($value['data_type'] == 'grid' || $value['data_type'] == 'survey'){
                continue;
            }
            $initialparentData = null;
            $submissionparentData = null;
            if($value['parentName'] !=""){
                if(isset($gridResult[$value['parentName']])){
                    $gridResult[$value['parentName']]['fields'][] = $value;
                }else{
                    $initialParentData =  isset($startData[$value['parentName']]) ? $startData[$value['parentName']] : '[]';
                    $initialParentData =   is_string($initialParentData) ? json_decode($initialParentData, true) : $initialParentData;
                    // checkbox check 
                    // coverage check within grid
                    $submissionparentData = isset($completionData[$value['parentName']]) ? $completionData[$value['parentName']] : '[]';
                    $submissionparentData =   is_string($submissionparentData) ? json_decode($submissionparentData, true) : $submissionparentData;
                    $gridResult[$value['parentName']] = array("initial" => $initialParentData, "submission" => $submissionparentData, 'fields' => array($value));
                }
                
            }
            else{
                  $this->buildChangeLog($startData, $completionData, $value, $labelMapping, $resultData);
            }

         
    }
    
    if(count($gridResult) > 0){    
        foreach($gridResult as $parentName => $data){
            $initialDataset = $data['initial'];
            $submissionDataset = $data['submission'];
            $count = max(count($initialDataset), count($submissionDataset));
            for($i = 0; $i < $count; $i++){
                $initialRowData = isset($initialDataset[$i]) ? $initialDataset[$i] : array();
                $submissionRowData = isset($submissionDataset[$i]) ? $submissionDataset[$i] : array();
                foreach($data['fields'] as $key => $field){
                   $this->buildChangeLog($initialRowData, $submissionRowData, $field, $labelMapping, $resultData);
                }
            }
        }
     
    }
        return $resultData;
    }

    public function getFileDataByActivityInstanceId($activityInstanceId){
        try{
            $selectQuery = "SELECT of.data from ox_file of
                            INNER JOIN ox_workflow_instance owi on owi.file_id = of.id
                            INNER JOIN ox_activity_instance oai on oai.workflow_instance_id = owi.id where oai.activity_instance_id = :activityInstanceId ";
            $selectQueryParams = array('activityInstanceId' => $activityInstanceId);
            $result = $this->executeQueryWithBindParameters($selectQuery, $selectQueryParams)->toArray();
            return $result[0];            
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function getFieldValue($startDataTemp,$value,$labelMapping=null){
        if(!isset($startDataTemp[$value['name']]) || empty($startDataTemp[$value['name']])){
            return "";
        }
        $initialData = $startDataTemp[$value['name']];
        if($value['data_type'] == 'text'){
            //handle string data being sent
            if(is_string($initialData)){
                $fieldValue = json_decode($initialData, true);
            } else {
                $fieldValue = $initialData;
            }
            //handle select component values having an object with keys value and label 
            if(!empty($fieldValue) && is_array($fieldValue)){
                //Add Handler for default Labels
                if(isset($fieldValue['label'])){
                    $initialData = $fieldValue['label'];
                } else {
                    // Add for single values array
                    if(isset($fieldValue[0]) && count($fieldValue) == 1){
                        $initialData = $fieldValue[0];
                    } else {
                        //Case multiple values allowed
                        if(count($fieldValue) > 1){
                            foreach ($fieldValue as $k => $v) {
                                $initialData .= $v;
                            } 
                        }
                    }
                }
            }
       
        }else if($value['data_type'] == 'boolean'){
            $initialData = $initialData ? "Yes" : "No";
        }
        if($value['type'] =='radio'){
            $radioFields =json_decode($value['template'],true);
            foreach ($radioFields['values'] as $key => $radiovalues) {
                if($initialData == $radiovalues['value']){
                    $initialData = $radiovalues['label'];
                    break;
                }
            }
        }
        if($value['type'] =='selectboxes'){
            $radioFields =json_decode($value['template'],true);
            $selectValues = json_decode($initialData,true);
            $initialData = "";
            $processed =0;
            foreach ($selectValues as $key => $value) {
                if($value == 1){
                    if($processed == 0){
                       $radioFields = ArrayUtils::convertListToMap($radioFields['values'],'value','label');
                        $processed = 1;
                    }
                    if(isset($radioFields[$key])){
                        if($initialData !=""){
                            $initialData = $initialData . ",";
                        }
                        $initialData .= $radioFields[$key];
                    }
                }
            }
        }
        if($labelMapping && isset($labelMapping[$initialData])){
            $initialData = $labelMapping[$initialData];
        }
        return $initialData;
    }

    private function buildChangeLog($startData, $completionData, $value, $labelMapping, &$resultData){
        $initialData =  $this->getFieldValue($startData,$value,$labelMapping);
        $submissionData = $this->getFieldValue($completionData,$value,$labelMapping);
        if((isset($initialData) && ($initialData != '[]') && (!empty($initialData))) || 
                (isset($submissionData) && ($submissionData != '[]') && (!empty($submissionData)))){
                $resultData[] = array('name' => $value['name'],
                                       'text' => $value['text'],
                                       'dataType' => $value['data_type'],
                                       'parentName' => $value['parentName'],
                                       'parentText' => $value['parentText'],
                                       'parentDataType' => $value['parentDataType'],
                                       'initialValue' => $initialData,
                                       'submittedValue' => $submissionData);
        }
    }

}
