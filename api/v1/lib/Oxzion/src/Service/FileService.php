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
        $data['data'] = $jsonData;
        $data['workflow_instance_id'] = isset($workflowInstanceId)?$workflowInstanceId:null;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] :  UuidUtil::uuid();
        $file = new File();
        $file->exchangeArray($data);
        $file->validate();
        $fields = array_diff_assoc($data, $file->toArray());
        $this->beginTransaction();
        $count = 0;
        try {
            // echo "Ceaet file";
            $count = $this->table->save($file);
            // var_dump($count);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $validFields = $this->checkFields($activityId, $formId, $fields, $id);
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
    /**
    * Update File Service
    * @method updateFile
    * @param array $id ID of File to update
    * @param array $data
    * @return array Returns a JSON Response with Status Code and Created File.
    */
    public function updateFile(&$data, $id)
    {
        // print_r(array($data['form_id'],$id));
        $obj = $this->table->get($id);
        if (is_null($obj)) {
            return 0;
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
        // print_r($obj);
        $fileObject = $obj->toArray();
        // print_r($fileObject);
        $file = new File();
        $changedArray = array_merge($fileObject, $data);
        $changedArray['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $changedArray['date_modified'] = date('Y-m-d H:i:s');

        $file->exchangeArray($changedArray);
        $file->validate();
        $fields = array_diff($data, $fileObject);
        $this->beginTransaction();
        try {
            $count = $this->table->save($file);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $validFields = $this->checkFields($activityId, $formId, $fields, $id);
            if ($validFields && !empty($validFields)) {
                $this->multiInsertOrUpdate('ox_file_attribute', $validFields);
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
            $count = $this->table->delete($id, ['org_id' => AuthContext::get(AuthConstants::ORG_ID)]);
            if ($count == 0) {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
        return $count;
    }

    /**
    * GET List File Service
    * @method GET
    * @return array Returns a JSON Response with Error Message.
    */
    public function getFiles($formId)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
                ->from('ox_file')
                ->columns(array("*"))
                ->where(array('ox_file.org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        $result = $this->executeQuery($select)->toArray();
        return array();
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
        $obj = $this->table->get($id, array('org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        if ($obj) {
            $fileArray = $obj->toArray();
            $sql = $this->getSqlObject();
            $select = $sql->select()
            ->from('ox_file_attribute')
            ->columns(array("*"))
            ->join('ox_field', 'ox_file_attribute.fieldid = ox_field.id', array('fieldname'=>'name'), 'left')
            ->where(array('ox_file_attribute.org_id' => AuthContext::get(AuthConstants::ORG_ID),'ox_file_attribute.fileid' => $id));
            $result = $this->executeQuery($select)->toArray();
            foreach ($result as $key => $value) {
                $fileArray[$value['fieldname']] = $value['fieldvalue'];
            }
            return $fileArray;
        }
        return 0;
    }
    /**
    * @ignore checkFields
    */
    protected function checkFields($activityId=null, $formId=null, $fieldData, $fileId)
    {
        $required = array();
        $sql = $this->getSqlObject();
        $activityFormsQuery = $sql->select();
        if (isset($activityId)) {
            $activityFormsQuery->from('ox_activity_form')
            ->columns(array("*"))
            ->where(array('ox_activity_form.activity_id' => $activityId));
            $activityForms = $this->executeQuery($activityFormsQuery)->toArray();
            $select = $sql->select();
            $select->from('ox_field')
            ->columns(array("*"))
            ->join('ox_form_field', 'ox_field.id = ox_form_field.field_id', array(), 'left')
            ->join('ox_form', 'ox_form.id = ox_form_field.form_id', array(), 'left')
            ->where(array('ox_form.id' => array(array_column($activityForms, 'form_id'))));
            $fields = $this->executeQuery($select)->toArray();
        } else {
            if (isset($formId)) {
                $select = $sql->select();
                $select->from('ox_field')
                ->columns(array("*"))
                ->join('ox_form_field', 'ox_field.id = ox_form_field.field_id', array(), 'left')
                ->join('ox_form', 'ox_form.id = ox_form_field.form_id', array(), 'left')
                ->where(array('ox_form.id' => $formId));
                $fields = $this->executeQuery($select)->toArray();
            } else {
                return 0;
            }
        }

        $fileFields = $sql->select();
        $fileFields->from('ox_file_attribute')
        ->columns(array("*"))
        ->where(array('ox_file_attribute.fileid' => $fileId));
        $fileArray = $this->executeQuery($fileFields)->toArray();
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
