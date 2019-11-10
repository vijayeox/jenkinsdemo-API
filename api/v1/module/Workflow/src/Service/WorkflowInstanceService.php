<?php
namespace Workflow\Service;

use Workflow\Model\WorkflowInstanceTable;
use Workflow\Model\WorkflowInstance;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Service\WorkflowService;
use Oxzion\Service\FileService;
use Oxzion\Workflow\WorkFlowFactory;
use Oxzion\Utils\FilterUtils;
use Exception;
use Oxzion\EntityNotFoundException;
use Oxzion\InvalidParameterException;
use Workflow\Service\ActivityInstanceService;
use Oxzion\Service\UserService;
use Oxzion\ServiceException;

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

    public function getWorkflowInstances($appId=null, $filterArray = array())
    {
        try{
            $query = "select * from ox_workflow_instance where app_id=?";
            $queryParams = array($appId);
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            return $resultSet;
        }
        catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }

    }
    public function getWorkflowInstance($id)
    {
        try{
            $query = "select oxi.id,oxi.process_instance_id ,oxi.app_id,oxi.org_id,ow.uuid as workflow_id from ox_workflow_instance as oxi join ox_workflow as ow on oxi.workflow_id = ow.id where oxi.org_id=? and oxi.process_instance_id=?";

            // $query = "SELECT * from ox_workflow_instance where org_id=? and process_instance_id=?";
            $queryParams = array(AuthContext::get(AuthConstants::ORG_ID),$id);
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            $this->logger->info("WorkflowInstance ----------".print_r($resultSet,true));
            return $resultSet;
        }
        catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function startWorkflow($params){
        $this->logger->info("Starting StartWorkflow method");
        $this->logger->info("Starting StartWorkflow method params - ".json_encode($params));
        if(!isset($params['workflowId'])){
            throw new EntityNotFoundException("No workflow or workflow instance id provided");
        }
        if(!isset($params['orgId'])){
            $params['orgId'] = AuthContext::get(AuthConstants::ORG_UUID);
        }
        if(!isset($params['created_by'])){
            $params['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        }
        $workflowId = $params['workflowId'];


        $workflow = $this->workflowService->getWorkflow($workflowId);
        if (empty($workflow)) {
            $this->logger->info("EMPTY WORKFLOW --- ");
            throw new EntityNotFoundException("No workflow found for workflow $workflowId");
        }
        $params['form_id'] = $workflow['form_id'];
        $activityId = $params['form_id'];
        if(!isset($params['app_id'])){
            $params['app_id'] = $workflow['app_id'];
        }
        if(!isset($params['entity_id'])){
            $params['entity_id'] = $workflow['entity_id'];
        }
        $workflowInstance = $this->setupWorkflowInstance($workflowId, null,$params);

        $this->logger->info("SETUP WORKFLOW RESPONSE DATA ----- ".print_r($workflowInstance,true));


        try{
            $params = $this->cleanData($params);
            $fileData = $params;
            if(isset($workflowInstance['parent_workflow_instance_id'])){
                $fileDataResult = $this->fileService->getFileByWorkflowInstanceId($workflowInstance['parent_workflow_instance_id'],false);
                $oldFileData = json_decode($fileDataResult['data'], true);
                $fileData = array_merge($oldFileData, $fileData);
                $fileData['parent_id'] = $fileDataResult['id'];
            }
            $this->beginTransaction();
            $file = $this->fileService->createFile($fileData, $workflowInstance['id']);
            $this->logger->info("File created -".$file);
            $params['fileId'] = $fileData['uuid'];
            $params['workflow_instance_id'] = $workflowInstance['id'];
            $this->logger->info("Checking something".print_r($workflow['process_id'],true));
            $this->logger->info("Checking Params".print_r($params,true));
            $workflowInstanceId = $this->processEngine->startProcess($workflow['process_id'], $params);
            $this->logger->info("WorkflowInstanceId created".print_r($workflowInstanceId,true));
            $updateQuery = "UPDATE ox_workflow_instance SET process_instance_id=:process_instance_id where id = :workflowInstanceId";
            $updateParams = array('process_instance_id' => $workflowInstanceId['id'], 'workflowInstanceId' => $workflowInstance['id']);
            $this->logger->info("Query1 - $updateQuery with Parametrs - ".print_r($updateParams,true));
            $update = $this->executeUpdateWithBindParameters($updateQuery,$updateParams);
            $this->setupIdentityField($params);
            $this->commit();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            $this->rollback();
            throw $e;
        }
        $this->logger->info("file - ".print_r($file,true));
        return $file;
    }

    private function setupIdentityField($params){    
        $this->logger->info("setupIdentityField");     
        if(isset($params['identity_field'])){
            $data = $params;
            $test = $this->userService->checkAndCreateUser(array(), $data, true);
            try{ 
                $this->beginTransaction();
                $query = "INSERT INTO ox_wf_user_identifier(`workflow_instance_id`,`user_id`,`identifier_name`,`identifier`) VALUES (:workflowInstanceId,:userId,:identifierName,:identifier)";
                $queryParams = array("workflowInstanceId" => $params['workflow_instance_id'],
                                     "userId" => $data['id'],
                                     "identifierName" => $params['identity_field'], 
                                     "identifier" => $params[$params['identity_field']]);
                                     $this->logger->info("Query2 - $query with Parametrs - ".print_r($queryParams,true));
                $resultSet = $this->executeQueryWithBindParameters($query,$queryParams);
                $this->commit();
            }catch (Exception $e) { 
                $this->logger->info("errrrrrrrrrrrrrrrrrrr");  
                $this->logger->error($e->getMessage(),$e);
                $this->rollback();
                throw $e;
            }
        }
    }

    public function submitActivity($params){
        $this->logger->info("submitActivity method - ");
        if(!isset($params['workflowInstanceId'])){
            throw new InvalidParameterException("No workflow or workflow instance id provided");
        }
        if (!isset($params['activityInstanceId'])) {
            throw new InvalidParameterException("Activity instance id required");
        }
        if(!isset($params['orgId'])){
            $params['orgId'] = AuthContext::get(AuthConstants::ORG_UUID);
        }
        if(!isset($params['created_by'])){
            $params['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        }

        $workflowInstanceId = $params['workflowInstanceId'];
        $workflowInstance = $this->getWorkflowInstance($workflowInstanceId);
        $this->logger->info(WorkflowInstanceService::class."Get WorkflowInstance -----".print_r($workflowInstance,true));
        if(isset($workflowInstance[0])){
            $workflowId = $workflowInstance[0]['workflow_id'];
            $workflow = $this->workflowService->getWorkflow($workflowId);
            $this->logger->info(WorkflowInstanceService::class."Get Workflow -----".print_r($workflow,true));
        } else {
            throw new EntityNotFoundException("workflow instance not found for $workflowInstanceId");
        }

        $activityInstance = $this->activityInstanceService->getActivityInstance($params['activityInstanceId'],$workflowInstanceId);
        $this->logger->info("Activity Instance Value - ".print_r($activityInstance,true));
        $activityId = $activityInstance['activity_instance_id'];
        if(!isset($params['app_id'])){
            $params['app_id'] = $workflow['app_id'];
        }
        if(!isset($params['entity_id'])){
            $params['entity_id'] = $workflow['entity_id'];
        }
        $params['workflow_instance_id'] = $workflowInstance[0]['id'];
        $query = "select * from ox_file where workflow_instance_id=?";
        $queryParams = array($workflowInstance[0]['id']);
        $existingFile = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        if(isset($existingFile[0])){
            $this->logger->info(WorkflowInstanceService::class."FILE UPDATE-----".print_r($existingFile,true));
            $file = $this->fileService->updateFile($params, $existingFile[0]['uuid']);
             $workflowInstanceId = $this->activityEngine->submitTaskForm($activityId, $params);

        } else {
            throw new EntityNotFoundException("No file EntityNotFoundExceptiond for workflow instance ".$workflowInstanceId );
        }
        $this->logger->info("Submit activity Completed- ".print_r($file,true));
        return $file;

    }


    public function initiateWorkflow($data){
        $this->logger->info("Workflow Instance Start".print_r($data,true));
        try{
            if(isset($data['variables'])){
                $workflowInstance = $this->setupWorkflowInstance($data['variables']['workflowId'],$data['processInstanceId'],$data['variables']);
            } else {
                $this->logger->info("Invalid Data ----- ".print_r($data,true));
                throw new InvalidParameterException("Invalid Data".$data);
            }
            $this->logger->info("Initiate Workflow Data ----- ".print_r($workflowInstance,true));
            return $workflowInstance;
        }
        catch(Exception $e){
            $this->logger->error($e->getMessage(),$e);
            $this->logger->info("Workflow Instance Start Failed".$e->getMessage()."Trace ---- ".$e->getTraceAsString());
        }
    }


    public function completeWorkflow($params){
        try{
        $this->beginTransaction();
            $updateQuery = "UPDATE ox_workflow_instance SET status=:status where process_instance_id = :workflowInstanceId";
            $updateParams = array('status' => 'Completed', 'workflowInstanceId' => $params['processInstanceId']);
            $update = $this->executeUpdateWithBindParameters($updateQuery,$updateParams);
            $this->commit();
            return $update->getAffectedRows();
        } catch (Exception $e) {
            $this->logger->info(ActivityInstanceService::class."Workflow Instance Entry Failed".$e->getMessage());
            $this->logger->error($e->getMessage(),$e);
            $this->rollback();
            throw $e;
        }
        
    }
    
    public function setupWorkflowInstance($workflowId, $processInstanceId=null,$params =null)
    {
        $this->logger->info("SET UP Workflow Instance --- ".print_r($params,true));
        if (isset($params['orgId'])) {
            if ($org = $this->getIdFromUuid('ox_organization', $params['orgId'])) {
                $orgId = $org;
            } else {
                $orgId = $params['orgId'];
            }
        } else {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
        }
        $this->logger->info("SET UP Workflow Instance (OrgID) --- ".$orgId);
        if (isset($params['created_by'])) {
            if ($userId = $this->getIdFromUuid('ox_user', $params['created_by'])) {
                $createdBy = $userId;
            } else {
                $createdBy = $params['created_by'];
            }
        } else {
            $createdBy = AuthContext::get(AuthConstants::USER_ID);
        }
        if($processInstanceId){
            $this->logger->info("SET UP Workflow Instance (ProcessInstanceID) --- ".$processInstanceId);
            if(isset($params['workflow_instance_id'])){
                $updateQuery = "UPDATE ox_workflow_instance SET process_instance_id=:process_instance_id where id = :workflowInstanceId";
                $updateParams = array('process_instance_id' => $processInstanceId, 'workflowInstanceId' => $params['workflow_instance_id']);
                $update = $this->executeUpdateWithBindParameters($updateQuery,$updateParams);
            }
             $query = "select * from ox_workflow_instance where process_instance_id=?";
            $queryParams = array($processInstanceId);
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if(count($resultSet)>0){
                $this->logger->info("SET UP Workflow Instance Result --- ".print_r($processInstanceId,true));
                return $resultSet[0];
            }
        }
        $this->logger->info("SET UP Workflow Instance (CREATE NEW WORKFLOW INSTANCE)");
        $form = new WorkflowInstance();
        $dateCreated = date('Y-m-d H:i:s');
        $query = "select app_id, id from ox_workflow where uuid=:uuid";
        $queryParams = array("uuid" => $workflowId);
        $this->logger->info("Query new $query with params--- ".json_encode($queryParams));
        $workflowResultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        $this->logger->info("Workresu set--- ".json_encode($workflowResultSet));
        if(count($workflowResultSet)){
            $data = array('workflow_id'=> $workflowResultSet[0]['id'],'app_id'=> $workflowResultSet[0]['app_id'],'org_id'=> $orgId,'process_instance_id'=>$processInstanceId,'status'=>"In Progress",'date_created'=>$dateCreated,'created_by'=>$createdBy);
            if(isset($params['parentWorkflowInstanceId'])){
               $resultParentWorkflow = $this->getIdFromProcessInstanceId($params['parentWorkflowInstanceId']);
               if(count($resultParentWorkflow) > 0 ){
                   $data['parent_workflow_instance_id'] = $resultParentWorkflow[0]['id'];
               }

            }
            $this->logger->info("WorkFlow Instance Insert DATA --- ".print_r($data,true));
            $form->exchangeArray($data);
            $this->logger->info("WorkFlow Instance Form DATA --- ".print_r($form,true));
            $form->validate();
            $this->beginTransaction();
            try {
                $count = $this->table->save($form);
                $this->logger->info("WorkFlow Instance Form DATA INSERTED--- ".print_r($count,true));
                if ($count == 0) {
                    $this->rollback();
                    throw new ServiceException("WorkFlow Instance Create Failed","workflow.instance.failed");
                }
                $this->commit();
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
                $this->logger->info("SET UP WORKFLOW DATA--- ".print_r($data,true));
            } catch (Exception $e) {
                $this->logger->info("SET UP WORKFLOW Exception -- ".$e->getMessage()." Trace -- ".$e->getTraceAsString());
                $this->rollback();
                $this->logger->error($e->getMessage(), $e);
                throw $e;
            }

            return $data;
        }
    }

    public function getFileList($params, $filterParams = null)
    {
        if(!empty($filterParams)){
            $filterParamsArray = json_decode($filterParams['filter'],TRUE);
        }
        $this->logger->info("Filter Param --".json_encode($filterParamsArray));
        $filterlogic = array();
        $fields = "";
        $sortFields = array();
        $appId = $this->getIdFromUuid('ox_app', $params['appId']);
        if(isset($params['userId'])){
            $userId = $this->getIdFromUuid('ox_user',$params['userId']);
        }
        if(isset($params['workflowId'])){
            $workflowId = $params['workflowId'];
        }

        $queryParams = array();
        $appFilter = "ox_workflow.app_id =:appId";
        $queryParams['appId'] = $appId;

        if(isset($workflowId)){
            $appFilter .= " AND ox_workflow.uuid =:workflowId";
            $queryParams['workflowId'] = $workflowId;
        }
        if (isset($filterParamsArray[0]['filter'])) {
            $filterlogic = isset($filterParamsArray[0]['filter']['logic']) ? $filterParamsArray[0]['filter']['logic'] : " AND ";
            $cnt = 1;
            $fieldParams = array();
            foreach($filterParamsArray[0]['filter']['filters'] as $key => $value){
                $fields .= $fields !== "" ? "," : $fields ;
                $fields .= ':val'.$cnt;
                $fieldParams['val'.$cnt] = $value['field'];
                $cnt++;
            }
            $query = "SELECT distinct ox_field.data_type, ox_field.name
                    from ox_workflow
                    inner join ox_activity on ox_activity.workflow_id = ox_workflow.id
                    inner join ox_activity_form on ox_activity_form.activity_id = ox_activity.id
                    inner join ox_form on ox_form.id = ox_activity_form.form_id
                    inner join ox_form_field on ox_form_field.form_id = ox_form.id
                    inner join ox_field on ox_field.id = ox_form_field.field_id
                    where $appFilter  AND ox_field.name IN ($fields)";

            $resultSet = $this->executeQueryWithBindParameters($query,array_merge($queryParams, $fieldParams))->toArray();

            $fields = array();
            foreach($resultSet as $key => $value){
                switch($value['data_type']){
                    case 'date':
                        $fields[$value['name']] = "(ox_field.name = '".$value['name']."' AND CAST(ox_file_attribute.field_value AS DATETIME))";
                    break;
                    case 'int':
                        $fields[$value['name']]= "(ox_field.name = '".$value['name']."' AND CAST(ox_file_attribute.field_value AS INT))";
                    break;
                    default:
                        $fields[$value['name']]= "(ox_field.name = '".$value['name']."' AND ox_file_attribute.field_value)";
                }
                $sortFields[$value['name']] = "ox_file_attribute.field_value";
            }

            $where = FilterUtils::processFilters($filterParamsArray[0]['filter']['filters'], $filterlogic, $fields, $queryParams);
        }

        if (isset($filterParamsArray[0]['sort']) && count($filterParamsArray[0]['sort']) > 0) {
            $sort = $filterParamsArray[0]['sort'];
            $sort = FilterUtils::sortArray($sort, $sortFields);
        }else{
            $sort = "";
        }
        if(!empty($sort)){
            $sort = " ORDER BY ".$sort;
        }
        $pageSize = "LIMIT ".(isset($filterParamsArray[0]['take']) ? $filterParamsArray[0]['take'] : 20);
        $offset = "OFFSET ".(isset($filterParamsArray[0]['skip']) ? $filterParamsArray[0]['skip'] : 0);
        $where = " WHERE $appFilter AND owi.status = 'Completed' AND f1.latest = 1".(isset($where) ? " AND $where" : "");
        $fromQuery = "from ox_workflow_instance as owi
        inner join ox_workflow on ox_workflow.id = owi.workflow_id
        inner join ox_file as f1 on f1.workflow_instance_id = owi.id
        inner join (SELECT workflow_instance_id,max(date_created) as date_created from ox_file
        group by workflow_instance_id) as f2 on f1.workflow_instance_id = f2.workflow_instance_id and f1.date_created = f2.date_created
        inner join ox_file_attribute on ox_file_attribute.file_id = f1.id
        inner join ox_field on ox_field.id = ox_file_attribute.field_id";

        if(isset($userId)){
            $fromQueryWithUserId = " inner join ox_wf_user_identifier on ox_wf_user_identifier.identifier_name = ox_field.name";
            $where = $where ." AND ox_wf_user_identifier.user_id = :userId";
            $queryParams['userId'] = $userId;
            $fromQuery = $fromQuery.$fromQueryWithUserId;
        }
        $countQuery = "SELECT count(distinct f1.id) as `count` $fromQuery $where";
        $countResultSet = $this->executeQueryWithBindParameters($countQuery, $queryParams)->toArray();
        $select = "SELECT distinct f1.data,f1.uuid,owi.status,owi.process_instance_id as workflowInstanceId,ox_workflow.name,ox_file_attribute.field_value $fromQuery $where $sort $pageSize $offset";
        $this->logger->info("Policy Listing query $select with params".json_encode($queryParams));
        $resultSet = $this->executeQueryWithBindParameters($select, $queryParams)->toArray();
       return array('data' => $resultSet,'total' => $countResultSet[0]['count']);
    }

    public function getFileDocumentList($params)
    {
        $selectQuery ='select ox_field.name, ox_file_attribute.field_value from ox_file
        inner join ox_file_attribute on ox_file_attribute.file_id = ox_file.id
        inner join ox_field on ox_field.id = ox_file_attribute.field_id
        inner join ox_app on ox_field.app_id = ox_app.id
        where ox_file.org_id=:organization and ox_app.uuid=:appUuid and ox_field.data_type=:dataType
        and ox_file.uuid=:fileUuid';
        $selectQueryParams = array('organization' => AuthContext::get(AuthConstants::ORG_ID),
                                   'appUuid' => $params['appId'],
                                   'fileUuid' => $params['fileId'],
                                   'dataType' =>'document');
        try {
            $selectResultSet = $this->executeQueryWithBindParameters($selectQuery, $selectQueryParams)->toArray();
            return $selectResultSet;
        }
        catch(Exception $e) {
            $this->logger->error($e->getMessage(),$e);
            return 0;
        }
    }

    private function getIdFromProcessInstanceId($processInstanceId){
        $query = "Select id from ox_workflow_instance where process_instance_id=:processInstanceId;";
        $params = array("processInstanceId" => $processInstanceId);
        return $result = $this->executeQueryWithBindParameters($query,$params)->toArray();
    }

    private function cleanData($params)
    {
        unset($params['submit']);
        unset($params['controller']);
        unset($params['method']);
        unset($params['action']);
        unset($params['access']);

        return $params;

    }
}