<?php
namespace Oxzion\Service;

use Oxzion\Model\FormTable;
use Oxzion\Model\Form;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Oxzion\ServiceException;
use Zend\Db\Sql\Expression;
use Exception;
use Oxzion\Service\FieldService;
use Oxzion\Model\Field;
use Oxzion\Model\FieldTable;
use Oxzion\FormEngine\FormFactory;
use Oxzion\Utils\ArrayUtils;
use Oxzion\Utils\UuidUtil;

class FormService extends AbstractService
{
    private $formFileExt = ".json";
    
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
        $this->logger->info("EXECUTING CREATE FORM ");
        $form = new Form();
        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] :  UuidUtil::uuid();
        if(isset($data['template']) && is_array($data['template'])){
            $data['template'] = json_encode($data['template']);
        }else{
            if(isset($data['template'])&&is_string($data['template'])){
                $data['template'] = $data['template'];
            } else {
                throw new ServiceException("Template not provided", 'template.required');
            }
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
            throw new Exception("Invalid AppId $appUuid passed");
        }
        $template['form']['app_id'] = $appId;
        // $data['name'] = $template['form']['name'];
        $template['form']['uuid'] = $data['uuid'];
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
        $this->logger->info("EXECUTING UPDATE FORM");
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
        $this->logger->info("EXECUTING DELETE FORM");
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
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        
        return $count;
    }

    public function getForms($appUuid=null, $filterArray=array())
    {
        $this->logger->info("EXECUTING GET FORMS");
        try{
            $where = "";
            $params = array();
            if (isset($appUuid)) {
                $where ."where app.uuid = :appId";
                $params['appId'] = $appUuid;
            }
            //TODO handle the $filterArray using FilterUtils
            $query = "select f.name, e.uuid as entity_id, f.uuid as form_id from 
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
        $this->logger->info("EXECUTING GET FORM");
        try{
            $queryString = "Select name, app_id, uuid from ox_form where uuid=?";
            $queryParams = array($uuid);
            $resultSet = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
            if (count($resultSet)==0) {
                return 0;
            }
            $data = $resultSet[0];
            $appId = $this->getUuidFromId("ox_app", $data['app_id']);
            $path = $this->config['FORM_FOLDER'].$appId."/".$data['name'].$this->formFileExt;
            $this->logger->info("Form template - $path");
            if(file_exists($path)){
               $data['template'] = file_get_contents($path);
            }
            unset($data['app_id']);
            return $data;
        }catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function getWorkflow($formId)
    {
        $this->logger->info("EXECUTING GET WORKFLOW");
        $select = "SELECT f.*, a.id as activity_id, w.uuid as workflow_id from ox_form f
                 left join ox_activity_form af on af.form_id = f.id
                 left join ox_activity a on a.id = af.form_id
                 inner join ox_workflow_deployment wd on wd.form_id = f.id
                 inner join ox_workflow w on wd.workflow_id = w.id
                 where f.uuid=:formId and wd.latest=1";
        $params = array("formId" => $formId);
        $this->logger->info("Executing query - $select with params - ".json_encode($params));
        $response = $this->executeQueryWithBindParameters($select,$params)->toArray();
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
            $this->saveField($existingFields,$field,$fieldsCreated,$fieldIdArray,$appId,$formId,$entityId);
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
        $select = "SELECT * FROM `ox_form_field` WHERE form_id=:formId AND field_id=:fieldId";
        $insertParams = array("formId" => $formId,"fieldId" =>$fieldId);
        $result = $this->executeQueryWithBindParameters($select,$insertParams)->toArray();
        if(count($result) > 0){
            return;
        }
        $this->beginTransaction();
        try {
            $insert = "INSERT INTO `ox_form_field` (`form_id`,`field_id`) VALUES (:formId,:fieldId)";
            $resultSet = $this->executeQueryWithBindParameters($insert,$insertParams);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    private function saveField(&$existingFields,&$field,&$fieldsCreated,&$fieldIdArray,$appId,$formId,$entityId){
            if(isset($field['parent'])){
                $parentField =  ArrayUtils::multiDimensionalSearch($existingFields,'name',$field['parent']['name']);
                $this->logger->info("PARENT FIELD----".json_encode($parentField));
                if(!$parentField){
                    $this->saveField($existingFields,$field['parent'],$fieldsCreated,$fieldIdArray,$appId,$formId,$entityId);
                    $parentField = $field['parent'];
                }
                $field['parent_id'] = $parentField['id'];
                unset($field['parent']);
                $foundField = ArrayUtils::multiFieldSearch($existingFields,array('name' => $field['name'],'parent_id' => $field['parent_id']));
            }else{
               $foundField =  ArrayUtils::multiDimensionalSearch($existingFields,'name',$field['name']); 
            }            
            $field['app_id'] = $appId;
            $field['entity_id'] = $entityId;
            $oxField = new Field();
            if($foundField){
                $oxField->exchangeArray($foundField);
            }
            $oxField->exchangeArray($field);
            $fieldData = $oxField->toArray();
            try {
                $fieldResult = $this->fieldService->saveField($appId, $fieldData);
                $fieldIdArray[] = $fieldData['id'];
                $fieldsCreated[] = $fieldData;
                if(!$foundField){
                    $existingFields[] = $fieldData;
                    $field['id'] = $fieldData['id'];
                }
                $createFormFieldEntry = $this->createFormFieldEntry($formId, $fieldData['id']);
            } catch (Exception $e) {
                foreach ($fieldIdArray as $fieldId) {
                    $id = $this->fieldService->deleteField($appId,$fieldId);
                    return 0;
                }
            }            
    }

    public function getChangeLog($formId,$startData,$completionData,$labelMapping){
        $fieldSelect = "SELECT ox_field.name,ox_field.template,ox_field.type,ox_field.text,ox_field.data_type,COALESCE(parent.name,'') as parentName,COALESCE(parent.text,'') as parentText,parent.data_type as parentDataType FROM ox_field 
                    left join ox_field as parent on ox_field.parent_id = parent.id 
                    inner join ox_form_field off on off.field_id = ox_field.id WHERE off.form_id=:formId AND ox_field.type NOT IN ('hidden','file','document','documentviewer') ORDER BY parentName, ox_field.name ASC";
        $fieldParams = array('formId' => $formId);
        $resultSet = $this->executeQueryWithBindParameters($fieldSelect,$fieldParams)->toArray();
        $resultData = array();
        $gridResult = array();
        foreach ($resultSet as $key => $value) {
            if($value['data_type'] == 'grid' || $value['data_type'] == 'survey'){
                continue;
            }
            $initialparentData = null;
            $submissionparentData = null;
            if($value['parentName'] !="") {
                if(isset($gridResult[$value['parentName']])){
                    $gridResult[$value['parentName']]['fields'][] = $value;
                } else {
                    $initialParentData =  isset($startData[$value['parentName']]) ? $startData[$value['parentName']] : '[]';
                    $initialParentData =   is_string($initialParentData) ? json_decode($initialParentData, true) : $initialParentData;
                    // checkbox check 
                    // coverage check within grid
                    $submissionparentData = isset($completionData[$value['parentName']]) ? $completionData[$value['parentName']] : '[]';
                    $submissionparentData =   is_string($submissionparentData) ? json_decode($submissionparentData, true) : $submissionparentData;
                    $gridResult[$value['parentName']] = array("initial" => $initialParentData, "submission" => $submissionparentData, 'fields' => array($value));
                }
                
            } else{
                $this->buildChangeLog($startData, $completionData, $value, $labelMapping, $resultData);
            }         
        }
        if(count($gridResult) > 0){    
            foreach($gridResult as $parentName => $data){
                $initialDataset = $data['initial'];
                $submissionDataset = $data['submission'];
                $count = max(count($initialDataset), count($submissionDataset));
                for($i = 0; $i < $count; $i++) {
                    $initialRowData = isset($initialDataset[$i]) ? $initialDataset[$i] : array();
                    $submissionRowData = isset($submissionDataset[$i]) ? $submissionDataset[$i] : array();
                    foreach($data['fields'] as $key => $field) {
                        $this->buildChangeLog($initialRowData, $submissionRowData, $field, $labelMapping, $resultData);
                    }
                }
            }
         }
        return $resultData;
    }

    public function getFieldValue($startDataTemp,$value,$labelMapping=null){
        if(!isset($startDataTemp[$value['name']])){
            return "";
        }
        $initialData = $startDataTemp[$value['name']];
        if($value['data_type'] == 'text'){
            //handle string data being sent
            if(is_string($initialData)){
                $fieldValue = json_decode($initialData, true);
            } else {
                $fieldValue = $initialData;
            }
            //handle select component values having an object with keys value and label 
            if(!empty($fieldValue) && is_array($fieldValue)){
                //Add Handler for default Labels
                if(isset($fieldValue['label'])){
                    $initialData = $fieldValue['label'];
                } else {
                    // Add for single values array
                    if(isset($fieldValue[0]) && count($fieldValue) == 1){
                        $initialData = $fieldValue[0];
                    } else {
                        //Case multiple values allowed
                        if(count($fieldValue) > 1){
                            foreach ($fieldValue as $k => $v) {
                                $initialData .= $v;
                            } 
                        }
                    }
                }
            }
       
        }else if($value['data_type'] == 'boolean'){
            if((is_bool($initialData) && $initialData == false) || (is_string($initialData) && ($initialData=="false" || $initialData=="0"))){
                $initialData = "No";
            } else {
                $initialData = "Yes";
            }
        }
        if($value['type'] =='radio'){
            $radioFields =json_decode($value['template'],true);
            if(isset($radioFields['values'])){
                foreach ($radioFields['values'] as $key => $radiovalues) {
                    if($initialData == $radiovalues['value']){
                        $initialData = $radiovalues['label'];
                        break;
                    }
                }
            }
        }
        if($value['type'] =='selectboxes'){
            $radioFields =json_decode($value['template'],true);
            if(is_string($initialData)){
                $selectValues = json_decode($initialData,true);
            } else {
                if(is_array($initialData)){
                    $selectValues = $initialData;
                }
            }
            $initialData = "";
            $processed =0;
            foreach ($selectValues as $key => $value) {
                if($value == 1){
                    if($processed == 0){
                       $radioFields = ArrayUtils::convertListToMap($radioFields['values'],'value','label');
                        $processed = 1;
                    }
                    if(isset($radioFields[$key])){
                        if($initialData !=""){
                            $initialData = $initialData . ",";
                        }
                        $initialData .= $radioFields[$key];
                    }
                }
            }
        }
        if($labelMapping && !empty($initialData) && isset($labelMapping[$initialData])){
            $initialData = $labelMapping[$initialData];
        }
        return $initialData;
    }

    private function buildChangeLog($startData, $completionData, $value, $labelMapping, &$resultData){
        $initialData =  $this->getFieldValue($startData,$value,$labelMapping);
        $submissionData = $this->getFieldValue($completionData,$value,$labelMapping);
        if((isset($initialData) && ($initialData != '[]') && (!empty($initialData))) || 
                (isset($submissionData) && ($submissionData != '[]') && (!empty($submissionData)))){
                $resultData[] = array('name' => $value['name'],
                                       'text' => $value['text'],
                                       'dataType' => $value['data_type'],
                                       'parentName' => $value['parentName'],
                                       'parentText' => $value['parentText'],
                                       'parentDataType' => $value['parentDataType'],
                                       'initialValue' => $initialData,
                                       'submittedValue' => $submissionData);
        }
    }
}
