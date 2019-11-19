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
use Oxzion\Utils\ArrayUtils;

class FormService extends AbstractService
{
    public function __construct($config, $dbAdapter, FormTable $table, FormFactory $formEngineFactory, FieldService $fieldService)
    {
        parent::__construct($config, $dbAdapter);
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
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
            
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
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
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
            print_r($e->getMessage());exit;
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        
        return $count;
    }

    public function getForms($appUuid=null, $filterArray=array())
    {
        
        try{
            $where = "";
            $params = array();
            if (isset($appUuid)) {
                $where ."where app.uuid = :appId";
                $params['appId'] = $appUuid;
            }
            //TODO handle the $filterArray using FilterUtils
            $query = "select f.name, f.template, e.uuid as entity_id, f.uuid as form_id from 
                      ox_form as f inner join ox_app_entity as e on e.id = f.entity_id
                      inner join ox_app as app on app.id = f.app_id
                      $where";
            $response = array();
            $response['data'] = $this->executeQueryWithBindParameters($query, $params)->toArray();
            return $response;
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
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
            $this->logger->error($e->getMessage(), $e);
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
        ->where(array('ox_form.uuid' => $formId));
        $response = $this->executeQuery($select)->toArray();
        if (count($response)==0) {
            return 0;
        }
        return $response[0];
    }
     private function generateFields($fieldsList, $appId, $formId,$entityId)
    {
        try {
            $existingFieldsQuery = "select ox_field.* from ox_field where ox_field.entity_id=".$entityId.";";
            $existingFields = $this->executeQuerywithParams($existingFieldsQuery);
            $existingFields = $existingFields->toArray();
        } catch (Exception $e) {
            throw $e;
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
                $fieldData['entity_id'] = $entityId;
                try {
                    $fieldResult = $this->fieldService->saveField($appId, $fieldData);
                    $fieldIdArray[] = $fieldData['id'];
                    $fieldsCreated[] = $fieldData;
                    $createFormFieldEntry = $this->createFormFieldEntry($formId, $fieldData['id']);
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
        $existingFormFieldsQuery = "select ox_field.* from ox_field INNER JOIN ox_form_field ON ox_form_field.field_id=ox_field.id where ox_form_field.form_id=".$formId.";";
        $existingFormFields = $this->executeQuerywithParams($existingFormFieldsQuery);
        $existingFormFields = $existingFormFields->toArray();
        foreach ($existingFormFields as $existingField) {
            $fieldDeleted =  ArrayUtils::multiDimensionalSearch($fieldsList,'name',$existingField['name']);
            if(!isset($fieldDeleted)){
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
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }
}
