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

    public function __construct($config, $dbAdapter, WorkflowTable $table, FormService $formService, FieldService $fieldService, FileService $fileService, WorkflowFactory $workflowFactory, ActivityService $activityService)
    {
        parent::__construct($config, $dbAdapter);
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
    public function deploy($file, $appId, $data)
    {
        $query = "SELECT * FROM `ox_app` WHERE uuid = '".$appId."';";
        $resultSet = $this->executeQuerywithParams($query)->toArray();
        $appId = $resultSet[0]['id'];
        $baseFolder = $this->config['UPLOAD_FOLDER'];
        $workflowName = $data['name'];
        if (!isset($appId)) {
            return 0;
        }
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
        $workFlowStorageFolder = $baseFolder."app/".$appId."/workflow/";
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
            $this->deleteWorkflow($appId, $workFlowId);
            return 1;
        }
        $activity = $this->getProcessManager()->parseBPMN($workFlowStorageFolder.$fileName, $appId, $workFlowId);
        $startFormId = null;
        $workFlowList = array();
        $workFlowFormIds = array();
        if (isset($activity)) {
            foreach ($activity as $activity) {
                $activityData = array();
                if (isset($activity['form']['properties'])) {
                    $activityProperties = json_decode($activity['form']['properties'], true);
                }
                $oxForm = new Activity();
                $oxForm->exchangeArray($activity['activity']);
                $oxFormProperties = $oxForm->getKeyArray();
                if (isset($activityProperties)) {
                    foreach ($activityProperties as $activityKey => $activityValue) {
                        if (in_array($activityKey, $activityProperties)) {
                            $oxForm->__set($key, $activityValue);
                        }
                    }
                }
                $activityData = $oxForm->toArray();
                try {
                    $activityResult = $this->activityService->createActivity($appId, $activityData);
                    $activityIdArray[] = $activityData['id'];
                    if (isset($activity['start_form'])) {
                        $startFormId = $activity['start_form'];
                    }
                    if ($activityResult) {
                        if (!$this->generateFields($activity['fields'], $appId, $activityData['id'], $workFlowId)) {
                            return 0;
                        }
                    } else {
                        $activityResult = $this->activityService->deleteActivity($activityData['id']);
                        return 0;
                    }
                } catch (Exception $e) {
                    foreach ($activityIdArray as $activityCreatedId) {
                        $id = $this->activityService->deleteActivity($activityCreatedId);
                    }
                    return 0;
                }
            }
        }
        if (isset($workflowName)) {
            $deployedData = array('id'=>$workFlowId,'app_id'=>$appId,'name'=>$workflowName,'process_id'=>$processId,'form_id'=>$startFormId,'file'=>$workFlowStorageFolder.$fileName);
            $workFlow = $this->saveWorkflow($appId, $deployedData);
        }
        return $deployedData?$deployedData:0;
    }
    public function saveWorkflow($appId, &$data)
    {
        $data['app_id'] = $appId;
        if (!isset($data['id'])) {
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
        }
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
                return 0;
                break;
                default:
                $this->rollback();
                return 0;
                break;
            }
        }
        return $count;
    }
    private function generateFields($fieldsList, $appId, $activityId, $workFlowId)
    {
        $i=0;
        $fieldIdArray = array();
        $existingFields = $this->fieldService->getFields($appId, array('workflow_id'=>$workFlowId));
        foreach ($fieldsList as $field) {
            $oxField = new Field();
            // $query = "SELECT * FROM `ox_app` WHERE uuid = '".$appId."';";
            // $resultSet = $this->executeQuerywithParams($query)->toArray();
            // $appId = $resultSet[0]['id'];
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
                $createFormFieldEntry = $this->createActivityFieldEntry($activityId, $fieldData['id']);
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

    private function createActivityFieldEntry($activityId, $fieldId)
    {
        $this->beginTransaction();
        try {
            $insert = "INSERT INTO `ox_activity_field` (`activity_id`,`field_id`) VALUES ($activityId,$fieldId)";
            $resultSet = $this->executeQuerywithParams($insert);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
    }

    public function updateWorkflow($id, &$data)
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
        $workflow = new Workflow();
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
            return 0;
        }
        return $count;
    }


    public function deleteWorkflow($appId, $id)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($id, ['app_id'=>$appId]);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
        }
        
        return $count;
    }

    public function getWorkflows($appId=null, $filterArray = array())
    {
        if (isset($appId)) {
            $filterArray['app_id'] = $appId;
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
        if (isset($appId)) {
            $params['app_id'] = $appId;
        }
        if (isset($id)) {
            $params['id'] = $id;
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
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_field')
        ->columns(array("*"))
        ->where(array('workflow_id' => $workflowId,'app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        return $response;
    }
    public function getForms($appId, $workflowId)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_form')
        ->columns(array("*"))
        ->where(array('workflow_id' => $workflowId,'app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        return $response;
    }

    public function getFile($params)
    {
        if (isset($params['instanceId'])) {
            return $this->fileService->getFile($params['instanceId']);
        } else {
            return 0;
        }
    }
    public function deleteFile($params)
    {
        if (isset($params['instanceId'])) {
            return $this->fileService->deleteFile($params['instanceId']);
        } else {
            return 0;
        }
    }

    public function getAssignments($appId)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_workflow')
        ->columns(array("*"))
        ->where(array('app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        if (count($response)==0) {
            return 0;
        }
        $processKeys = array_column($response, 'process_keys');
        // foreach ($response as $workflow) {
        // print_r($workflow);
        // foreach ($workflow['process_ids'] as $process_id) {
        // print_r(AuthContext::get(AuthConstants::USER_ID));
        $assignments[] = $this->activityEngine->getActivitiesByUser(AuthContext::get(AuthConstants::USERNAME), array('processDefinitionKeyIn'=>implode(",", $processKeys)));
        // }
        // }
        return $response;
    }
}
