<?php
namespace Oxzion\Service;

use Zend\Db\Sql\Sql;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\Service\AbstractService;
use Bos\ValidationException;
use Oxzion\Model\Workflow;
use Oxzion\Model\WorkflowTable;
use Oxzion\Model\Form;
use Oxzion\Service\FormService;
use Oxzion\Model\FormTable;
use Oxzion\Model\Field;
use Oxzion\Service\FieldService;
use Oxzion\Model\FieldTable;
use Oxzion\Workflow\WorkFlowFactory;
use Oxzion\Utils\FileUtils;

class WorkflowService extends AbstractService{
	
	private $id;
	private $baseFolder;

    /**
    * @ignore table
    */
    private $table;
    protected $config;
    protected $processManager;

    public function __construct($config, $dbAdapter,WorkflowTable $table,FormTable $formTable,FieldTable $fieldTable){
    	parent::__construct($config, $dbAdapter);
    	$this->baseFolder = $this->config['DATA_FOLDER'];
    	$this->table = $table;
    	$this->config = $config;
    	$this->workFlowFactory = WorkFlowFactory::getInstance();
    	$this->processManager = $this->workFlowFactory->getProcessManager();
    	$this->formService = new FormService($config,$dbAdapter,$formTable);
    	$this->fieldService = new FieldService($config,$dbAdapter,$fieldTable);
    }
    public function deploy($file,$data){
    	$workFlowStorageFolder = $this->baseFolder."organization/".AuthContext::get(AuthConstants::ORG_ID)."/app_workflows/";
    	$fileName = $file['name'];
    	FileUtils::storeFile($file,$workFlowStorageFolder);
    	$formList = $this->processManager->parseBPMN($workFlowStorageFolder."/".$fileName,$data['app_id']);
    	$formIdArray = array();
    	$workFlowList = array();
        $workFlowFormIds = array();
    	foreach ($formList as $form) {
    		$formProperties = json_decode($form['form']['properties'],true);
    		$oxForm = new Form();
    		$oxForm->exchangeArray($form['form']);
    		$oxFormProperties = $oxForm->getKeyArray();
    		foreach ($formProperties as $formKey => $formValue) {
    			if(in_array($formKey, $formProperties)){
    				$oxForm->__set($key,$formValue);
    			}
    		}
    		$formData = $oxForm->toArray();
    		try {
    			$formResult = $this->formService->createForm($formData);
    			$formIdArray[] = $formData['id'];
    			$deployedProcess = $this->processManager->deploy(AuthContext::get(AuthConstants::ORG_ID),$data['name'],array($file));
    			$processIds[] = $formData['process_id'];
    			if($formResult && $workFlow){
    				if(!$this->generateFields($form['fields'],$formData['id'])) {
    					return 0;
    				}
    			} else {
    				$formResult = $this->formService->deleteForm($formData['id']);
    				return 0;
    			}
    		} catch (Exception $e){
    			foreach ($formIdArray as $formCreatedId) {
    				$id = $this->formService->deleteForm($formCreatedId);
    			}
    			return 0;
    		}
    	}
        $data = array('app_id'=>$data['app_id'],'name'=>$file['name'],'process_ids'=>json_encode(array_unique($processIds)),'form_ids'=>json_encode(array_unique($formIdArray)),'file'=>$workFlowStorageFolder.$file['name']);
        $workFlow = $this->addWorkflow($data);
    	return $workFlow?$workFlow:0;
    }
    private function addWorkflow(&$data){
    	$form = new Workflow();
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
    private function generateFields($fieldsList,$formId){
    	$i=0;
    	$fieldIdArray = array();
    	foreach ($fieldsList as $field) {
    		$oxField = new Field();
    		$field['form_id'] = $formId;
    		$oxField->exchangeArray($field);
    		if(isset($field['properties'])){
    			$fieldProperties = json_decode($field['properties'],true);
    			$oxFieldProperties = $oxField->getKeyArray();
    			foreach ($fieldProperties as $fieldKey => $fieldValue) {
    				if(in_array($fieldKey, $fieldProperties)){
    					$oxField->__set($fieldKey,$fieldValue);
    				} else {
    					$oxFormProps[] = array('name'=>$fieldKey,'value'=>$fieldValue);
    				}
    			}
    		}
    		$oxField->__set('properties',json_encode($oxFormProps));
    		$fieldData = $oxField->toArray();
    		try {
    			$fieldResult = $this->fieldService->createField($formId,$fieldData);
    			$fieldIdArray[] = $fieldData['id'];
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

    
}
?>