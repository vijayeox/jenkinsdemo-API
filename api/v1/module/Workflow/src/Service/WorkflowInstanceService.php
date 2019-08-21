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
use Exception;

class WorkflowInstanceService extends AbstractService
{
    protected $workflowService;
    protected $fileService;
    protected $processEngine;
    protected $activityEngine;

    public function __construct(
        $config,
        $dbAdapter,
        WorkflowInstanceTable $table,
        FileService $fileService,
        WorkflowService $workflowService,
        WorkflowFactory $workflowFactory
    ) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->fileService = $fileService;
        $this->workflowService = $workflowService;
        $this->workFlowFactory = $workflowFactory;
        $this->processEngine = $this->workFlowFactory->getProcessEngine();
        $this->activityEngine = $this->workFlowFactory->getActivity();
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
        }
        
        return $count;
    }

    public function getWorkflowInstances($appId=null, $filterArray = array())
    {
        if (isset($appId)) {
            $filterArray['app_id'] = $appId;
        }
        $resultSet = $this->getDataByParams('ox_workflow_instance', array("*"), $filterArray, null);
        $response = array();
        $response['data'] = $resultSet->toArray();
        return $response;
    }
    public function getWorkflowInstance($appId, $id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_workflow_instance')
        ->columns(array("*"))
        ->where(array('id' => $id,'app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        if (count($response)==0) {
            return 0;
        }
        return $response[0];
    }

    public function executeWorkflow($params, $id=null)
    {
        $workflowId = $params['workflowId'];
        $workFlowFlag = 1;
        $workflow = $this->workflowService->getWorkflow(null, $workflowId);
        if (empty($workflow)) {
            $workFlowFlag= 0;
            return 0;
        }
        if (!isset($params['activityId'])) {
            $params['form_id'] = $workflow['form_id'];
            $activityId = $params['form_id'];
        } else {
            $params['activity_id'] = $params['activityId'];
            $activityId = $params['activityId'];
        }
        if(!isset($params['orgid'])){
            $params['orgid'] = AuthContext::get(AuthConstants::ORG_UUID);
        }
        if(!isset($params['app_id'])){
            $params['app_id'] = $workflow['app_id'];
        }
        if(!isset($params['created_by'])){
            $params['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        }
        if (isset($id)) {
            return $this->fileService->updateFile($params, $id);
        } else {
            if ($workFlowFlag) {
                if ($workflow['form_id']==$params['form_id'] || $params['activityId']==null) {
                    $workflowInstanceId = $this->processEngine->startProcess($workflow['process_id'], $params);
                    $workflowInstance = $this->setupWorkflowInstance($workflowId, $workflowInstanceId['id'],$params);
                } else {
                    $workflowInstanceId = $this->activityEngine->submitTaskForm($activityId, $params);
                }
            } else {
                return 0;
            }
            return $this->fileService->createFile($params, $workflowInstanceId['id']);
        }
        return 0;
    }
    
    public function setupWorkflowInstance($workflowId, $processInstanceId,$params =null)
    {
        $form = new WorkflowInstance();
        if (isset($params['orgid'])) {
            if ($org = $this->getIdFromUuid('ox_organization', $params['orgid'])) {
                $orgId = $org;
            } else {
                $orgId = $params['orgid'];
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
        $dateCreated = date('Y-m-d H:i:s');
        $query = "select app_id from ox_workflow where id = $workflowId";
        $resultSet = $this->executeQuerywithParams($query)->toArray();
        $data = array('workflow_id'=> $workflowId,'app_id'=> $resultSet[0]['app_id'],'org_id'=> $orgId,'process_instance_id'=>$processInstanceId,'status'=>"In Progress",'date_created'=>$dateCreated,'created_by'=>$createdBy);
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
            return 0;
        }
        return $data;
    }
}
