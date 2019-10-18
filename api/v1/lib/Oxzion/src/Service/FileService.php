<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Model\File;
use Oxzion\Model\FileTable;
use Oxzion\Utils\UuidUtil;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Oxzion\AppDelegate\AppDelegateService;

class FileService extends AbstractService
{
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, FileTable $table, FormService $formService)
    {
        $logger = new Logger();
        $writer = new Stream(__DIR__ . '/../../../../logs/file.log');
        $logger->addWriter($writer);
        parent::__construct($config, $dbAdapter, $logger);
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
        unset($data['submit']);
        unset($data['workflowId']);
        $jsonData = json_encode($data);
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
        $data['data'] = $jsonData;
        $data['app_id'] = $this->getIdFromUuid('ox_app', $data['app_id']);
        $data['workflow_instance_id'] = isset($workflowInstanceId) ? $workflowInstanceId : null;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['form_id'] = $formId;
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['entity_id'] = isset($data['entity_id']) ? $data['entity_id'] : null;
        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
        $file = new File();
        $file->exchangeArray($data);
        $file->validate();
        $fields = array_diff_assoc($data, $file->toArray());
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($file);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $validFields = $this->checkFields($data['entity_id'], $fields, $id);
            if ($validFields && !empty($validFields)) {
                $this->multiInsertOrUpdate('ox_file_attribute', $validFields, ['id']);
            } else {
                if (!empty($validFields)) {
                    $this->rollback();
                    return 0;
                }
            }
            $this->commit();
        } catch (Exception $e) { 
            switch (get_class($e)) {
                case "Oxzion\ValidationException":
                $this->rollback();
                $this->logger->log(Logger::ERR, $e->getMessage());
                throw $e;
                break;
                default:
                $this->rollback();
                $this->logger->log(Logger::ERR, $e->getMessage());
                throw $e;
                break;
            }
        }
        return $count;
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
            $whereQuery = array($data['workflow_instance_idk']);
            $obj = $this->executeQueryWithBindParameters($select,$whereQuery)->toArray();
            if (is_null($obj)) {
                return 0;
            }
        } else {
            $obj = $this->table->getByUuid($id);
            if (is_null($obj)) {
                return 0;
            }
        }
        if(isset($data['form_uuid'])){
            $data['form_id'] = $this->getIdFromUuid('ox_form',$data['form_uuid']); 
            unset($data['form_uuid']);
        }
        if(isset($data['app_uuid'])){
            $data['app_id'] = $this->getIdFromUuid('ox_app',$data['app_uuid']);
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
        $fileObject = $obj->toArray();
        foreach($data as $key => $dataelement){
            if(is_array($dataelement) || is_bool($dataelement)){
                $data[$key] = json_encode($dataelement);
            }
        }
        $fields = array_diff($data,$fileObject);
        $file = new File();
        $fileObject['data'] =  json_encode($data);
        $fileObject['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $fileObject['date_modified'] = date('Y-m-d H:i:s');
        $file->exchangeArray($fileObject);
        $file->validate();
        $id = $this->getIdFromUuid('ox_file',$id);
        $this->beginTransaction();
        try {  
            $this->logger->info("Entering to Update File -".print_r($file,true)."\n");
            $count = $this->table->save($file);
            if ($count == 0) {
                $this->logger->info("$count - files got updated \n");
                $this->rollback();
                return 0;
            }
            $validFields = $this->checkFields(isset($fileObject['entity_id'])?$fileObject['entity_id']:null, $fields,$id);
            $this->logger->info(print_r($validFields,true)."are the list of valid fields.\n");
            if ($validFields && !empty($validFields)) {
                $query = "Delete from ox_file_attribute where file_id = :fileId";
                $queryWhere = array("fileId" => $id);
                $result = $this->executeQueryWithBindParameters($query,$queryWhere);
                $this->multiInsertOrUpdate('ox_file_attribute', $validFields);
            }
            $this->logger->info("Leaving the updateFile method \n");
            $this->commit();
        } catch (Exception $e) { 
                $this->logger->log(Logger::ERR, $e->getMessage());
                $this->rollback();
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
        try{
            $params['uuid'] = $id;
            $update = $sql->update();
            $update->table('ox_file')
            ->set(array('is_active'=> 0))
            ->where($params);
            $response = $this->executeUpdate($update);
            return 1;
        } catch (Exception $e) { print_r($e->getMessage());exit;
            $this->logger->log(Logger::ERR, $e->getMessage());
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
        try{
            $id = $this->getIdFromUuid('ox_file',$id);
            $obj = $this->table->get($id, array('is_active' => 1,'org_id' => AuthContext::get(AuthConstants::ORG_ID)));
            if ($obj) {
                $fileArray = $obj->toArray();
                return $fileArray;
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
    protected function checkFields($entityId, $fieldData, $fileId)
    {
        $this->logger->info("Entering into checkFields method");
        $required = array();
        if (isset($entityId)) {
            $query = "SELECT ox_field.* from ox_field
            left join ox_app_entity on ox_app_entity.id = ox_field.entity_id
            where ox_app_entity.id=?";
            $where = array($entityId);
            $this->logger->info("Executing query - $query with  params".print_r($where,true));
            $fields = $this->executeQueryWithBindParameters($query,$where)->toArray();
            $this->logger->info("Query result".print_r($fields,true));
        } else {
            return 0;
        }
        $sqlQuery = "SELECT * from ox_file_attribute where ox_file_attribute.file_id=?";
        $whereParams = array($fileId);
        $this->logger->info("Executing query - $sqlQuery with  params".print_r($whereParams,true));
        $fileArray = $this->executeQueryWithBindParameters($sqlQuery,$whereParams)->toArray();
        $this->logger->info("Query result".print_r($fileArray,true));
        $keyValueFields = array();
        $i=0;        
        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (($key = array_search($field['id'], array_column($fileArray, 'field_id')))>-1) {
                    // Update the existing record
                    $keyValueFields[$i]['id'] = $fileArray[$key]['id'];
                } else {
                    // Insert the Record
                    $keyValueFields[$i]['id'] = "";
                }
                $keyValueFields[$i]['org_id'] = (empty($fileArray[$key]['org_id']) ? AuthContext::get(AuthConstants::ORG_ID) : $fileArray[$key]['org_id']);
                $keyValueFields[$i]['created_by'] = (empty($fileArray[$key]['created_by']) ? AuthContext::get(AuthConstants::USER_ID) : $fileArray[$key]['created_by']);
                $keyValueFields[$i]['date_created'] = (!isset($fileArray[$key]['date_created']) ? date('Y-m-d H:i:s') : $fileArray[$key]['date_created']);
                $keyValueFields[$i]['date_modified'] = date('Y-m-d H:i:s');
                $keyValueFields[$i]['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
                $keyValueFields[$i]['field_value'] = isset($fieldData[$field['name']])?$fieldData[$field['name']]:null;
                $keyValueFields[$i]['field_id'] = $field['id'];
                $keyValueFields[$i]['file_id'] = $fileId;
                $i++;
            }
        }
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
        $dataList = $this->getActivePolicies($queryStr);
        // $this->sendEmail($appId, $dataList); //Commenting this line
        return $dataList;
    }

    private function generateFieldWhereStatement($data)
    {
        $prefix = 1;
        $whereQuery = "";
        $joinQuery = "";
        $returnQuery = Array();
        $fieldList = $data['field_list'];
        foreach ($fieldList as $key => $val) {
            $tablePrefix = "tblf" . $prefix;
            $fieldId = $this->getFieldDetaild($key, $data['entity_id']);
            $joinQuery .= "left join ox_file_attribute as " . $tablePrefix . " on (a.id =". $tablePrefix . ".file_id) ";
            $whereQuery .= $tablePrefix . ".field_id =" . $fieldId['id'] . " and " . $tablePrefix . ".field_value ='" . $val . "' and ";
            $prefix += 1;
        }
        $whereQuery .= '1';
        return $returnQuery = Array("joinQuery" => $joinQuery, "whereQuery" => $whereQuery);
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
        print_r($content);exit;
        return 1;
        // foreach ($data as $d) {

        // }
    }
}
