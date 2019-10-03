<?php
namespace Oxzion\Service;

use Oxzion\Model\FileTable;
use Oxzion\Model\File;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Oxzion\Utils\UuidUtil;
use Exception;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

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
        parent::__construct($config, $dbAdapter,$logger);
        $this->table = $table;
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
    public function createFile(&$data, $workflowInstanceId=null)
    {
        unset($data['submit']);
        unset($data['workflowId']);
        $jsonData = json_encode($data);
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
        if(isset($workflowInstanceId)){
            $updateQuery = "UPDATE ox_file SET latest=:latest where workflow_instance_id = :workflowInstanceId";
            $updateParams = array('latest' => 0, 'workflowInstanceId' => $workflowInstanceId);
            $update = $this->executeUpdateWithBindParameters($updateQuery,$updateParams);
            $data['latest'] = 1;
        }
        $data['data'] = $jsonData;
        $data['workflow_instance_id'] = isset($workflowInstanceId)?$workflowInstanceId:null;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['entity_id'] = isset($data['entity_id'])?$data['entity_id']:null;
        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] :  UuidUtil::uuid();
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
        $obj = $this->table->getByUuid($id);
        if (is_null($obj)) {
            return 0;
        }
        $data['form_id'] = $this->getIdFromUuid('ox_form',$data['form_uuid']); 
        $data['app_id'] = $this->getIdFromUuid('ox_app',$data['app_uuid']);
        unset($data['form_uuid']);
        unset($data['app_uuid']);
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
        $file = new File();
        $changedArray = array_merge($fileObject, $data);
        $changedArray['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $changedArray['date_modified'] = date('Y-m-d H:i:s');
        $file->exchangeArray($changedArray);
        $file->validate();
        $fields = array_diff($data, $fileObject);
        $id = $this->getIdFromUuid('ox_file',$id);
        $this->beginTransaction();
        try { 
            $count = $this->table->save($file);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $validFields = $this->checkFields(isset($changedArray['entity_id'])?$changedArray['entity_id']:null, $fields, $this->getIdFromUuid('ox_file',$id));
            if ($validFields && !empty($validFields)) {
                $this->multiInsertOrUpdate('ox_file_attribute', $validFields);
            }
            $this->commit();
        } catch (Exception $e) { 
            print_r($e->getMessage());exit;
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
        $count = 0;
        try {
            $count = $this->table->delete($id = $this->getIdFromUuid('ox_file',$id), ['org_id' => AuthContext::get(AuthConstants::ORG_ID)]);
            if ($count == 0) {
                return 0;
            }
        } catch (Exception $e) {
            $this->logger->log(Logger::ERR, $e->getMessage());
            throw $e;
        }
        return $count;
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
            $obj = $this->table->get($id, array('org_id' => AuthContext::get(AuthConstants::ORG_ID)));
            if ($obj) {
                $fileArray = $obj->toArray();
                // $sql = $this->getSqlObject();
                $select = "SELECT ox_field.name as fieldname,ox_file_attribute.* from ox_file_attribute 
                           left join ox_field on ox_file_attribute.fieldid = ox_field.id
                           where ox_file_attribute.org_id=? and ox_file_attribute.fileid=?";
                 $whereQuery = array(AuthContext::get(AuthConstants::ORG_ID),$id);
                 $result = $this->executeQueryWithBindParameters($select,$whereQuery)->toArray();
                foreach ($result as $key => $value) {
                    $fileArray[$value['fieldname']] = $value['fieldvalue'];
                }
                return $fileArray;
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
        $required = array();
            if (isset($formId)) {
                $query = "SELECT ox_field.* from ox_field 
                left join ox_entity_field on ox_field.id = ox_entity_field.field_id
                left join ox_app_entity on ox_app_entity.id = ox_entity_field.entity_id
                 where ox_form.id=?";
                $where = array($formId);
                $fields = $this->executeQueryWithBindParameters($query,$where)->toArray();
            } else {
                return 0;
            }
        $sqlQuery = "SELECT * from ox_file_attribute where ox_file_attribute.fileid=?";
        $whereParams = array($fileId);
        $fileArray = $this->executeQueryWithBindParameters($sqlQuery,$whereParams)->toArray();
        $keyValueFields = array();
        $i=0;
        if (!empty($fields)) {
            foreach ($fields as $field) {
                // if ($field['required']) {
                //     if (!isset($fieldData[$field['name']])) {
                //         $required[$field['name']] = 'required';
                //     }
                // }
                if (($key = array_search($field['id'], array_column($fileArray, 'fieldid')))>-1) {
                    $keyValueFields[$i]['id'] = $fileArray[$key]['id'];
                    $keyValueFields[$i]['date_modified'] = date('Y-m-d H:i:s');
                    $keyValueFields[$i]['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
                    $keyValueFields[$i]['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
                } else {
                    $keyValueFields[$i]['created_by'] = AuthContext::get(AuthConstants::USER_ID);
                    $keyValueFields[$i]['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
                    $keyValueFields[$i]['date_created'] = date('Y-m-d H:i:s');
                    $keyValueFields[$i]['date_modified'] = date('Y-m-d H:i:s');
                    $keyValueFields[$i]['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
                }
                $keyValueFields[$i]['fieldvalue'] = isset($fieldData[$field['name']])?$fieldData[$field['name']]:null;
                $keyValueFields[$i]['fieldid'] = $field['id'];
                $keyValueFields[$i]['fileid'] = $fileId;
                $i++;
            }
        }
        // if (count($required)>0) {
        //     $validationException = new ValidationException();
        //     $validationException->setErrors($required);
        //     throw $validationException;
        // }
        return $keyValueFields;
    }
}
