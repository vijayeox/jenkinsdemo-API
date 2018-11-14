<?php
namespace File\Service;

use Oxzion\Service\AbstractService;
use File\Model\FileTable;
use File\Model\File;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class FileService extends AbstractService{
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, FileTable $table){
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
    *               status : string,
    *               formid : integer,
    *               Fields from Form
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created File.
    */
    public function createFile(&$data){
        $form = new File();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $fields = array_diff_assoc($data,$form->toArray());
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->save($form);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $validFields = $this->checkFields($data['form_id'],$fields,$data['org_id'],$id);
            if($validFields){
                $this->multiInsertOrUpdate('ox_file_attribute',$validFields,['id']);
            }
            $this->commit();
        }catch(Exception $e){
            switch (get_class ($e)) {
             case "Oxzion\ValidationException" :
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
    public function updateFile($id,&$data){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $file = $obj->toArray();
        $form = new File();
        $changedArray = array_merge($obj->toArray(),$data);
        $changedArray['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $changedArray['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($changedArray);
        $form->validate();
        $fields = array_diff($obj->toArray(),$data);
        $this->beginTransaction();
        try{
            $count = $this->table->save($form);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $validFields = $this->checkFields($data['form_id'],$fields,$data['org_id'],$id);
            if($validFields){
                $this->multiInsertOrUpdate('ox_file_attribute',$validFields,['id']);
            }
            $this->commit();
        }catch(Exception $e){
            print_r($e->getMessage());exit;
            switch (get_class ($e)) {
             case "Oxzion\ValidationException" :
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
    public function deleteFile($id){
    $count = 0;
        try{
            $count = $this->table->delete($id, ['org_id' => AuthContext::get(AuthConstants::ORG_ID)]);
            if($count == 0){
                return 0;
            }
        }catch(Exception $e){
            return 0;
        }
        return $count;
    }

    /**
    * GET List File Service
    * @method GET
    * @return array Returns a JSON Response with Error Message.
    */
    public function getFiles() {
        // $sql = $this->getSqlObject();
        // $select = $sql->select()
        //         ->from('ox_file')
        //         ->columns(array("*"))
        //         ->where(array('ox_file.org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        // $result = $this->executeQuery($select)->toArray();
        return array();
    }

    /**
    * GET File Service
    * @method getFile
    * @param $id ID of File
    * @return array $data 
    * @return array Returns a JSON Response with Status Code and Created File.
    */
    public function getFile($id){
        $obj = $this->table->get($id,array('org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        if($obj){
            $fileArray = $obj->toArray(); 
            $sql = $this->getSqlObject();
            $select = $sql->select()
            ->from('ox_file_attribute')
            ->columns(array("*"))
            ->join('ox_field', 'ox_file_attribute.fieldid = ox_field.id', array('fieldname'=>'name'),'left')
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
    protected function checkFields($formId,$fieldData,$orgId,$fileId){
        $required = array();
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_field')
        ->columns(array("*"))
        ->where(array('ox_field.form_id' => $formId,'ox_field.org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        $fields = $this->executeQuery($select)->toArray();
        $keyValueFields = array();
        $i=0;
        foreach ($fields as $key => $field) {
            if($field['required']){
                if(!isset($fieldData[$field['name']])){
                    $required[$field['name']] = 'required';
                }
            }
            if($field['options']){
                $valid = 0;
                $optionslist = json_decode($field['options'],true);
                foreach ($optionslist['data'] as $k => $option) {
                    if($k==$fieldData[$field['name']]){
                        $valid = 1;
                        break;
                    }
                }
                if(!$valid){
                    $required[$field['name']] = 'option not found';
                }
            }
            $keyValueFields[$i]['fieldvalue'] = $fieldData[$field['name']];
            $keyValueFields[$i]['fieldid'] = $field['id'];
            $keyValueFields[$i]['org_id'] = $orgId;
            $keyValueFields[$i]['fileid'] = $fileId;
            $i++;
        }
        if(count($required)>0){
            $validationException = new ValidationException();
            $validationException->setErrors($required);
            throw $validationException;
        }
        return $keyValueFields;
    }
}
?>