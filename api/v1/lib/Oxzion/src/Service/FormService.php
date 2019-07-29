<?php
namespace Oxzion\Service;

use Oxzion\Model\FormTable;
use Oxzion\Model\Form;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;
use Oxzion\Service\FieldService;
use Oxzion\Model\Field;
use Oxzion\Model\FieldTable;
use Oxzion\FormEngine\FormFactory;

class FormService extends AbstractService{

    public function __construct($config, $dbAdapter, FormTable $table,FormFactory $formEngineFactory,FieldService $fieldService){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->formEngineFactory = $formEngineFactory;
        $this->formEngine = $this->formEngineFactory->getFormEngine();
        $this->fieldService = $fieldService;
    }

    public function createForm($appId,&$data){
        $form = new Form();
        $template = $this->formEngine->parseForm($data['template']);
        if(!is_array($template)){
            return 0;
        }
		$template['form']['app_id'] = $appId;
        $template['form']['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $template['form']['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $template['form']['date_created'] = date('Y-m-d H:i:s');
        $template['form']['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($template['form']);
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
            $generateFields = $this->generateFields($template['fields'],$appId,$id);
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
    public function updateForm($appId,$id,&$data){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $template = $this->formEngine->parseForm($data['template']);
        if(!is_array($template)){
            return 0;
        }
        $form = new Form();
        $changedArray = array_merge($obj->toArray(),$template['form']);
        $changedArray['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $changedArray['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($changedArray);
        $form->validate();
        $count = 0;
        try{
            $count = $this->table->save($form);
            if($count == 0){
                return 0;
            }
            $generateFields = $this->generateFields($template['fields'],$appId,$id);
        }catch(Exception $e){
            switch (get_class ($e)) {
             case "Oxzion\ValidationException" :
                throw $e;
                break;
             default:
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
            $count = $this->table->delete($id,[]);
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

    public function getForms($appId=null,$filterArray=array()) {
        if(isset($appId)){
            $filterArray['app_id'] = $appId;
        }
        $resultSet = $this->getDataByParams('ox_form',array("*"),$filterArray,null);
        $response = array();
        $response['data'] = $resultSet->toArray();
        return $response;
    }
    public function getForm($id) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_form')
        ->columns(array("*"))
        ->where(array('ox_form.id' => $id));
        $response = $this->executeQuery($select)->toArray();
        if(count($response)==0){
            return 0;
        }
        return $response[0];
    }
    private function generateFields($fieldsList,$appId,$formId){
        try {
            $deleteFields = $this->fieldService->deleteFields($formId);
            $delete = "DELETE from ox_form_field where form_id=".$formId.";";
            $result = $this->runGenericQuery($delete);
        } catch (Exception $e){
            return 0;
        }
        $i=0;
        $fieldIdArray = array();
        foreach ($fieldsList as $field) {
            $oxField = new Field();
            $field['app_id'] = $appId;
            $oxField->exchangeArray($field);
            $oxFieldProps = array();
            $fieldData = $oxField->toArray();
            try {
                $fieldResult = $this->fieldService->saveField($appId,$fieldData);
                $fieldIdArray[] = $fieldData['id'];
                $createFormFieldEntry = $this->createFormFieldEntry($formId,$fieldData['id']);
            } catch(Exception $e){
                foreach ($fieldIdArray as $fieldId) {
                    $id = $this->fieldService->deleteField($fieldId);
                    return 0;
                }
            }
            $i++;
        }
        if(count($fieldsList)==$i){
            return 1;
        } else {
            return 0;
        }
    }
    private function createFormFieldEntry($formId,$fieldId){        
        $this->beginTransaction();      
        try {       
            $insert = "INSERT INTO `ox_form_field` (`form_id`,`field_id`) VALUES ($formId,$fieldId)";       
            $resultSet = $this->executeQuerywithParams($insert);        
            $this->commit();        
        } catch (Exception $e) {        
            $this->rollback();      
            return 0;       
        }           
    }
}
?>