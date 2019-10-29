<?php
namespace Oxzion\Service;

use Oxzion\Model\FileTable;
use Oxzion\Model\File;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Oxzion\Utils\UuidUtil;
use Exception;

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
        $this->logger->info("Data CreateFile- ".print_r($data,true));
        $parentId = isset($data['parent_id']) ? $data['parent_id'] : null;
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
        $this->cleanData($data);
        $jsonData = json_encode($data);
        $data['app_id'] = $this->getIdFromUuid('ox_app', $data['app_id']);
        $data['workflow_instance_id'] = isset($workflowInstanceId) ? $workflowInstanceId : null;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['form_id'] = $formId;
        $data['parent_id'] = $parentId;
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['entity_id'] = isset($data['entity_id']) ? $data['entity_id'] : null;
        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();   
        $data['data'] = $jsonData;
        $file = new File();
        $file->exchangeArray($data);
        $this->logger->info("Data From Fileservice - ".print_r($data,true));
        $this->logger->info("File data From Fileservice - ".print_r($file->toArray(),true));
        $fields = array_diff_assoc($data, $file->toArray());
        $file->validate();
        $this->beginTransaction();

        $count = 0;
        $this->logger->info("COUNT ----".print_r($count,true));
        try {
            if($parentId){
                if(!$this->setFileLatest($parentId, 0)){
                    throw new Exception("Could not update latest for parent file ".$data['parent_id']);
                }
            }
            $count = $this->table->save($file);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->logger->info("FILE DATA ----- ".print_r($data,true));
            $validFields = $this->checkFields($data['entity_id'], $fields, $id);
            if (!$validFields || empty($validFields)) {
                return 0;
            }            
            $this->logger->info("Check Fields - ".print_r($validFields,true));
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

    private function setFileLatest($fileId, $isLatest){

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
        if(isset($data['workflow_instance_id'])){
            $select = "SELECT ox_file.* from ox_file 
            where ox_file.workflow_instance_id=? ";
            $whereQuery = array($data['workflow_instance_id']);
            $obj = $this->executeQueryWithBindParameters($select,$whereQuery)->toArray()[0];
        } else {
            $obj = $this->table->getByUuid($id);
            $obj = $obj->toArray();
        }
        if (is_null($obj)) {
            return 0;
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
        $fileObject = json_decode($obj['data'],true);

        foreach($fileObject as $key =>$fileObjectValue){
            if(is_array($fileObjectValue) || is_bool($fileObjectValue)){
                $fileObject[$key] = json_encode($fileObjectValue);
            }
        }

        foreach($data as $key => $dataelement){
            if(is_array($dataelement) || is_bool($dataelement)){
                $data[$key] = json_encode($dataelement);
            }
        }
        
        $fields = array_diff($data,$fileObject);
        $file = new File();
        $fileObject = $obj;
        $fileObject['data'] = json_encode($data);
        $fileObject['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $fileObject['date_modified'] = date('Y-m-d H:i:s');
        $file->exchangeArray($fileObject);
        $file->validate();
        $id = $this->getIdFromUuid('ox_file', $id);
        $this->beginTransaction();
        try {
            $this->logger->info("Entering to Update File -" . print_r($file, true) . "\n");
            $count = $this->table->save($file);

            if ($count == 0) {
                $this->logger->info("$count - files got updated \n");
                $this->rollback();
                return 0;
            }
            $validFields = $this->checkFields(isset($fileObject['entity_id']) ? $fileObject['entity_id'] : null, $fields, $id);
            $this->logger->info(print_r($validFields, true) . "are the list of valid fields.\n");
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
            $this->logger->info("FILE ID  ------".print_r($id,true));
            $params = array('id' => $id,
                            'active' => 1,
                            'orgId' => AuthContext::get(AuthConstants::ORG_ID));
            $select  = "SELECT uuid, data  from ox_file where uuid = :id AND is_active = :active AND org_id = :orgId";
            $this->logger->info("Executing query $select with params ".print_r($params,true));
            
            $result = $this->executeQueryWithBindParameters($select,$params)->toArray();
            $this->logger->info("FILE DATA ------".print_r($result,true));
            if(count($result) > 0){
                $this->logger->info("FILE ID  ------".print_r($result,true));
                if($result[0]['data']){
                    $result[0]['data'] = json_decode($result[0]['data'], true);
                }
                return $result;
            }
            return 0;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function getFileByWorkflowInstanceId($workflowInstanceId,$isProcessInstanceId = true)
    { 
        if($isProcessInstanceId){
            $where = "ox_workflow_instance.process_instance_id=:workflowInstanceId";
        }else{
            $where = "ox_workflow_instance.id=:workflowInstanceId";
        }
        try{
            $select = "SELECT ox_file.id,ox_file.data from ox_file 
                       inner join ox_workflow_instance on ox_workflow_instance.id = ox_file.workflow_instance_id
                       where ox_file.org_id=:orgId and $where and ox_file.is_active =:isActive";
            $whereQuery = array("orgId" => AuthContext::get(AuthConstants::ORG_ID),
                                "workflowInstanceId" => $workflowInstanceId,
                                "isActive" => 1);
            $result = $this->executeQueryWithBindParameters($select,$whereQuery)->toArray();
            if(count($result) > 0){                    
                return $result[0];
            }
            return 0;
        }catch(Exception $e){
            $this->logger->log(Logger::ERR, $e->getMessage());
            throw $e;
        }
    }


    /**
    * @ignore checkFields
    */
    protected function checkFields($entityId, $fieldData, $fileId)
    {
        $this->logger->info("Entering into checkFields method");
        $required = array();
        if (isset($entityId)) {
            $query = "SELECT ox_field.* from ox_field
            left join ox_app_entity on ox_app_entity.id = ox_field.entity_id
            where ox_app_entity.id=?";
            $where = array($entityId);
            $this->logger->info("Executing query - $query with  params" . print_r($where, true));
            $fields = $this->executeQueryWithBindParameters($query, $where)->toArray();
            $this->logger->info("Query result" . print_r($fields, true));
        } else {
            return 0;
        }
        $sqlQuery = "SELECT * from ox_file_attribute where ox_file_attribute.file_id=?";
        $whereParams = array($fileId);
        $this->logger->info("Executing query - $sqlQuery with  params" . print_r($whereParams, true));
        $fileArray = $this->executeQueryWithBindParameters($sqlQuery, $whereParams)->toArray();
        $this->logger->info("Query result" . print_r($fileArray, true));
        $keyValueFields = array();
        $i = 0;
        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (($key = array_search($field['id'], array_column($fileArray, 'field_id'))) > -1) {
                    // Update the existing record
                    $keyValueFields[$i]['id'] = $fileArray[$key]['id'];
                } else {
                    // Insert the Record
                    //$keyValueFields[$i]['id'] = "";
                }
                if($field['data_type'] == 'selectboxes' && isset($fieldData[$field['name']])){
                    
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
        $this->logger->info("Key Values - " . print_r($keyValueFields, true));
        return $keyValueFields;
    }

    public function checkFollowUpFiles($appId, $data)
    {
        $fieldWhereQuery = $this->generateFieldWhereStatement($data);
        $queryStr = "Select * from ox_file as a
        join ox_form as b on (a.entity_id = b.entity_id)
        join ox_form_field as c on (c.form_id = b.id)
        join ox_field as d on (c.field_id = d.id)
        join ox_app as f on (f.id = b.app_id)
        " . $fieldWhereQuery['joinQuery'] . "
        where f.id = " . $data['app_id'] . " and b.id = " . $data['form_id'] . " and (" . $fieldWhereQuery['whereQuery'] . ") group by a.id";
        $this->logger->info("Executing query - $queryStr");
        $dataList = $this->getActivePolicies($queryStr);
        // $this->sendEmail($appId, $dataList); //Commenting this line
        return $dataList;
    }

    private function generateFieldWhereStatement($data)
    {
        $prefix = 1;
        $whereQuery = "";
        $joinQuery = "";
        $returnQuery = array();
        $fieldList = $data['field_list'];
        foreach ($fieldList as $key => $val) {
            $tablePrefix = "tblf" . $prefix;
            $fieldId = $this->getFieldDetaild($key, $data['entity_id']);
            $joinQuery .= "left join ox_file_attribute as " . $tablePrefix . " on (a.id =" . $tablePrefix . ".file_id) ";
            $whereQuery .= $tablePrefix . ".field_id =" . $fieldId['id'] . " and " . $tablePrefix . ".field_value ='" . $val . "' and ";
            $prefix += 1;
        }
        $whereQuery .= '1';
        return $returnQuery = array("joinQuery" => $joinQuery, "whereQuery" => $whereQuery);
    }

    private function getFieldDetaild($fieldName, $entityId)
    {
        $queryStr = "select * from ox_field where name = '" . $fieldName . "' and entity_id = " . $entityId . "";
        $resultSet = $this->executeQuerywithParams($queryStr);
        $dataSet = $resultSet->toArray();
        if (count($dataSet) == 0) {
            return 0;
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

    private function cleanData($params){
        unset($params['workflowInsatnceId']);
        unset($params['parentWorkflowInsatnceId']);
        unset($params['activityId']);
        unset($params['workflowId']);
        unset($params['form_id']);
        unset($params['fileId']);
        unset($params['app_id']);
        unset($params['org_id']);
        unset($params['created_by']);
        unset($params['date_modified']);
        unset($params['entity_id']);
        unset($params['parent_id']);
        unset($params['submit']);
        unset($params['workflowId']);
        
        return $params;
        
    }
}