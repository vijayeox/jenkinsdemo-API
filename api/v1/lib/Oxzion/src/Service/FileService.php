<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\EntityNotFoundException;
use Oxzion\Model\File;
use Oxzion\Model\FileTable;
use Oxzion\ServiceException;
use Oxzion\Utils\UuidUtil;
use Oxzion\Messaging\MessageProducer;

class FileService extends AbstractService
{
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, FileTable $table, FormService $formService,MessageProducer $messageProducer)
    {
        parent::__construct($config, $dbAdapter);
        $this->messageProducer = $messageProducer;
        $this->table = $table;
        $this->config = $config;
        $this->dbAdapter = $dbAdapter;
        // $emailService = new EmailService($config, $dbAdapter, Oxzion\Model\Email);
    }

    /**
     * Create File Service
     * @method createFile
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               formid : integer,
     *               Fields from Form
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created File.
     */
    public function createFile(&$data, $workflowInstanceId = null)
    {
        $this->logger->info("Data CreateFile- " . json_encode($data));
        $parentId = isset($data['parent_id']) ? $data['parent_id'] : null;
        if(isset($data['uuid'])){
            $fileId = $this->getIdFromUuid('ox_file', $data['uuid']);
            if($fileId){
                unset($data['uuid']);
            }
        }
        if (isset($data['form_id'])) {
            $formId = $this->getIdFromUuid('ox_form', $data['form_id']);
        } else {
            $formId = null;
        }
        if (isset($data['activity_id'])) {
            $activityId = $data['activity_id'];
        } else {
            $activityId = null;
        }
        $entityId = isset($data['entity_id']) ? $data['entity_id'] : null;
        $fields = $data = $this->cleanData($data);
        $this->logger->info("Data From Fileservice before encoding - " . print_r($data, true));
        $jsonData = json_encode($data);
        $data['workflow_instance_id'] = isset($workflowInstanceId) ? $workflowInstanceId : null;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['form_id'] = $formId;
        $data['parent_id'] = $parentId;
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['entity_id'] = $entityId;
        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
        $data['data'] = $jsonData;
        $file = new File();
        $file->exchangeArray($data);
        $this->logger->info("Data From Fileservice - " . print_r($data, true));
        $this->logger->info("File data From Fileservice - " . print_r($file->toArray(), true));
        // $fields = array_diff_assoc($data, $file->toArray());
        $file->validate();
        $this->beginTransaction();

        $count = 0;
        try {
            if ($parentId) {
                if (!$this->setFileLatest($parentId, 0)) {
                    throw new Exception("Could not update latest for parent file " . $data['parent_id']);
                }
            }
            $this->logger->info("FILE DATA BEFORE SAVE----" . print_r($file,true));
            $count = $this->table->save($file);
            $this->logger->info("COUNT  FILE DATA----" . $count);
            if ($count == 0) {
                throw new ServiceException("File Creation Failed", "file.create.failed");
            }
            $id = $this->table->getLastInsertValue();
            $this->logger->info("FILE ID DATA" . $id);
            $data['id'] = $id;
            $this->logger->info("FILE DATA ----- " . json_encode($data));
            $validFields = $this->checkFields($data['entity_id'], $fields, $id);
            $this->updateFileData($id, $fields);
            if (!$validFields || empty($validFields)) {
                $this->logger->info("FILE Validation ----- ");
                throw new ValidationException("Validation Errors" . json_encode($fields));
            }
            $this->logger->info("Check Fields - " . json_encode($validFields));
            $this->multiInsertOrUpdate('ox_file_attribute', $validFields, ['id']);
            $this->logger->info("Created successfully  - file record");
            $this->commit();
        } catch (Exception $e) {
            $this->logger->info("erorororor  - file record");
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $count;
    }

    private function updateFileData($id, $data)
    {
        $query = "update ox_file set data = :data where id = :id";
        $params = array('data' => json_encode($data), 'id' => $id);
        $result = $this->executeUpdateWithBindParameters($query, $params);
        return $result->getAffectedRows() > 0;
    }
    private function setFileLatest($fileId, $isLatest)
    {

        // $selectQuery = "Select data_type from ox_field where id=:fieldId;";
        // $queryParams = array("fieldId" => );
        // $resultSet = $this->executeQueryWithBindParameters($selectQuery,$queryParams)->toArray();
        $query = "update ox_file set latest = :latest where id = :fileId";
        $params = array('latest' => $isLatest, 'fileId' => $fileId);
        $result = $this->executeUpdateWithBindParameters($query, $params);
        // print_r("UpdateFileLatest - \n");
        // print_r($result->getAffectedRows());
        return $result->getAffectedRows() > 0;
    }
    /**
     * Update File Service
     * @method updateFile
     * @param array $id ID of File to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created File.
     */
    public function updateFile(&$data, $id)
    {
        if (isset($data['workflow_instance_id'])) {
            $select = "SELECT ox_file.* from ox_file
            where ox_file.workflow_instance_id=? ";
            $whereQuery = array($data['workflow_instance_id']);
            $obj = $this->executeQueryWithBindParameters($select, $whereQuery)->toArray()[0];
            if (is_null($obj)) {
                throw new EntityNotFoundException("File Id not found -- " . $id);
            }
        } else {
            $obj = $this->table->getByUuid($id);
            if (is_null($obj)) {
                throw new EntityNotFoundException("File Id not found -- " . $id);
            }
            $obj = $obj->toArray();

        }
        if (isset($data['form_uuid'])) {
            $data['form_id'] = $this->getIdFromUuid('ox_form', $data['form_uuid']);
            unset($data['form_uuid']);
        }
        if (isset($data['app_uuid'])) {
            $data['app_id'] = $this->getIdFromUuid('ox_app', $data['app_uuid']);
            unset($data['app_uuid']);
        }
        if (isset($data['form_id'])) {
            $formId = $data['form_id'];
        } else {
            $formId = null;
        }
        if (isset($data['activity_id'])) {
            $activityId = $data['activity_id'];
        } else {
            $activityId = null;
        }

        $fileObject = json_decode($obj['data'], true);

        foreach ($fileObject as $key => $fileObjectValue) {
            if (is_array($fileObjectValue)) {
                $fileObject[$key] = json_encode($fileObjectValue);
            }
        }

        foreach ($data as $key => $dataelement) {
            if (is_array($dataelement) || is_bool($dataelement)) {
                $data[$key] = json_encode($dataelement);
            }
        }

        $fields = array_merge($fileObject,$data);
        $file = new File();
        $id = $this->getIdFromUuid('ox_file', $id);
        $validFields = $this->checkFields(isset($obj['entity_id']) ? $obj['entity_id'] : null, $fields, $id);
        $dataArray = array_merge($fileObject, $fields);

        $fileObject = $obj;
        $dataArray = $this->cleanData($dataArray);
        $fileObject['data'] = json_encode($dataArray);
        $fileObject['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $fileObject['date_modified'] = date('Y-m-d H:i:s');

        $this->beginTransaction();
        try {
            $this->logger->info("Entering to Update File -" . json_encode($fileObject) . "\n");

            $file->exchangeArray($fileObject);
            $file->validate();
            $count = $this->table->save($file);

            $this->logger->info(json_encode($validFields) . "are the list of valid fields.\n");
            if ($validFields && !empty($validFields)) {
                $query = "Delete from ox_file_attribute where file_id = :fileId";
                $queryWhere = array("fileId" => $id);
                $result = $this->executeQueryWithBindParameters($query, $queryWhere);
                $this->multiInsertOrUpdate('ox_file_attribute', $validFields);
            }
            $this->logger->info("Leaving the updateFile method \n");
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $id;
    }

    /**
     * Delete File Service
     * @method deleteFile
     * @param $id ID of File to Delete
     * @return array success|failure response
     */
    public function deleteFile($id)
    {
        $params['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $sql = $this->getSqlObject();
        $params = array();
        try {
            $params['uuid'] = $id;
            $update = $sql->update();
            $update->table('ox_file')
                ->set(array('is_active' => 0))
                ->where($params);
            $response = $this->executeUpdate($update);
            return 1;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    /**
     * GET File Service
     * @method getFile
     * @param $id ID of File
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created File.
     */
    public function getFile($id)
    {
        try {
            $this->logger->info("FILE ID  ------" . json_encode($id));
            $params = array('id' => $id,
                'active' => 1,
                'orgId' => AuthContext::get(AuthConstants::ORG_ID));
            $select = "SELECT uuid, data  from ox_file where uuid = :id AND is_active = :active AND org_id = :orgId";
            $this->logger->info("Executing query $select with params " . json_encode($params));

            $result = $this->executeQueryWithBindParameters($select, $params)->toArray();
            $this->logger->info("FILE DATA ------" . json_encode($result));
            if (count($result) > 0) {
                $this->logger->info("FILE ID  ------" . json_encode($result));
                if ($result[0]['data']) {
                    $result[0]['data'] = json_decode($result[0]['data'], true);
                }
                return $result[0];
            }
            return 0;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function getFileByWorkflowInstanceId($workflowInstanceId, $isProcessInstanceId = true)
    {
        if ($isProcessInstanceId) {
            $where = "ox_workflow_instance.process_instance_id=:workflowInstanceId";
        } else {
            $where = "ox_workflow_instance.id=:workflowInstanceId";
        }
        try {
            $select = "SELECT ox_file.id,ox_file.data from ox_file
                       inner join ox_workflow_instance on ox_workflow_instance.id = ox_file.workflow_instance_id
                       where ox_file.org_id=:orgId and $where and ox_file.is_active =:isActive";
            $whereQuery = array("orgId" => AuthContext::get(AuthConstants::ORG_ID),
                "workflowInstanceId" => $workflowInstanceId,
                "isActive" => 1);
            $result = $this->executeQueryWithBindParameters($select, $whereQuery)->toArray();
            if (count($result) > 0) {
                return $result[0];
            }
            return 0;
        } catch (Exception $e) {
            $this->logger->log(Logger::ERR, $e->getMessage());
            throw $e;
        }
    }

    /**
     * @ignore checkFields
     */
    protected function checkFields($entityId, &$fieldData, $fileId)
    {
        $this->logger->info("Entering into checkFields method---EntityId : " . $entityId);
        $required = array();
        if (isset($entityId)) {
            $query = "SELECT ox_field.* from ox_field
            left join ox_app_entity on ox_app_entity.id = ox_field.entity_id
            where ox_app_entity.id=?";
            $where = array($entityId);
            $this->logger->info("Executing query - $query with  params" . json_encode($where));
            $fields = $this->executeQueryWithBindParameters($query, $where)->toArray();
            $this->logger->info("Query result" . json_encode($fields));
        } else {
            $this->logger->info("No Entity ID");
            return 0;
        }
        $sqlQuery = "SELECT * from ox_file_attribute where ox_file_attribute.file_id=?";
        $whereParams = array($fileId);
        $this->logger->info("Executing query - $sqlQuery with  params" . json_encode($whereParams));
        $fileArray = $this->executeQueryWithBindParameters($sqlQuery, $whereParams)->toArray();
        $this->logger->info("Query result" . json_encode($fileArray));
        $keyValueFields = array();
        $i = 0;
        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (($key = array_search($field['id'], array_column($fileArray, 'field_id'))) > -1) {
                    // Update the existing record
                    $keyValueFields[$i]['id'] = $fileArray[$key]['id'];
                } else {
                    // Insert the Record
                    $keyValueFields[$i]['id'] = null;
                }
                $fieldProperties = json_decode($field['template'], true);
                $this->logger->info("FIELD PROPERTIES - " . json_encode($fieldProperties));
                if (!$fieldProperties['persistent']) {
                    if (isset($fieldData[$field['name']])) {
                        unset($fieldData[$field['name']]);
                    }
                    continue;
                }
                if (isset($fieldData[$field['name']]) && is_array($fieldData[$field['name']])) {
                    $fieldData[$field['name']] = json_encode($fieldData[$field['name']]);
                }
                $keyValueFields[$i]['org_id'] = (empty($fileArray[$key]['org_id']) ? AuthContext::get(AuthConstants::ORG_ID) : $fileArray[$key]['org_id']);
                $keyValueFields[$i]['created_by'] = (empty($fileArray[$key]['created_by']) ? AuthContext::get(AuthConstants::USER_ID) : $fileArray[$key]['created_by']);
                $keyValueFields[$i]['date_created'] = (!isset($fileArray[$key]['date_created']) ? date('Y-m-d H:i:s') : $fileArray[$key]['date_created']);
                $keyValueFields[$i]['date_modified'] = date('Y-m-d H:i:s');
                $keyValueFields[$i]['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
                $keyValueFields[$i]['field_value'] = isset($fieldData[$field['name']]) ? $fieldData[$field['name']] : null;
                $keyValueFields[$i]['field_id'] = $field['id'];
                $keyValueFields[$i]['file_id'] = $fileId;
                $i++;
            }
        }
        $this->logger->info("Key Values - " . json_encode($keyValueFields));
        return $keyValueFields;
    }

    public function checkFollowUpFiles($appId, $data)
    {
        try {
            $fieldWhereQuery = $this->generateFieldWhereStatement($data);
            // print_r($fieldWhereQuery);exit;
            if (!empty($fieldWhereQuery['joinQuery'] && !empty($fieldWhereQuery['whereQuery']))) {
                $queryStr = "Select * from ox_file as a
        join ox_form as b on (a.entity_id = b.entity_id)
        join ox_form_field as c on (c.form_id = b.id)
        join ox_field as d on (c.field_id = d.id)
        join ox_app as f on (f.id = b.app_id)
        " . $fieldWhereQuery['joinQuery'] . "
        where f.id = " . $data['app_id'] . " and b.id = " . $data['form_id'] . " and (" . $fieldWhereQuery['whereQuery'] . ") group by a.id";
                $this->logger->info("Executing query - $queryStr");
                $resultSet = $this->executeQuerywithParams($queryStr);
                return $dataSet = $resultSet->toArray();
            } else {
                return 0;
            }
            // $this->email->sendRemainderEmail($appId, $dataList); //Commenting this line
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $dataList;
    }

    private function generateFieldWhereStatement($data)
    {
        $prefix = 1;
        $whereQuery = "";
        $joinQuery = "";
        $returnQuery = array();
        $fieldList = $data['field_list'];
        try {
            if (!empty($fieldList)) {
                foreach ($fieldList as $key => $val) {
                    $tablePrefix = "tblf" . $prefix;
                    $fieldId = $this->getFieldDetails($key, $data['entity_id']);
                    if (!empty($val) && !empty($fieldId)) {
                        $joinQuery .= "left join ox_file_attribute as " . $tablePrefix . " on (a.id =" . $tablePrefix . ".file_id) ";
                        $whereQuery .= $tablePrefix . ".field_id =" . $fieldId['id'] . " and " . $tablePrefix . ".field_value ='" . $val . "' and ";
                    }
                    $prefix += 1;
                }
            }
            $whereQuery .= '1';
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $returnQuery = array("joinQuery" => $joinQuery, "whereQuery" => $whereQuery);
    }

    public function getFieldDetails($fieldName, $entityId = null)
    {
        try {
            if($entityId){
                $entityWhere = "entity_id = " . $entityId . "";
            } else {
                $entityWhere = "1";
            }
            $queryStr = "select * from ox_field where name = '" . $fieldName . "' and " . $entityWhere . "";
            $resultSet = $this->executeQuerywithParams($queryStr);
            $dataSet = $resultSet->toArray();
            if (count($dataSet) == 0) {
                return 0;
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $dataSet[0];
    }

    public function getFileList($appUUid,$params, $filterParams = null)
    {
        $orgId = isset($params['orgId'])? $this->getIdFromUuid('ox_organization', $params['orgId']) : AuthContext::get(AuthConstants::ORG_ID);
        $appId = $this->getIdFromUuid('ox_app', $appUUid);
        if(!isset($orgId)){
            $orgId = $params['orgId'];
        }
        $select = "SELECT * from ox_app_registry where org_id = :orgId AND app_id = :appId";
        $selectQuery = array("orgId" => $orgId,"appId" => $appId);           
        $result = $this->executeQuerywithBindParameters($select,$selectQuery)->toArray();
        if(count($result) > 0){
            $queryParams = array();
            $appFilter = "h.app_id = :appId";
            $queryParams['appId'] = $appId;
            $fieldNameList = "";
            if (isset($params['workflowId'])) {
                $workflowId = $this->getIdFromUuid('ox_workflow', $params['workflowId']);
                if(!$workflowId){
                    throw new ServiceException("Workflow Does not Exist","app.forworkflownot.found");
                } else {
                    $appFilter .= " AND h.id = :workflowId";
                    $queryParams['workflowId'] = $workflowId;
                }
            }
            if(isset($params['status'])){
                $statusFilter = " AND g.status = '".$params['status']."'";
            } else {
                $statusFilter = "";
            }
            $pageSize = " LIMIT " . (isset($filterParamsArray[0]['take']) ? $filterParamsArray[0]['take'] : 20);
            $offset = " OFFSET " . (isset($filterParamsArray[0]['skip']) ? $filterParamsArray[0]['skip'] : 0);
            $where = " $appFilter $statusFilter";
            $fromQuery = " from ox_file as a
            inner join ox_form as b on (a.entity_id = b.entity_id)
            inner join ox_form_field as c on (c.form_id = b.id)
            inner join ox_field as d on (c.field_id = d.id)
            inner join ox_app as f on (f.id = b.app_id)";
            if (isset($params['userId'])) {
                if($params['userId'] == 'me'){
                    $userId = AuthContext::get(AuthConstants::USER_ID);
                } else {
                    $userId = $this->getIdFromUuid('ox_user', $params['userId']);
                    if(!$userId){
                        throw new ServiceException("User Does not Exist","app.forusernot.found");
                    }  
                }
                $fromQuery .= "left join (select * from ox_wf_user_identifier where ox_wf_user_identifier.user_id = :userId) as owufi ON owufi.identifier_name=d.name AND owufi.workflow_instance_id=a.workflow_instance_id";
                $userWhere = " and owufi.user_id = :userId";
                $queryParams['userId'] = $userId;
            } else {
                $userWhere = "";
            }
            $fromQuery .= " inner join ox_workflow_instance as g on a.workflow_instance_id = g.id
            inner join ox_workflow_deployment as wd on wd.id = g.workflow_deployment_id
            inner join ox_workflow as h on h.id = wd.workflow_id and wd.latest=1
            left join (SELECT workflow_instance_id, max(latest) as latest from ox_file
            group by workflow_instance_id) as f2 on a.workflow_instance_id = f2.workflow_instance_id
            and a.latest = f2.latest";
            $prefix = 1;
            $whereQuery = "";
            $joinQuery = "";
            $sort = "";
            $field = "";
            if (!empty($filterParams)) {
                $filterParamsArray = json_decode($filterParams['filter'], true);
                if (array_key_exists("sort", $filterParamsArray[0])) {
                    $sortParam = $filterParamsArray[0]['sort'];
                }
                $filterlogic = isset($filterParamsArray[0]['filter']['logic']) ? $filterParamsArray[0]['filter']['logic'] : " AND ";
                $cnt = 1;
                $fieldParams = array();
                if(isset($filterParamsArray[0]['filter'])){
                    foreach ($filterParamsArray[0]['filter']['filters'] as $key => $value) {
                        $fieldNameList .= $fieldNameList !== "" ? "," : $fieldNameList;
                        $fieldNameList .= ':val' . $cnt;
                        $fieldParams['val' . $cnt] = $value['field'];
                        $cnt++;
                    }
                    $filterData = $filterParamsArray[0]['filter']['filters'];
                    foreach ($filterData as $val) {
                        $tablePrefix = "tblf" . $prefix;
                        $fieldId = $this->getFieldDetails($val['field']);
                        if (!empty($val) && !empty($fieldId)) {
                            $joinQuery .= " left join ox_file_attribute as " . $tablePrefix . " on (a.id =" . $tablePrefix . ".file_id) ";
                            $valueTransform = $this->getFieldType($fieldId, $tablePrefix);
                            $filterOperator = $this->processFilters($val);
                            $whereQuery .= " ".$filterlogic." (" . $tablePrefix . ".field_id = " . $fieldId['id'] . " and " . $valueTransform . "" . $filterOperator["operation"] . "'" . $filterOperator["operator1"] . "" . $val['value'] . "" . $filterOperator["operator2"] . "') ";
                        }
                        if (isset($filterParamsArray[0]['sort']) && count($filterParamsArray[0]['sort']) > 0) {
                            if ($sortParam[0]['field'] === $val['field']) {
                                $sort = "ORDER BY " . $tablePrefix . ".field_value";
                                $field = " , ".$tablePrefix.".field_value";
                            }
                        }
                        $prefix += 1;
                    }
                }
            }
            $where .= " " . $whereQuery . "";
            $fromQuery .= " " . $joinQuery . "";
            try {
                $countQuery = "SELECT count(distinct a.id) as `count` $fromQuery  WHERE ($where) $userWhere";
                $countResultSet = $this->executeQueryWithBindParameters($countQuery, $queryParams)->toArray();
                $select = "SELECT DISTINCT a.data, a.uuid, g.status, g.process_instance_id as workflowInstanceId, h.name as entity_name $field $fromQuery WHERE $where $userWhere $sort $pageSize $offset";
                $resultSet = $this->executeQueryWithBindParameters($select, $queryParams)->toArray();
                if($resultSet){
                    $i=0;
                    foreach ($resultSet as $file) {
                        if($file['data']){
                            $content = json_decode($file['data'],true);
                            if($content){
                                $resultSet[$i] = array_merge($file,$content);
                            }
                        }
                        $i++;
                    }
                }
                return array('data' => $resultSet, 'total' => $countResultSet[0]['count']);
            } catch (Exception $e){
                throw new ServiceException($e->getMessage(),"app.mysql.error");
            }
        } else {
            throw new ServiceException("App Does not belong to the org","app.fororgnot.found");
        }
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

    private function cleanData($params)
    {
        unset($params['workflowInstanceId']);
        unset($params['activityInstanceId']);
        unset($params['workflow_instance_id']);
        unset($params['formId']);
        unset($params['workflow_uuid']);
        unset($params['page']);
        unset($params['parentWorkflowInstanceId']);
        unset($params['activityId']);
        unset($params['workflowId']);
        unset($params['form_id']);
        unset($params['fileId']);
        unset($params['app_id']);
        unset($params['org_id']);
        unset($params['orgId']);
        unset($params['created_by']);
        unset($params['date_modified']);
        unset($params['entity_id']);
        unset($params['parent_id']);
        unset($params['submit']);
        unset($params['controller']);
        unset($params['method']);
        unset($params['action']);
        unset($params['access']);
        unset($params['uuid']);
        unset($params['commands']);

        return $params;

    }
}
