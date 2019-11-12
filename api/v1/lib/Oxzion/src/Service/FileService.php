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

class FileService extends AbstractService
{
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, FileTable $table, FormService $formService)
    {
        parent::__construct($config, $dbAdapter);
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

        $fields = array_diff($data,$fileObject);
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
            $this->logger->info("Entering to Update File -" . json_encode($file) . "\n");

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
                if ($field['data_type'] == 'selectboxes' && isset($fieldData[$field['name']])) {

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
                $dataList = $this->getActivePolicies($queryStr);
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

    public function generateFieldWhereStatement($data)
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
                    $fieldId = $this->getFieldDetaild($key, $data['entity_id']);
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

    public function getFieldDetaild($fieldName, $entityId = null)
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

    private function getActivePolicies($queryStr)
    {
        $resultSet = $this->executeQuerywithParams($queryStr);
        return $dataSet = $resultSet->toArray();
    }

    // Code to run through the list of all the active policies and and send email to the Insureds
    private function sendEmail($appId, $data)
    {
        $delegateService = new AppDelegateService($this->config, $this->dbAdapter);
        $content = $delegateService->execute($appId, 'DispatchRenewalPolicy', $data);
        // print_r($content);exit;
        return 1;
        // foreach ($data as $d) {

        // }
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
