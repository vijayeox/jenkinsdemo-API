<?php
namespace Form\Service;

use Oxzion\Service\AbstractService;
use Form\Model\FieldTable;
use Form\Model\Field;
use Form\Model\Metafield;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class FieldService extends AbstractService{

    public function __construct($config, $dbAdapter, FieldTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    public function createField($formId,&$data){
        $form = new Field();
        $data['form_id'] = $formId;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        if(isset($data['sequence'])){
            $sequenceExist = $this->checkSequenceExists($formId,$data['sequence']);
            if($sequenceExist){
                $data['sequence'] = $this->getSequenceByForm($formId)+1;
            }
        } else {
            $data['sequence'] = $this->getSequenceByForm($formId)+1;
        }
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try{
            $nameExists = $this->getUniqueFieldName($data['name']);
            if($nameExists){
                $validationException = new ValidationException();
                $validationException->setErrors(array('name'=>'Field Name Exists'));
                throw $validationException;
            } else {
                $this->createMetafield($form->toArray());
            }
            $count = $this->table->save($form);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
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
    public function updateField($id,&$data){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $data['id'] = $id;
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $file = $obj->toArray();
        $changedArray = array_merge($obj->toArray(),$data);
        $form = new Field();
        $form->exchangeArray($changedArray);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->save($form);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            return 0;
        }
        return $count;
    }


    public function deleteField($formId,$id){
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->delete($id, ['org_id' => AuthContext::get(AuthConstants::ORG_ID),'form_id'=>$formId]);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
        }
        
        return $count;
    }

    public function getFields($formId) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_field')
                ->columns(array("*"))
                ->where(array('org_id' => AuthContext::get(AuthConstants::ORG_ID),'form_id'=>$formId));
        return $this->executeQuery($select)->toArray();
    }
    public function getField($formId,$id) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_field')
        ->columns(array("*"))
        ->where(array('id' => $id,'ox_field.org_id' => AuthContext::get(AuthConstants::ORG_ID),'form_id'=>$formId));
        $response = $this->executeQuery($select)->toArray();
        if(count($response)==0){
            return 0;
        }
        return $response[0];
    }
    protected function getSequenceByForm($formId){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_field')
                ->columns(array('MAX' => new \Zend\Db\Sql\Expression("MAX(sequence)")))
                ->where(array('org_id' => AuthContext::get(AuthConstants::ORG_ID),'form_id'=>$formId));
        $result = $this->executeQuery($select)->toArray();
        if(count($result)>0){
            return $result[0]['MAX'];
        } else {
            return 1;
        }
    }
    private function checkSequenceExists($formId,$sequence){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_field')
                ->columns(array('sequence'))
                ->where(array('org_id' => AuthContext::get(AuthConstants::ORG_ID),'form_id'=>$formId,'sequence'=>$sequence));
        $result = $this->executeQuery($select)->toArray();
        if(count($result)>0){
            return 0;
        }
        return 1;
    }
    private function getUniqueFieldName($fieldName){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_field')
                ->columns(array('name'))
                ->where(array('org_id' => AuthContext::get(AuthConstants::ORG_ID),'name'=>$fieldName));
        $result = $this->executeQuery($select)->toArray();
        if(count($result)>0){
            return 1;
        }
        return 0;
    }
    private function createMetafield($field){
        $sql = $this->getSqlObject();
        $insert = $sql->insert('ox_metafield');
        $metaField = new Metafield();
        $metaField->exchangeArray($field);
        $insert->values($metaField->toArray());
        $result = $this->executeUpdate($insert);
    }
}
?>