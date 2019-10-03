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
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Oxzion\Utils\ArrayUtils;

class FormService extends AbstractService
{
    public function __construct($config, $dbAdapter, FormTable $table, FormFactory $formEngineFactory, FieldService $fieldService, Logger $logger)
    {
        parent::__construct($config, $dbAdapter,$logger);
        $this->table = $table;
        $this->formEngineFactory = $formEngineFactory;
        $this->formEngine = $this->formEngineFactory->getFormEngine();
        $this->fieldService = $fieldService;
    }

    public function createForm($appUuid, &$data)
    {   
        $form = new Form();
        if(is_array($data['template'])){
            $data['template'] = json_encode($data['template']);
        }
        $template = $this->formEngine->parseForm($data['template']);
        if (!is_array($template)) {
            return 0;
        }
        if(isset($data['entity_id'])){
            $template['form']['entity_id'] = $data['entity_id'];
        }
        if ($app = $this->getIdFromUuid('ox_app', $appUuid)) {
            $appId = $app;
        } else {
            $appId = $appUuid;
        }
        $template['form']['app_id'] = $appId;
        $data['name'] = $template['form']['name'];
        $template['form']['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $template['form']['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $template['form']['date_created'] = date('Y-m-d H:i:s');
        $template['form']['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($template['form']);
        $form->validate();
        $count = 0;
        $this->beginTransaction();
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $generateFields = $this->generateFields($template['fields'], $appId, $id,$template['form']['entity_id']);
            $data['fields'] = $generateFields;
            $this->commit();
        } catch (Exception $e) {
            switch (get_class($e)) {
             case "Oxzion\ValidationException":
                $this->rollback();
                 $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
                throw $e;
                break;
             default:
                $this->rollback();
                 $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
                throw $e;
                break;
            }
        }
        return $count;
    }
    public function updateForm($appUuid, $formUuid, &$data)
    {
        $obj = $this->table->getByUuid($formUuid);
        if (is_null($obj)) {
            return 0;
        }
        $template = $this->formEngine->parseForm($data['template']);
        if (!is_array($template)) {
            return 0;
        }
        $form = new Form();
        $existingForm = $obj->toArray();
        $changedArray = array_merge($obj->toArray(), $template['form']);
        $changedArray['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $changedArray['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($changedArray);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $generateFields = $this->generateFields($template['fields'], $this->getIdFromUuid('ox_app', $appUuid), $this->getIdFromUuid('ox_form', $formUuid),$existingForm['entity_id']);
            $this->commit();
        } catch (Exception $e) {
            switch (get_class($e)) {
             case "Oxzion\ValidationException":
                $this->rollback();
                 $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
                throw $e;
                break;
             default:
                $this->rollback();
                 $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
                throw $e;
                break;
            }
        }
        return $count;
    }

    public function deleteForm($formUuid)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($this->getIdFromUuid('ox_form', $formUuid), []);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
             $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
             throw $e;
        }
        
        return $count;
    }

    public function getForms($appUuid=null, $filterArray=array())
    {
        
        try{
            $appId = $this->getIdFromUuid('ox_app', $appUuid);
            if (isset($appId)) {
                $filterArray['app_id'] = $appId;
            }
            $resultSet = $this->getDataByParams('ox_form', array("*"), $filterArray, null);
            $response = array();
            $response['data'] = $resultSet->toArray();
            return $response;
        } catch (Exception $e) {
            $this->rollback();
             $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
             throw $e;
        }
    }
    public function getForm($uuid)
    {
        try{
            $queryString = "Select * from ox_form where uuid=?";
            $queryParams = array($uuid);
            $resultSet = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
            if (count($resultSet)==0) {
                return 0;
            }
            return $resultSet[0];
        }catch(Exception $e){
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
    }

    public function getWorkflow($formId)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_form')
        ->columns(array("*"))
        ->join('ox_activity_form', 'ox_activity_form.form_id = ox_form.id', array(), 'left')
        ->join('ox_activity', 'ox_activity.id = ox_activity_form.form_id', array('activity_id'=>'id'), 'left')
        ->join('ox_workflow', 'ox_workflow.form_id = ox_form.id', array('workflow_id'=>'uuid'), 'inner')
        ->where(array('ox_form.id' => $formId));
        $response = $this->executeQuery($select)->toArray();
        if (count($response)==0) {
            return 0;
        }
        return $response[0];
    }
     private function generateFields($fieldsList, $appId, $formId,$entityId)
    {
        try {
            $existingFieldsQuery = "select ox_field.* from ox_field INNER JOIN ox_entity_field ON ox_entity_field.field_id=ox_field.id where ox_entity_field.entity_id=".$entityId.";";
            $existingFields = $this->executeQuerywithParams($existingFieldsQuery);
            if(count($existingFields) > 0){
                $existingFields = $existingFields->toArray();
            } else {
                $existingFields = array();
            }
        } catch (Exception $e) {
            return 0;
        }
        $fieldsCreated = array();
        $fieldIdArray = array();
        foreach ($fieldsList as $field) {
            //Add only new fields
            $foundField =  ArrayUtils::multiDimensionalSearch($existingFields,'name',$field['name']);
            if(!$foundField){
                $oxField = new Field();
                $field['app_id'] = $appId;
                $field['entity_id'] = $entityId;
                $oxField->exchangeArray($field);
                $oxFieldProps = array();
                $fieldData = $oxField->toArray();
                try {
                    $fieldResult = $this->fieldService->saveField($appId, $fieldData);
                    $fieldIdArray[] = $fieldData['id'];
                    $fieldsCreated[] = $fieldData;
                    $createFormFieldEntry = $this->createFormFieldEntry($formId, $fieldData['id']);
                    $entityFieldEntry = $this->createEntityFieldEntry($entityId, $fieldData['id']);
                } catch (Exception $e) {
                    foreach ($fieldIdArray as $fieldId) {
                        $id = $this->fieldService->deleteField($fieldId);
                        return 0;
                    }
                }
                unset($fieldData);
            } else {
                $createFormFieldEntry = $this->createFormFieldEntry($formId, $foundField['id']);
            }
        }
        foreach ($existingFields as $existingField) {
            $fieldDeleted =  ArrayUtils::multiDimensionalSearch($fieldsList,'name',$existingField['name']);
            if(isset($fieldDeleted)){
               $deleteFormFields = "DELETE from ox_form_field where form_id=".$formId." and field_id=".$existingField['id'].";";
               $result = $this->executeQuerywithParams($deleteFormFields);
            }
        }
        return $fieldsCreated;
    }
    private function createFormFieldEntry($formId, $fieldId)
    {
        $this->beginTransaction();
        try {
            $insert = "INSERT INTO `ox_form_field` (`form_id`,`field_id`) VALUES (:formId,:fieldId)";
            $insertParams = array("formId" => $formId,"fieldId" =>$fieldId);
            $resultSet = $this->executeQueryWithBindParameters($insert,$insertParams);
            $this->commit();
        } catch (Exception $e) {
            print_r($e->getMessage());exit;
            $this->rollback();
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
    }
    private function createEntityFieldEntry($entityId, $fieldId)
    {
        $this->beginTransaction();
        try {
            $insert = "INSERT INTO `ox_entity_field` (`entity_id`,`field_id`) VALUES (:entityId,:fieldId)";
            $insertParams = array("entityId" => $entityId,"fieldId" =>$fieldId);
            $resultSet = $this->executeQueryWithBindParameters($insert,$insertParams);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
    }
}
