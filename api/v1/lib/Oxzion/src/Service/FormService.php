<?php
namespace Oxzion\Service;

use Oxzion\Model\FormTable;
use Oxzion\Model\Form;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\Service\AbstractService;
use Bos\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class FormService extends AbstractService{

    public function __construct($config, $dbAdapter, FormTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createForm(&$data){
        $form = new Form();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
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
            $this->commit();
        }catch(Exception $e){
            switch (get_class ($e)) {
             case "Bos\ValidationException" :
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
    public function updateForm($id,&$data){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $file = $obj->toArray();
        $form = new Form();
        $changedArray = array_merge($obj->toArray(),$data);
        $changedArray['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $changedArray['date_modified'] = date('Y-m-d H:i:s');
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
            switch (get_class ($e)) {
             case "Bos\ValidationException" :
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


    public function deleteForm($id){
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->delete($id, ['org_id' => AuthContext::get(AuthConstants::ORG_ID)]);
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

    public function getForms() {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_form')
                ->columns(array("*"))
                ->where(array('ox_form.org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        return $this->executeQuery($select)->toArray();
    }
    public function getForm($id) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_form')
        ->columns(array("*"))
        ->where(array('ox_form.id' => $id,'ox_form.org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        $response = $this->executeQuery($select)->toArray();
        if(count($response)==0){
            return 0;
        }
        return $response[0];
    }
}
?>