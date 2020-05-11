<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\EntityNotFoundException;
use Oxzion\InvalidParameterException;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Service\FileService;
use Oxzion\Service\UserService;
use Oxzion\Service\WorkflowService;
use Oxzion\Workflow\WorkFlowFactory;
use Oxzion\Model\WorkflowInstance;
use Oxzion\Model\WorkflowInstanceTable;
use Oxzion\Service\ActivityInstanceService;

class WorkflowInstanceService extends AbstractService
{
    protected $workflowService;
    protected $fileService;
    protected $processEngine;
    protected $userService;
    protected $activityEngine;

    public function __construct(
        $config,
        $dbAdapter,
        WorkflowInstanceTable $table,
        FileService $fileService,
        UserService $userService,
        WorkflowService $workflowService,
        WorkflowFactory $workflowFactory,
        ActivityInstanceService $activityInstanceService
    ) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->fileService = $fileService;
        $this->workflowService = $workflowService;
        $this->workFlowFactory = $workflowFactory;
        $this->processEngine = $this->workFlowFactory->getProcessEngine();
        $this->activityEngine = $this->workFlowFactory->getActivity();
        $this->activityInstanceService = $activityInstanceService;
        $this->userService = $userService;
    }
    public function setProcessEngine($processEngine)
    {
        $this->processEngine = $processEngine;
    }

    public function setActivityEngine($activityEngine)
    {
        $this->activityEngine = $activityEngine;
    }
    public function saveWorkflowInstance($appId, &$data)
    {
        $WorkflowInstance = new WorkflowInstance();
        $data['app_id'] = $appId;
        if (!isset($data['id'])) {
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
        }
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $WorkflowInstance->exchangeArray($data);
        $WorkflowInstance->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($WorkflowInstance);
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
            switch (get_class($e)) {
                case "Oxzion\ValidationException":
                    $this->rollback();
                    throw $e;
                    break;
                default:
                    $this->rollback();
                    return 0;
                    break;
            }
        }
        return $count;
    }
    public function updateWorkflowInstance($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $data['id'] = $id;
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $file = $obj->toArray();
        $changedArray = array_merge($obj->toArray(), $data);
        $WorkflowInstance = new WorkflowInstance();
        $WorkflowInstance->exchangeArray($changedArray);
        $WorkflowInstance->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($WorkflowInstance);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $count;
    }

    public function deleteWorkflowInstance($id)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($id);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }

        return $count;
    }

    public function getWorkflowInstances($appId = null, $filterArray = array())
    {
        try {
            $query = "select * from ox_workflow_instance where app_id=?";
            $queryParams = array($appId);
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            return $resultSet;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }

    }
    public function getWorkflowInstance($id)
    {
        try {
            $query = "select oxi.id,oxi.process_instance_id ,oxi.app_id,oxi.org_id,ow.uuid as workflow_id 
            from ox_workflow_instance as oxi
            join ox_workflow_deployment as wd on wd.id = oxi.workflow_deployment_id
             join ox_workflow as ow on wd.workflow_id = ow.id
             where oxi.org_id=? and oxi.process_instance_id=?";

            // $query = "SELECT * from ox_workflow_instance where org_id=? and process_instance_id=?";
            $queryParams = array(AuthContext::get(AuthConstants::ORG_ID), $id);
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            $this->logger->info("WorkflowInstance ----------" . print_r($resultSet, true));
            return $resultSet;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function startWorkflow($params)
    {
        $this->logger->info("Starting StartWorkflow method params - ".json_encode($params));
        if (!isset($params['workflowId'])) {
            throw new EntityNotFoundException("No workflow or workflow instance id provided");
        }
        if (!isset($params['orgId'])) {
            $params['orgId'] = AuthContext::get(AuthConstants::ORG_UUID);
        }
        if (!isset($params['created_by'])) {
            $params['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        }
        $workflowId = $params['workflowId'];

        if (!isset($params['app_id'])) {
            $params['app_id'] = null;
        }
        if (!isset($params['appId'])) {
            $params['appId'] = null;
        }

        $workflow = $this->workflowService->getWorkflow($workflowId, $params['appId']);

        if (empty($workflow)) {
            $this->logger->info("EMPTY WORKFLOW --- ");
            throw new EntityNotFoundException("No workflow found for workflow $workflowId");
        }
        $params['form_id'] = $workflow['form_id'];
        $activityId = $params['form_id'];
        if (!isset($params['app_id'])) {
            $params['app_id'] = $workflow['app_id'];
        }
        if (!isset($params['entity_id'])) {
            $params['entity_id'] = $workflow['entity_id'];
        }
        $workflowInstance = $this->setupWorkflowInstance($workflowId, null, $params);

        $this->logger->info("SETUP WORKFLOW RESPONSE DATA ----- " . print_r($workflowInstance, true));

        try {
            $params = $this->cleanData($params);
            $fileData = $params;
            $this->beginTransaction();
            if (isset($workflowInstance['parent_workflow_instance_id'])) {
                $fileDataResult = $this->fileService->getFileByWorkflowInstanceId($workflowInstance['parent_workflow_instance_id'], false);
                $oldFileData = json_decode($fileDataResult['data'], true);
                $fileData = array_merge($oldFileData, $fileData);
                // $fileData['parent_id'] = $fileDataResult['id']; // Check during ENDOR
                $file = $this->fileService->updateFile($fileData,$fileDataResult['fileId']);
                if(!isset($fileData['uuid'])){
                    $fileData['uuid'] = $fileDataResult['fileId'];
                }
                $fileData['data'] = !isset($fileData['data']) ? $fileData : $fileData['data'];
            }else{
                $file = $this->fileService->createFile($fileData);
            }
            $this->logger->info("File created -" . $file);
            $params['fileId'] = $fileData['uuid'];
            $params['workflow_instance_id'] = $workflowInstance['id'];
            $this->logger->info("Checking something" . print_r($workflow['process_definition_id'], true));
            $this->logger->info("Checking Params" . print_r($params, true));
            $workflowInstanceId = $this->processEngine->startProcess($workflow['process_definition_id'], $params);
            $this->logger->info("WorkflowInstanceId created" . print_r($workflowInstanceId, true));
            $updateQuery = "UPDATE ox_workflow_instance SET process_instance_id=:process_instance_id, file_id=:fileId, start_data=:startData where id = :workflowInstanceId";
            $updateParams = array('process_instance_id' => $workflowInstanceId['id'], 'workflowInstanceId' => $workflowInstance['id'],'fileId'=>$this->getIdFromUuid('ox_file', $params['fileId']),'startData'=>json_encode($fileData['data']));
            $this->logger->info("Query1 - $updateQuery with Parametrs - " . print_r($updateParams, true));
            $update = $this->executeUpdateWithBindParameters($updateQuery, $updateParams);
            $this->setupIdentityField($params);
            $this->commit();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            $this->rollback();
            throw $e;
        }
        $this->logger->info("file - " . print_r($file, true));
        return $file;
    }

    private function setupIdentityField($params)
    {
        $this->logger->info("setupIdentityField");
        if (isset($params['identifier_field'])) {
            $data = $params;
            $test = $this->userService->checkAndCreateUser(array(), $data, true);
        }
    }

    public function submitActivity($params)
    {
        $this->logger->info("submitActivity method - ");
        if (!isset($params['workflowInstanceId'])) {
            throw new InvalidParameterException("No workflow or workflow instance id provided");
        }
        if (!isset($params['activityInstanceId'])) {
            throw new InvalidParameterException("Activity instance id required");
        }
        if (!isset($params['orgId'])) {
            $params['orgId'] = AuthContext::get(AuthConstants::ORG_UUID);
        }

        // SADHITHA CHANGE CREATED BY TO SUBMITTED BY
        if (!isset($params['created_by'])) {
            $params['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        }

        $workflowInstanceId = $params['workflowInstanceId'];
        $workflowInstance = $this->getWorkflowInstance($workflowInstanceId);
        $this->logger->info(WorkflowInstanceService::class . "Get WorkflowInstance -----" . print_r($workflowInstance, true));
        if (isset($workflowInstance[0])) {
            $workflowId = $workflowInstance[0]['workflow_id'];
            $workflow = $this->workflowService->getWorkflow($workflowId);
            $this->logger->info(WorkflowInstanceService::class . "Get Workflow -----" . print_r($workflow, true));
        } else {
            throw new EntityNotFoundException("workflow instance not found for $workflowInstanceId");
        }

        $activityInstance = $this->activityInstanceService->getActivityInstance($params['activityInstanceId'], $workflowInstanceId);
        $this->logger->info("Activity Instance Value - " . print_r($activityInstance, true));
        $activityId = $activityInstance['activity_instance_id'];
        if (!isset($params['app_id'])) {
            $params['app_id'] = $workflow['app_id'];
        }
        if (!isset($params['entity_id'])) {
            $params['entity_id'] = $workflow['entity_id'];
        }
        $params['workflow_instance_id'] = $workflowInstance[0]['id'];
        $query = "select ox_file.* from ox_file join ox_workflow_instance on ox_workflow_instance.file_id =  ox_file.id where ox_workflow_instance.id=?";
        $queryParams = array($workflowInstance[0]['id']);
        $existingFile = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        if (isset($existingFile[0])) {
            $this->logger->info(WorkflowInstanceService::class . "FILE UPDATE-----" . print_r($existingFile, true));
            $file = $this->fileService->updateFile($params, $existingFile[0]['uuid']);
            $updateQuery = "UPDATE ox_activity_instance SET completion_data=:completionData,submitted_date=:submittedDate,modified_by=:modifiedBy where workflow_instance_id=:workflowInstanceId";
            $updateQueryParams = array('completionData'=>json_encode($params),'submittedDate'=>date('Y-m-d H:i:s'),'modifiedBy'=>AuthContext::get(AuthConstants::USER_ID),'workflowInstanceId' => $workflowInstance[0]['id']);
            $updateQueryResult = $this->executeUpdateWithBindParameters($updateQuery,$updateQueryParams);
            $workflowInstanceId = $this->activityEngine->completeActivity($activityId, $params);

        } else {
            throw new EntityNotFoundException("No file EntityNotFoundExceptiond for workflow instance " . $workflowInstanceId);
        }
        $this->logger->info("Submit activity Completed- " . print_r($file, true));
        return $file;

    }

    public function initiateWorkflow($data)
    {
        $this->logger->info("Workflow Instance Start" . print_r($data, true));
        try {
            if (isset($data['variables']) && isset($data['variables']['workflowId'])) {
                $workflowInstance = $this->setupWorkflowInstance($data['variables']['workflowId'], $data['processInstanceId'], $data['variables']);
            } else {
                $this->logger->info("Invalid Data ----- " . print_r($data, true));
                throw new InvalidParameterException("Invalid Data - " . json_encode($data));
            }
            $this->logger->info("Initiate Workflow Data ----- " . print_r($workflowInstance, true));
            return $workflowInstance;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            $this->logger->info("Workflow Instance Start Failed" . $e->getMessage() . "Trace ---- " . $e->getTraceAsString());
        }
    }

    public function completeWorkflow($params)
    {
        $this->logger->info("Complete Workflow Params------".print_r($params,true));
        try {
            $this->beginTransaction();
             $selectQuery = "SELECT ox_file.data from ox_file inner join ox_workflow_instance on ox_workflow_instance.file_id = ox_file.id where process_instance_id = :workflowInstanceId";
             $updateQueryParams = array('workflowInstanceId' => $params['processInstanceId']);
            $select = $this->executeQueryWithBindParameters($selectQuery, $updateQueryParams)->toArray();
            $this->logger->info("QUERY------- $selectQuery with params".print_r($updateQueryParams,true));
            if(count($select)>0){
                $updateQuery = "UPDATE ox_workflow_instance owi join ox_file of on of.id = owi.file_id SET owi.status=:status,owi.completion_data=:fileData,owi.date_modified=:modifiedDate where process_instance_id = :workflowInstanceId";
                $updateParams = array('status' => 'Completed', 'workflowInstanceId' => $params['processInstanceId'],'fileData' =>$select[0]['data'],'modifiedDate'=>date('Y-m-d H:i:s'));
                $update = $this->executeUpdateWithBindParameters($updateQuery, $updateParams);  
                $this->commit();
                return $update->getAffectedRows();    
            }
        } catch (Exception $e) {
            $this->logger->info(ActivityInstanceService::class . "Workflow Instance Entry Failed" . $e->getMessage());
            $this->logger->error($e->getMessage(), $e);
            $this->rollback();
            throw $e;
        }

    }

    public function setupWorkflowInstance($workflowId, $processInstanceId = null, $params = null)
    {
        $this->logger->info("SET UP Workflow Instance --- " . print_r($params, true));
        if (isset($params['orgId'])) {
            if ($org = $this->getIdFromUuid('ox_organization', $params['orgId'])) {
                $orgId = $org;
            } else {
                $orgId = $params['orgId'];
            }
        } 
        if(!$orgId){
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
        }
        $this->logger->info("SET UP Workflow Instance (OrgID) --- " . $orgId);
        if (isset($params['created_by'])) {
            if ($userId = $this->getIdFromUuid('ox_user', $params['created_by'])) {
                $createdBy = $userId;
            } else {
                $createdBy = $params['created_by'];
            }
        } else {
            $createdBy = AuthContext::get(AuthConstants::USER_ID);
        }
        if ($processInstanceId) {
            $this->logger->info("SET UP Workflow Instance (ProcessInstanceID) --- " . $processInstanceId);
            if (isset($params['workflow_instance_id'])) {
                $updateQuery = "UPDATE ox_workflow_instance SET process_instance_id=:process_instance_id where id = :workflowInstanceId";
                $updateParams = array('process_instance_id' => $processInstanceId, 'workflowInstanceId' => $params['workflow_instance_id']);
                $update = $this->executeUpdateWithBindParameters($updateQuery, $updateParams);
            }
            $query = "select * from ox_workflow_instance where process_instance_id=?";
            $queryParams = array($processInstanceId);
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if (count($resultSet) > 0) {
                $this->logger->info("SET UP Workflow Instance Result --- " . print_r($processInstanceId, true));
                return $resultSet[0];
            }
        }
        $this->logger->info("SET UP Workflow Instance (CREATE NEW WORKFLOW INSTANCE)");
        $form = new WorkflowInstance();
        $dateCreated = date('Y-m-d H:i:s');
        $query = "select w.app_id, wd.id from ox_workflow as w
        inner join ox_workflow_deployment as wd on w.id = wd.workflow_id 
        where w.uuid=:uuid and wd.latest=:latest";
        $queryParams = array("uuid" => $workflowId,"latest" => 1);
        $workflowResultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();

        if (count($workflowResultSet)) {
            $data = array('workflow_deployment_id' => $workflowResultSet[0]['id'], 'app_id' => $workflowResultSet[0]['app_id'], 'org_id' => $orgId, 'process_instance_id' => $processInstanceId, 'status' => "In Progress", 'date_created' => $dateCreated, 'created_by' => $createdBy);
            if (isset($params['parentWorkflowInstanceId'])) {
                $resultParentWorkflow = $this->getIdFromProcessInstanceId($params['parentWorkflowInstanceId']);
                if (count($resultParentWorkflow) > 0) {
                    $data['parent_workflow_instance_id'] = $resultParentWorkflow[0]['id'];
                }

            }
            $this->logger->info("WorkFlow Instance Insert DATA --- " . print_r($data, true));
            $form->exchangeArray($data);
            $this->logger->info("WorkFlow Instance Form DATA --- " . print_r($form, true));
            $form->validate();
            $this->beginTransaction();
            try {
                $count = $this->table->save($form);
                $this->logger->info("WorkFlow Instance Form DATA INSERTED--- " . print_r($count, true));
                if ($count == 0) {
                    $this->rollback();
                    throw new ServiceException("WorkFlow Instance Create Failed", "workflow.instance.failed");
                }
                $this->commit();
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
                $this->logger->info("SET UP WORKFLOW DATA--- " . print_r($data, true));
            } catch (Exception $e) {
                $this->logger->info("SET UP WORKFLOW Exception -- " . $e->getMessage() . " Trace -- " . $e->getTraceAsString());
                $this->rollback();
                $this->logger->error($e->getMessage(), $e);
                throw $e;
            }

            return $data;
        }
    }

    private function getIdFromProcessInstanceId($processInstanceId)
    {
        $query = "Select id from ox_workflow_instance where process_instance_id=:processInstanceId;";
        $params = array("processInstanceId" => $processInstanceId);
        return $result = $this->executeQueryWithBindParameters($query, $params)->toArray();
    }

    private function cleanData($params)
    {
        unset($params['submit']);
        unset($params['controller']);
        unset($params['method']);
        unset($params['action']);
        unset($params['access']);
        unset($params['uuid']);
        return $params;

    }

    public function claimActivityInstance(&$data){
        $result = $this->activityInstanceService->claimActivityInstance($data);     
        return $result;
    }

    public function getActivityInstanceForm(&$data){
        $result = $this->activityInstanceService->getActivityInstanceForm($data);     
        return $result;
    }

    public function getActivityLog($fileId,$appId,$filterParams=null){
        $selectQuery = "(SELECT owi.process_instance_id as workflowInstanceId, ow.name as workflowName,oai.start_date, owi.date_created as workflowInstanceCreatedDate,owi.date_modified as workflowInstanceSubmissionDate,owi.created_by,ouu.name as ModifiedBy,ou.name as activityModifiedBy,owi.modified_by,oa.name as activityName, oai.submitted_date as activitySubmittedDate,oai.modified_by,oai.activity_instance_id as activityInstanceId from ox_workflow_instance owi 
        inner join ox_workflow_deployment owd on owd.id = owi.workflow_deployment_id 
        inner join ox_workflow ow on ow.id = owd.workflow_id
        inner join ox_activity_instance oai on oai.workflow_instance_id = owi.id
        inner join ox_activity oa on oa.id = oai.activity_id
        inner join ox_file of on of.id = owi.file_id
        inner join ox_user ou on ou.id = oai.modified_by
        inner join ox_user ouu on ouu.id = owi.created_by
        where of.uuid = :fileId and owi.app_id = :appId ORDER BY oai.start_date ASC) UNION (SELECT owi.process_instance_id as workflowInstanceId, ow.name as workflowName,
        owi.date_created, owi.date_created as workflowInstanceCreatedDate,
        owi.date_modified as workflowInstanceSubmissionDate,
        owi.created_by,ouu.name as ModifiedBy,ouu.name as activityModifiedBy,
        owi.modified_by,'Initiated' as activityName
        , owi.date_created as activitySubmittedDate,
        owi.created_by,'' as activityInstanceId from ox_workflow_instance owi 
        inner join ox_workflow_deployment owd on owd.id = owi.workflow_deployment_id 
        inner join ox_workflow ow on ow.id = owd.workflow_id
        inner join ox_file of on of.id = owi.file_id
        left join ox_activity_instance oai on oai.workflow_instance_id = owi.id
        left join ox_activity oa on oa.id = oai.activity_id
        inner join ox_user ouu on ouu.id = owi.created_by
        where of.uuid = :fileId and owi.app_id = :appId and oai.id is NULL)";         
        $selectQueryParams = array('fileId' => $fileId, 'appId' => $this->getIdFromUuid('ox_app', $appId));
        $result = $this->executeQueryWithBindParameters($selectQuery, $selectQueryParams)->toArray();
        return $result;
    }

    public function getWorkflowSubmissionData($workflowInstanceId){
        try{
            $selectQuery = "SELECT of.data from ox_file of
                            INNER JOIN ox_workflow_instance owi on owi.file_id = of.id where owi.process_instance_id = :workflowInstanceId ";
            $selectQueryParams = array('workflowInstanceId' => $workflowInstanceId);
            $result = $this->executeQueryWithBindParameters($selectQuery, $selectQueryParams)->toArray();
            if(isset($result[0])){
                return $result[0]; 
            } else {
                return;
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function getWorkflowChangeLog($workflowInstanceId,$labelMapping=null){
        $selectQuery = "SELECT owi.start_data, owi.file_id ,pwi.completion_data as initialData from ox_workflow_instance owi left join ox_workflow_instance as pwi on owi.parent_workflow_instance_id = pwi.id where owi.process_instance_id = :workflowInstanceId ";
        $selectQueryParams = array('workflowInstanceId' => $workflowInstanceId);
        $result = $this->executeQueryWithBindParameters($selectQuery, $selectQueryParams)->toArray();
        if(count($result) > 0){
            $recordSet = $this->fileService->getWorkflowInstanceByFileId($this->getUuidFromId('ox_file',$result[0]['file_id']));// get entityId
            $startData = json_decode($result[0]['initialData'],true);
            $completionData = json_decode($result[0]['start_data'],true);
            $resultData = $this->fileService->getChangeLog($recordSet[0]['entity_id'],$startData,$completionData,$labelMapping);
            return $resultData;
        } else {
            return $result;
        }
    }
}
