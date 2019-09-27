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
            $generateFields = $this->generateFields($template['fields'], $appId, $id);
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
            $generateFields = $this->generateFields($template['fields'], $this->getIdFromUuid('ox_app', $appUuid), $this->getIdFromUuid('ox_form', $formUuid));
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
        try{
            $select = "Select ox_workflow.uuid as workflow_id,ox_form.* from ox_form inner join ox_workflow on ox_workflow.form_id = ox_form.id where ox_form.uuid =?";
            $whereQuery = array($formId);
            $response = $this->executeQueryWithBindParameters($select,$whereQuery)->toArray();
            if (count($response)==0) {
                return 0;
            }
            return $response[0];
        }catch(Exception $e){
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
    }
    private function generateFields($fieldsList, $appId, $formId)
    {
        try {
            $deleteFields = "DELETE ox_field from ox_field INNER JOIN ox_form_field ON ox_form_field.field_id=ox_field.id where ox_form_field.form_id=:formId;";
            $deleteParams = array("formId" => $formId);
            $deleteFields = $this->executeQueryWithBindParameters($deleteFields,$deleteParams);
            $deleteFormFields = "DELETE from ox_form_field where form_id=:formId;";
            $deleteFormParams = array("formId" => $formId);
            $result = $this->executeQueryWithBindParameters($deleteFormFields,$deleteFormParams);
        } catch (Exception $e) {
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
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
                $fieldResult = $this->fieldService->saveField($appId, $fieldData);
                $fieldIdArray[] = $fieldData['id'];
                $createFormFieldEntry = $this->createFormFieldEntry($formId, $fieldData['id']);
            } catch (Exception $e) {
                foreach ($fieldIdArray as $fieldId) {
                    $id = $this->fieldService->deleteField($fieldId);
                    return 0;
                }
            }
            $i++;
        }
        if (count($fieldsList)==$i) {
            return 1;
        } else {
            return 0;
        }
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
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
    }
}
