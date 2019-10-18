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
use Zend\Log\Logger;
use Exception;
use Oxzion\Service\UserService;

class WorkflowInstanceService extends AbstractService
{
    protected $workflowService;
    protected $fileService;
    protected $processEngine;
    protected $userService;
    protected $activityEngine;
    private $log;

    public function __construct(
        $config,
        $dbAdapter,
        WorkflowInstanceTable $table,
        FileService $fileService,
        UserService $userService,
        WorkflowService $workflowService,
        WorkflowFactory $workflowFactory,
        Logger $log
    ) {
        parent::__construct($config, $dbAdapter, $log);
        $this->table = $table;
        $this->fileService = $fileService;
        $this->workflowService = $workflowService;
        $this->log = $log;
        $this->workFlowFactory = $workflowFactory;
        $this->processEngine = $this->workFlowFactory->getProcessEngine();
        $this->activityEngine = $this->workFlowFactory->getActivity();
        $this->userService = $userService;
    }
    public function setProcessEngine($processEngine)
    {
        $this->processEngine = $processEngine;
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
            $this->log->err($e);
            return 0;
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
            $this->log->err($e);
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
            $this->log->err($e);
            return 0;
        }

    }
    public function getWorkflowInstance($id)
    {
        try{
            $query = "select * from ox_workflow_instance where org_id=? and process_instance_id=?";
            $queryParams = array(AuthContext::get(AuthConstants::ORG_ID),$id);
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            return $resultSet;
        }
        catch (Exception $e) {
            $this->log->err($e);
            return 0;
        }
    }

    public function executeWorkflow($params, $id=null)
    { 
        if(isset($params['workflowId'])){
            $workflowId = $params['workflowId'];
            $workFlowFlag = 1;
            $workflow = $this->workflowService->getWorkflow($workflowId);
            if (empty($workflow)) {
                $workFlowFlag= 0;
                return 0;
            }
        } else {
            if(isset($params['workflowInstanceId'])){
                $workflowInstanceId = $params['workflowInstanceId'];
                $workflowInstance = $this->getWorkflowInstance($workflowInstanceId);
                if(isset($workflowInstance[0])){
                    $workflowId = $workflowInstance[0]['workflow_id'];
                    $workFlowFlag= 1;
                    $workflow = $this->workflowService->getWorkflow($workflowId);
                } else {
                    return 0;
                }
            }
        }
        if (!isset($params['activityId'])) {
            $params['form_id'] = $workflow['form_id'];
            $activityId = $params['form_id'];
        } else {
            try{
                $query = "select ox_activity_instance.*, ox_activity.task_id as task_id 
                          FROM `ox_activity_instance` 
                          LEFT JOIN ox_activity on ox_activity.id = ox_activity_instance.activity_id 
                          WHERE ox_activity_instance.activity_instance_id=? and ox_activity_instance.org_id=? and ox_activity_instance.workflow_instance_id=?";
                $queryParams = array($params['activityId'],AuthContext::get(AuthConstants::ORG_ID),$workflowInstance[0]['id']);
                $activityInstance = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
                if(isset($activityInstance) || is_array($activityInstance) || !empty($activityInstance)){
                    $activityId = $activityInstance[0]['activity_instance_id'];
                } else {
                    return 0;
                }
            }
            catch (Exception $e) { 
                $this->log->err($e);
                throw $e;
            }
        
        }

        if(!isset($params['orgId'])){
            $params['orgId'] = AuthContext::get(AuthConstants::ORG_UUID);
        }
        if(!isset($params['app_id'])){
            $params['app_id'] = $workflow['app_id'];
        }
        if(!isset($params['created_by'])){
            $params['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        }
        if(!isset($params['entity_id'])){
            $params['entity_id'] = $workflow['entity_id'];
        }

        if (isset($id)) {
            return $this->fileService->updateFile($params, $id);
        } else {
            if ($workFlowFlag) {
                if ($workflow['form_id']==$params['form_id'] || $params['activityId']==null) {
                    $workflowInstance = $this->setupWorkflowInstance($workflowId, null,$params);
                    try{ 
                        $this->beginTransaction();
                        $fileData = $params;
                        $file = $this->fileService->createFile($fileData, $workflowInstance['id']);
                        $params['fileId'] = $fileData['id'];
                        $params['workflow_instance_id'] = $workflowInstance['id'];
                        $workflowInstanceId = $this->processEngine->startProcess($workflow['process_id'], $params);
                        // var_dump($workflowInstanceId);exit;
                        $updateQuery = "UPDATE ox_workflow_instance SET process_instance_id=:process_instance_id where id = :workflowInstanceId";
                        $updateParams = array('process_instance_id' => $workflowInstanceId['id'], 'workflowInstanceId' => $workflowInstance['id']);
                        $update = $this->executeUpdateWithBindParameters($updateQuery,$updateParams);
                        $this->setupIdentityField($params);
                        $this->commit();
                    } catch (Exception $e) {
                        $this->log->info(ActivityInstanceService::class."Workflow Instance Entry Failed".$e->getMessage());
                        $this->rollback();
                        return 0;
                    }
                } else {
                    $query = "select * from ox_file where workflow_instance_id=?";
                    if(isset($workflowInstanceId)){
                        $queryParams = array($workflowInstance[0]['id']);
                    } else {
                        $queryParams = array($workflowInstance['id']);
                    }
                    $existingFile = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
                    if(isset($existingFile[0])){
                        $file = $this->fileService->updateFile($params, $existingFile[0]['uuid']);
                        $this->log->info("Update file result - $file \n");
                        $workflowInstanceId = $this->activityEngine->submitTaskForm($activityId, $params);
                        if($workflowInstanceId != 0){
                            return $params;
                        } else {
                            return 0;
                        }
                    } else {
                        return 0;
                    }
                }
                $this->log->info("Completed the executeWorkflow with the result - $file \n");
                return $file;
            } else {
                return 0;
            }
        }
        return 0;
    }

    private function setupIdentityField($params){         
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
                $resultSet = $this->executeQueryWithBindParameters($query,$queryParams);
                $this->commit();
            }catch (Exception $e) { 
                $this->log->err($e);
                $this->rollback();
                throw $e;
            }
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
            $this->log->info(ActivityInstanceService::class."Workflow Instance Entry Failed".$e->getMessage());
            $this->rollback();
            return 0;
        }
        
    }
    
    public function setupWorkflowInstance($workflowId, $processInstanceId=null,$params =null)
    {  
        if (isset($params['orgId'])) {
            if ($org = $this->getIdFromUuid('ox_organization', $params['orgId'])) {
                $orgId = $org;
            } else {
                $orgId = $params['orgId'];
            }
        } else {
            $orgId = AuthContext::get(AuthConstants::ORG_UUID);
        }
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
            if(isset($params['workflow_instance_id'])){
                $updateQuery = "UPDATE ox_workflow_instance SET process_instance_id=:process_instance_id where id = :workflowInstanceId";
                $updateParams = array('process_instance_id' => $processInstanceId, 'workflowInstanceId' => $params['workflow_instance_id']);
                $update = $this->executeUpdateWithBindParameters($updateQuery,$updateParams);
            }
            $query = "select * from ox_workflow_instance where process_instance_id=?";
            $queryParams = array($processInstanceId);
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if(count($resultSet)>0){
                return $resultSet[0];
            }
        }
        $form = new WorkflowInstance();
        $dateCreated = date('Y-m-d H:i:s');
        $query = "select id,app_id from ox_workflow where id=? or uuid=?";
        $queryParams = array($workflowId,$workflowId);
        $workflowResultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        if(count($workflowResultSet)){
            $data = array('workflow_id'=> $workflowResultSet[0]['id'],'app_id'=> $workflowResultSet[0]['app_id'],'org_id'=> $orgId,'process_instance_id'=>$processInstanceId,'status'=>"In Progress",'date_created'=>$dateCreated,'created_by'=>$createdBy);
            $form->exchangeArray($data);
            $form->validate();
            $this->beginTransaction();
            try {
                $count = $this->table->save($form);
                if ($count == 0) {
                    $this->rollback();
                    return 0;
                }
                $this->commit();
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
            } catch (Exception $e) {
                $this->rollback();
                $this->log->err($e);
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
        $where = " WHERE $appFilter AND owi.status = 'Completed' ".(isset($where) ? " AND $where" : "");
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
        $select = "SELECT distinct f1.data,f1.uuid as fileId,owi.status,owi.process_instance_id as workflowInstanceId,ox_workflow.name,ox_file_attribute.field_value $fromQuery $where $sort $pageSize $offset";
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
            $this->log->err($e);
            return 0;
        }
    }
}
