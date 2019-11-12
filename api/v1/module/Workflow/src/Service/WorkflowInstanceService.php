<?php
namespace Workflow\Service;

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
use Workflow\Model\WorkflowInstance;
use Workflow\Model\WorkflowInstanceTable;
use Workflow\Service\ActivityInstanceService;

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
            $query = "select oxi.id,oxi.process_instance_id ,oxi.app_id,oxi.org_id,ow.uuid as workflow_id from ox_workflow_instance as oxi join ox_workflow as ow on oxi.workflow_id = ow.id where oxi.org_id=? and oxi.process_instance_id=?";

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
        $this->logger->info("Starting StartWorkflow method");

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

        $workflow = $this->workflowService->getWorkflow($workflowId);
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
            $fileData = $params;

            if (isset($workflowInstance['parent_workflow_instance_id'])) {
                $fileDataResult = $this->fileService->getFileByWorkflowInstanceId($workflowInstance['parent_workflow_instance_id'], false);
                $oldFileData = json_decode($fileDataResult['data'], true);
                $fileData = array_merge($oldFileData, $fileData);
                $fileData['parent_id'] = $fileDataResult['id'];
            }
            $this->beginTransaction();
            $file = $this->fileService->createFile($fileData, $workflowInstance['id']);
            $this->logger->info("File created -" . $file);
            $params['fileId'] = $fileData['uuid'];
            $params['workflow_instance_id'] = $workflowInstance['id'];
            $this->logger->info("Checking something" . print_r($workflow['process_id'], true));
            $this->logger->info("Checking Params" . print_r($params, true));
            $workflowInstanceId = $this->processEngine->startProcess($workflow['process_id'], $params);
            $this->logger->info("WorkflowInstanceId created" . print_r($workflowInstanceId, true));
            $updateQuery = "UPDATE ox_workflow_instance SET process_instance_id=:process_instance_id where id = :workflowInstanceId";
            $updateParams = array('process_instance_id' => $workflowInstanceId['id'], 'workflowInstanceId' => $workflowInstance['id']);
            $this->logger->info("Query1 - $updateQuery with Parametrs - " . print_r($updateParams, true));
            $update = $this->executeUpdateWithBindParameters($updateQuery, $updateParams);
            $this->setupIdentityField($params);
            $this->logger->info("Completed somthing");
            $this->commit();
        } catch (Exception $e) {
            $this->logger->info("Mulpa somthing");
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
        if (isset($params['identity_field'])) {
            $data = $params;
            $test = $this->userService->checkAndCreateUser(array(), $data, true);
            try {
                $this->beginTransaction();
                $query = "INSERT INTO ox_wf_user_identifier(`workflow_instance_id`,`user_id`,`identifier_name`,`identifier`) VALUES (:workflowInstanceId,:userId,:identifierName,:identifier)";
                $queryParams = array("workflowInstanceId" => $params['workflow_instance_id'],
                    "userId" => $data['id'],
                    "identifierName" => $params['identity_field'],
                    "identifier" => $params[$params['identity_field']]);
                $this->logger->info("Query2 - $query with Parametrs - " . print_r($queryParams, true));
                $resultSet = $this->executeQueryWithBindParameters($query, $queryParams);
                $this->commit();
            } catch (Exception $e) {
                $this->logger->info("errrrrrrrrrrrrrrrrrrr");
                $this->logger->error($e->getMessage(), $e);
                $this->rollback();
                throw $e;
            }
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
        $query = "select * from ox_file where workflow_instance_id=?";
        $queryParams = array($workflowInstance[0]['id']);
        $existingFile = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        if (isset($existingFile[0])) {
            $this->logger->info(WorkflowInstanceService::class . "FILE UPDATE-----" . print_r($existingFile, true));
            $file = $this->fileService->updateFile($params, $existingFile[0]['uuid']);
            $workflowInstanceId = $this->activityEngine->submitTaskForm($activityId, $params);

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
            if (isset($data['variables'])) {
                $workflowInstance = $this->setupWorkflowInstance($data['variables']['workflowId'], $data['processInstanceId'], $data['variables']);
            } else {
                $this->logger->info("Invalid Data ----- " . print_r($data, true));
                throw new InvalidParameterException("Invalid Data" . $data);
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
        try {
            $this->beginTransaction();
            $updateQuery = "UPDATE ox_workflow_instance SET status=:status where process_instance_id = :workflowInstanceId";
            $updateParams = array('status' => 'Completed', 'workflowInstanceId' => $params['processInstanceId']);
            $update = $this->executeUpdateWithBindParameters($updateQuery, $updateParams);
            $this->commit();
            return $update->getAffectedRows();
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
        } else {
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
        $query = "select app_id, id from ox_workflow where id=? or uuid=?";
        $queryParams = array($workflowId, $workflowId);
        $workflowResultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();

        if (count($workflowResultSet)) {
            $data = array('workflow_id' => $workflowResultSet[0]['id'], 'app_id' => $workflowResultSet[0]['app_id'], 'org_id' => $orgId, 'process_instance_id' => $processInstanceId, 'status' => "In Progress", 'date_created' => $dateCreated, 'created_by' => $createdBy);
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

    public function getFileList($params, $filterParams = null)
    {
        if (!empty($filterParams)) {
            $filterParamsArray = json_decode($filterParams['filter'], true);
            if (array_key_exists("sort", $filterParamsArray[0])) {
                $sortParam = $filterParamsArray[0]['sort'];
            }
        }
        $fieldNameList = "";
        $appId = $this->getIdFromUuid('ox_app', $params['appId']);
        if (isset($params['userId'])) {
            $userId = $this->getIdFromUuid('ox_user', $params['userId']);
        }
        if (isset($params['workflowId'])) {
            $workflowId = $params['workflowId'];
        }

        $queryParams = array();
        $appFilter = "h.app_id = :appId";
        $queryParams['appId'] = $appId;

        if (isset($workflowId)) {
            $appFilter .= " AND h.uuid = :workflowId";
            $queryParams['workflowId'] = $workflowId;
        }

        if (isset($filterParamsArray[0]['filter'])) {
            $filterlogic = isset($filterParamsArray[0]['filter']['logic']) ? $filterParamsArray[0]['filter']['logic'] : " AND ";
            $cnt = 1;
            $fieldParams = array();
            foreach ($filterParamsArray[0]['filter']['filters'] as $key => $value) {
                $fieldNameList .= $fieldNameList !== "" ? "," : $fieldNameList;
                $fieldNameList .= ':val' . $cnt;
                $fieldParams['val' . $cnt] = $value['field'];
                $cnt++;
            }
            $filterData = $filterParamsArray[0]['filter']['filters'];
        }

        $pageSize = " LIMIT " . (isset($filterParamsArray[0]['take']) ? $filterParamsArray[0]['take'] : 20);
        $offset = " OFFSET " . (isset($filterParamsArray[0]['skip']) ? $filterParamsArray[0]['skip'] : 0);
        $where = " WHERE $appFilter AND g.status = 'Completed' and";

        $fromQuery = " from ox_file as a
            join ox_form as b on (a.entity_id = b.entity_id)
            join ox_form_field as c on (c.form_id = b.id)
            join ox_field as d on (c.field_id = d.id)
            join ox_app as f on (f.id = b.app_id)";
        if (isset($userId)) {
            $fromQuery .= " join ox_wf_user_identifier on ox_wf_user_identifier.identifier_name = d.name";
        }
        $fromQuery .= " inner join ox_workflow_instance as g on a.workflow_instance_id = g.id
            inner join ox_workflow as h on h.id = g.workflow_id
            left join (SELECT workflow_instance_id, max(latest) as latest from ox_file
            group by workflow_instance_id) as f2 on a.workflow_instance_id = f2.workflow_instance_id
            and a.latest = f2.latest";

        $prefix = 1;
        $whereQuery = "";
        $joinQuery = "";
        $sort = "";
        // print_r($filterParams);exit;
        if (!empty($filterData)) {
            foreach ($filterData as $val) {
                $tablePrefix = "tblf" . $prefix;
                $fieldId = $this->fileService->getFieldDetaild($val['field']);
                if (!empty($val) && !empty($fieldId)) {
                    $joinQuery .= " left join ox_file_attribute as " . $tablePrefix . " on (a.id =" . $tablePrefix . ".file_id) ";
                    $valueTransform = $this->getFieldType($fieldId, $tablePrefix);
                    $filterOperator = $this->processFilters($val);
                    $whereQuery .= " (" . $tablePrefix . ".field_id = " . $fieldId['id'] . " and " . $valueTransform . "" . $filterOperator["operation"] . "'" . $filterOperator["operator1"] . "" . $val['value'] . "" . $filterOperator["operator2"] . "') and ";
                }
                if (isset($filterParamsArray[0]['sort']) && count($filterParamsArray[0]['sort']) > 0) {
                    if ($sortParam[0]['field'] === $val['field']) {
                        $sort = "ORDER BY " . $tablePrefix . ".field_value";
                    }
                }
                $prefix += 1;
            }
        }
        $where .= " " . $whereQuery . "";
        $fromQuery .= " " . $joinQuery . "";

        if (isset($userId)) {
            $where = $where . " ox_wf_user_identifier.user_id = :userId and";
            $queryParams['userId'] = $userId;
        }

        $where .= " 1";

        // echo $where;exit;
        $countQuery = "SELECT count(distinct a.id) as `count` $fromQuery $where";
        $countResultSet = $this->executeQueryWithBindParameters($countQuery, $queryParams)->toArray();

        $select = "SELECT a.data, a.uuid as fileId, g.status, g.process_instance_id as workflowInstanceId, h.name $fromQuery $where group by a.id $sort $pageSize $offset";
        $resultSet = $this->executeQueryWithBindParameters($select, $queryParams)->toArray();
        return array('data' => $resultSet, 'total' => $countResultSet[0]['count']);
    }

    public function getFileDocumentList($params)
    {
        $selectQuery = 'select ox_field.name, ox_file_attribute.field_value from ox_file
        inner join ox_file_attribute on ox_file_attribute.file_id = ox_file.id
        inner join ox_field on ox_field.id = ox_file_attribute.field_id
        inner join ox_app on ox_field.app_id = ox_app.id
        where ox_file.org_id=:organization and ox_app.uuid=:appUuid and ox_field.data_type=:dataType
        and ox_file.uuid=:fileUuid';
        $selectQueryParams = array('organization' => AuthContext::get(AuthConstants::ORG_ID),
            'appUuid' => $params['appId'],
            'fileUuid' => $params['fileId'],
            'dataType' => 'document');
        try {
            $selectResultSet = $this->executeQueryWithBindParameters($selectQuery, $selectQueryParams)->toArray();
            return $selectResultSet;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            return 0;
        }
    }

    private function getIdFromProcessInstanceId($processInstanceId)
    {
        $query = "Select id from ox_workflow_instance where process_instance_id=:processInstanceId;";
        $params = array("processInstanceId" => $processInstanceId);
        return $result = $this->executeQueryWithBindParameters($query, $params)->toArray();
    }

    public function getFieldType($value, $prefix)
    {
        switch ($value['data_type']) {
            case 'date':
                $castString = "CAST($prefix.field_value AS DATETIME)";
                break;
            case 'int':
                $castString = "CAST($prefix.field_value AS INT)";
                break;
            default:
                $castString = "($prefix.field_value)";
        }
        return $castString;
    }

    public function processFilters($filterList)
    {
        $operator = $filterList['operator'];
        $field = $filterList['field'];
        $operatorp1 = '';
        $operatorp2 = '';
        if ($operator == 'startswith') {
            $operatorp2 = '%';
            $operation = ' like ';
        } elseif ($operator == 'endswith') {
            $operatorp1 = '%';
            $operation = ' like ';
        } elseif ($operator == 'eq') {
            $operation = ' = ';
        } elseif ($operator == 'neq') {
            $operation = ' <> ';
        } elseif ($operator == 'contains') {
            $operatorp1 = '%';
            $operatorp2 = '%';
            $operation = ' like ';
        } elseif ($operator == 'doesnotcontain') {
            $operatorp1 = '%';
            $operatorp2 = '%';
            $operation = ' NOT LIKE ';
        } elseif ($operator == 'isnull' || $operator == 'isempty') {
            $value = '';
            $operation = ' = ';
        } elseif ($operator == 'isnotnull' || $operator == 'isnotempty') {
            $value = '';
            $operation = ' <> ';
        } elseif ($operator == 'lte') {
            $operation = ' <= ';
        } elseif ($operator == 'lt') {
            $operation = ' < ';
        } elseif ($operator == 'gt') {
            $operation = ' > ';
        } elseif ($operator == 'gte') {
            $operation = ' >= ';
        } else {
            $operatorp1 = '%';
            $operatorp2 = '%';
            $operation = ' like ';
        }

        return $returnData = array(
            "operation" => $operation,
            "operator1" => $operatorp1,
            "operator2" => $operatorp2,
        );
    }
}
