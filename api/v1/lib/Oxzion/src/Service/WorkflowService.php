<?php
namespace Oxzion\Service;

use Zend\Db\Sql\Sql;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Oxzion\Model\Workflow;
use Oxzion\Model\WorkflowTable;
use Oxzion\Model\Form;
use Oxzion\Model\Activity;
use Oxzion\Model\ActivityTable;
use Oxzion\Service\FormService;
use Oxzion\Service\ActivityService;
use Oxzion\Model\FormTable;
use Oxzion\Model\Field;
use Oxzion\Service\FieldService;
use Oxzion\Model\FieldTable;
use Oxzion\Workflow\WorkFlowFactory;
use Oxzion\Utils\FileUtils;
use Oxzion\Service\FileService;
use Workflow\Model\WorkflowInstance;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\FilterUtils;
use Exception;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class WorkflowService extends AbstractService
{
    private $id;
    private $baseFolder;

    /**
    * @ignore table
    */
    private $table;
    protected $config;
    protected $processManager;
    protected $fileService;
    protected $formService;
    protected $fieldService;
    protected $processEngine;
    protected $activityEngine;
    protected $activityService;
    static $field= array('workflow_name' => 'ox_workflow.name');

    public function __construct($config, $dbAdapter, WorkflowTable $table, FormService $formService, FieldService $fieldService, FileService $fileService, WorkflowFactory $workflowFactory, ActivityService $activityService)
    {
        $logger = new Logger();
        $writer = new Stream(__DIR__ . '/../../../../logs/workflowservice.log');
        $logger->addWriter($writer);
        parent::__construct($config, $dbAdapter,$logger);
        $this->baseFolder = $this->config['UPLOAD_FOLDER'];
        $this->table = $table;
        $this->config = $config;
        $this->workFlowFactory = $workflowFactory;
        $this->processManager = $this->workFlowFactory->getProcessManager();
        $this->formService = $formService;
        $this->fieldService = $fieldService;
        $this->fileService = $fileService;
        $this->processEngine = $this->workFlowFactory->getProcessEngine();
        $this->activityEngine = $this->workFlowFactory->getActivity();
        $this->activityService = $activityService;
    }
    public function setProcessEngine($processEngine)
    {
        $this->processEngine = $processEngine;
    }
    public function setProcessManager($processManager)
    {
        $this->processManager = $processManager;
    }
    public function getProcessManager()
    {
        return $this->processManager;
    }
    public function deploy($file, $appUuid, $data,$entityId)
    {
        $query = "SELECT * FROM `ox_app` WHERE uuid = :appUuid;";
        $queryParams = array("appUuid" => $appUuid);
        $resultSet = $this->executeQuerywithBindParameters($query,$queryParams)->toArray();
        $appId = $resultSet[0]['id'];
        $baseFolder = $this->config['UPLOAD_FOLDER'];
        $workflowName = $data['name'];
        if (!isset($appId)) {
            return 0;
        }
        $data['entity_id'] = $entityId;
        if (!isset($data['workflowId'])) {
            try {
                $this->saveWorkflow($appId, $data);
                $workflow = $data;
            } catch (Exception $e) {
                return 0;
            }
            $workFlowId = $data['id'];
        } else {
            $workFlowId = $data['workflowId'];
        }
        $workFlowStorageFolder = $baseFolder."app/".$appId."/entity/";
        $fileName = FileUtils::storeFile($file, $workFlowStorageFolder);
        try {
            $processIds = $this->getProcessManager()->deploy($workflowName, array($workFlowStorageFolder.$fileName));
            if ($processIds) {
                if (count($processIds)==1) {
                    $processId = $processIds[0];
                } else {
                    $this->deleteWorkflow($appId, $workFlowId);
                    return 2;
                }
            } else {
                $this->deleteWorkflow($appId, $workFlowId);
                return 1;
            }
        } catch (Exception $e) {
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            $this->deleteWorkflow($appId, $workFlowId);
            throw $e;
        }
        $processes = $this->getProcessManager()->parseBPMN($workFlowStorageFolder.$fileName, $appId);
        $startFormId = null;
        $workFlowList = array();
        $workFlowFormIds = array();
        if (isset($processes)) {
            foreach ($processes as $process) {
                $activityData = array();
                if (isset($process['form']['properties'])) {
                    $formProperties = json_decode($process['form']['properties'], true);
                }
                $oxForm = new Form();
                $oxForm->exchangeArray($process['form']);
                $oxFormProperties = $oxForm->getKeyArray();
                if (isset($formProperties)) {
                    foreach ($formProperties as $formKey => $formValue) {
                        if (in_array($formKey, $oxFormProperties)) {
                            $oxForm->__set($formKey, $formValue);
                        }
                    }
                }

                $formData = $oxForm->toArray();
                $formData['entity_id'] = $entityId;
                $formData['workflow_id'] = $workFlowId;
                $formResult = $this->formService->createForm($appUuid, $formData);
                $startFormId = $formData['id'];
                foreach ($process['activity'] as $activity) {
                    $oxActivity = new Activity();
                    $oxActivity->exchangeArray($activity);
                    $oxFormProperties = $oxActivity->getKeyArray();
                    if (isset($activityProperties)) {
                        foreach ($activityProperties as $activityKey => $activityValue) {
                            if (in_array($activityKey, $activityProperties)) {
                                $oxActivity->__set($key, $activityValue);
                            }
                        }
                    }
                    $activityData = $oxActivity->toArray();
                    try {
                        if(isset($activity['form'])){
                            $formTemplate = json_decode($activity['form'],true);
                            $activityData['template'] = $formTemplate['template'];
                            $activityData['entity_id'] = $entityId;
                        }
                        $activityData['workflow_id'] = $workFlowId;
                        $activityResult = $this->activityService->createActivity($appId, $activityData,$appUuid);
                        $activityIdArray[] = $activityData['id'];
                    } catch (Exception $e) {
                        foreach ($activityIdArray as $activityCreatedId) {
                            $id = $this->activityService->deleteActivity($activityCreatedId);
                        }
                        $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
                        throw $e;
                    }
                }
            }
        }
        if (isset($workflowName)) {
            $deployedData = array('id'=>$workFlowId,'app_id'=>$appId,'name'=>$workflowName,'process_id'=>$processId,'form_id'=>$startFormId,'file'=>$workFlowStorageFolder.$fileName,'entity_id'=>$entityId,'uuid'=>$workflow['uuid']);
            try {
                $workFlow = $this->saveWorkflow($appId, $deployedData);
            } catch (Exception $e){
                $this->deleteWorkflow($appId,$workflowId);
                $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
                 throw $e;
            }
        }
        return $deployedData?$deployedData:0;
    }
    public function saveWorkflow($appId, &$data)
    {
        if(isset($appId)){
            if ($app = $this->getIdFromUuid('ox_app', $appId)) {
                $appId = $app;
            }
        } else {
            return 0;
        }
        $data['app_id'] = $appId;
        if (!isset($data['id']) || $data['id']==0) {
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
            // $data['org_id'] = isset($data['org_id']) ? $data['org_id'] :  AuthContext::get(AuthConstants::ORG_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
        }
        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] :  UuidUtil::uuid();
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form = new Workflow();
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
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
                $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
                throw $e;;
                break;
                default:
                $this->rollback();
                $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
                throw $e;
                break;
            }
        }
        return $count;
    }
    private function generateFields($fieldsList, $appId, $activityId, $workFlowId,$type)
    {
        $i=0;
        $fieldIdArray = array();
        $existingFields = $this->fieldService->getFields($appId, array('workflow_id'=>$workFlowId));
        foreach ($fieldsList as $field) {
            $oxField = new Field();
            $field['app_id'] = $appId;
            $field['workflow_id'] = $workFlowId;
            $oxField->exchangeArray($field);
            $oxFieldProps = array();
            if (isset($field['properties'])) {
                $fieldProperties = json_decode($field['properties'], true);
                $oxFieldProperties = $oxField->getKeyArray();
                foreach ($fieldProperties as $fieldKey => $fieldValue) {
                    if (in_array($fieldKey, $fieldProperties)) {
                        $oxField->__set($fieldKey, $fieldValue);
                    } else {
                        $oxFieldProps[] = array('name'=>$fieldKey,'value'=>$fieldValue);
                    }
                }
            }
            $oxField->__set('properties', json_encode($oxFieldProps));
            $fieldData = $oxField->toArray();
            try {
                $fieldResult = $this->fieldService->saveField($appId, $fieldData);
                $fieldIdArray[] = $fieldData['id'];
                $createFormFieldEntry = $this->createFormFieldEntry($activityId, $fieldData['id']);
            } catch (Exception $e) {
                foreach ($fieldIdArray as $fieldId) {
                    $id = $this->fieldService->deleteField($fieldId);
                    return 0;
                }
            }
            $i++;
        }
        if (count($fieldsList)==$i) {
            return 1;
        } else {
            return 0;
        }
    }

    private function createFormFieldEntry($formId, $fieldId)
    {
        $this->beginTransaction();
        try {
            $insert = "INSERT INTO `ox_form_field` (`form_id`,`field_id`) VALUES (:formId,:fieldId)";
            $insertParams = array("formId" => $formId, "fieldId" => $fieldId);
            $resultSet = $this->executeQuerywithBindParameters($insert,$insertParams);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
    }

    public function updateWorkflow($appUuid,$id, &$data)
    {
        $obj = $this->table->getByUuid($id,array());
        if (is_null($obj)) {
            return 0;
        }
        $data['id'] = $this->getIdFromUuid('ox_workflow',$id);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s'); 
        // $data['app_id'] = $this->getIdFromUuid('ox_app',$appUuid);  
        $workflow = new Workflow();
        $changedArray = array_merge($obj->toArray(), $data);
        $workflow->exchangeArray($changedArray); 
        $workflow->validate();
        $this->beginTransaction();
        $count = 0;
        try { 
            $count = $this->table->save($workflow);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) { 
            $this->rollback();
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
        return $count;
    }

    public function deleteWorkflow($appUuid, $workflowUuid)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($this->getIdFromUuid('ox_workflow',$workflowUuid), ['app_id'=>$this->getIdFromUuid('ox_app',$appUuid)]);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
        
        return $count;
    }

    public function getWorkflows($appUuid=null, $filterArray = array())
    {
        if (isset($appId)) {
            $filterArray['app_id'] = $this->getIdFromUuid('ox_app',$appUuid);
        }
        $resultSet = $this->getDataByParams('ox_workflow', array("*"), $filterArray, null);
        $response = array();
        $response['data'] = $resultSet->toArray();
        return $response;
    }

    public function getWorkflow($appId=null, $id=null)
    {
        $sql = $this->getSqlObject();
        $params = array();
        if(isset($params['app_id'])){
            if ($app = $this->getIdFromUuid('ox_app', $params['app_id'])) {
                $appId = $app;
            } else {
                $appId = $params['app_id'];
            }
        } else {
            $appId = null;
        }
        if (isset($id)) {
            if ($workflow = $this->getIdFromUuid('ox_workflow', $id)) {
                $workflowId = $workflow;
            } else {
                $workflowId = $id;
            }
            $params['id'] = $workflowId;
        }
        if (isset($appId)) {
            $params['app_id'] = $appId;
        }
        $select = $sql->select();
        $select->from('ox_workflow')
        ->columns(array("*"))
        ->where($params);
        $response = $this->executeQuery($select)->toArray();
        if (count($response)==0) {
            return 0;
        }
        return $response[0];
    }

    public function getFields($appId, $workflowId)
    {
        try{
            $queryString = "Select * from ox_field where workflow_id=:workflowId and app_id=:appId";
            $queryParams = array("workflowId" => $workflowId,"appId" => $appId); 
            $response = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
            return $response;
        }catch(Exception $e){
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
    }

    public function getForms($appId, $workflowId)
    {
        try{
            $queryString = "Select * from ox_form where workflow_id=:workflowId and app_id=:appId";
            $queryParams = array("workflowId" => $workflowId,"appId" => $appId); 
            $response = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
            return $response;
        }catch(Exception $e){
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
    }

    public function getStartForm($appId, $workflowId)
    {
        $sql = $this->getSqlObject();
        if ($app = $this->getIdFromUuid('ox_app', $appId)) {
            $appId = $app;
        } else {
            $appId = $appId;
        }
        if ($workflow = $this->getIdFromUuid('ox_workflow', $workflowId)) {
            $workflowId = $workflow;
        } else {
            $workflowId = $workflowId;
        }
        $select = "select ox_form.template as content,ox_form.uuid as id
         from ox_form
          left join ox_workflow on ox_workflow.form_id=ox_form.id 
          left join ox_app on ox_app.id=ox_workflow.app_id 
          where ox_workflow.id=:workflowId and ox_app.id=:appId;";
        $queryParams = array("workflowId" => $workflowId, "appId" => $appId);
        $response = $this->executeQueryWithBindParameters($select,$queryParams)->toArray();
        return $response;
    }

    // public function getFile($params)
    // {
    //     if (isset($params['instanceId'])) {
    //         return $this->fileService->getFile($params['instanceId']);
    //     } else {
    //         return 0;
    //     }
    // }
    // public function deleteFile($params)
    // {
    //     if (isset($params['instanceId'])) {
    //         return $this->fileService->deleteFile($params['instanceId']);
    //     } else {
    //         return 0;
    //     }
    // }

    public function getAssignments($appId,$filterParams)
    {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        if(!empty($filterParams)){
            $filterParamsArray = json_decode($filterParams['filter'],TRUE);
        }
        $sort = "";
        if(count($filterParams) > 0 || sizeof($filterParams) > 0){
            if(isset($filterParams['filter'])){
                $filterArray = json_decode($filterParams['filter'],true);
                if(isset($filterArray[0]['filter'])){
                    $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
                    $filterList = $filterArray[0]['filter']['filters'];
                    $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic,self::$field);
                }

                if(isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0){
                    $sort = $filterArray[0]['sort'];
                    $sort = FilterUtils::sortArray($sort,self::$field);
                }
            }
        }
        
        $appFilter = "ox_app.uuid ='".$appId."'";
        $fromQuery = "FROM ox_workflow
                      INNER JOIN ox_app on ox_app.id = ox_workflow.app_id
                      INNER JOIN ox_workflow_instance on ox_workflow_instance.workflow_id = ox_workflow.id
                      INNER JOIN ox_activity on ox_activity.workflow_id = ox_workflow.id
                      INNER JOIN ox_activity_instance ON ox_activity_instance.activity_id = ox_activity.id
                      INNER JOIN ox_activity_instance_assignee ON ox_activity_instance_assignee.activity_instance_id = ox_activity_instance.id
                      LEFT JOIN ox_user_group ON ox_activity_instance_assignee.group_id = ox_user_group.group_id";
        $whereQuery = " WHERE (ox_user_group.avatar_id = $userId OR ox_activity_instance_assignee.user_id = $userId) AND $appFilter AND ox_activity_instance.status = 'In Progress'";                      
        if(!empty($sort)){
            $sort = " ORDER BY ".$sort;
        }
        $pageSize = "LIMIT ".(isset($filterParamsArray[0]['take']) ? $filterParamsArray[0]['take'] : 20);
        $offset = "OFFSET ".(isset($filterParamsArray[0]['skip']) ? $filterParamsArray[0]['skip'] : 0);

        $countQuery = "SELECT count(distinct ox_activity_instance.id) as `count` $fromQuery $whereQuery";
        $countResultSet = $this->executeQuerywithParams($countQuery)->toArray();

        $querySet = "SELECT distinct ox_workflow.name as workflow_name,
        ox_activity_instance.activity_instance_id as activityInstanceId,ox_workflow_instance.process_instance_id as workflowInstanceId,
         ox_activity.name as activityName,
        CASE WHEN ox_activity_instance_assignee.group_id is null then false
        else true end as to_be_claimed  $fromQuery $whereQuery $sort $pageSize $offset";   
        $resultSet = $this->executeQuerywithParams($querySet)->toArray();
        return array('data' => $resultSet,'total' => $countResultSet[0]['count']);
    }
}
