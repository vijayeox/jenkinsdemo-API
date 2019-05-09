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
use Bos\Service\FileService;

class WorkflowService extends AbstractService{
	
	private $id;
	private $baseFolder;

    /**
    * @ignore table
    */
    private $table;
    protected $config;
	protected $processManager;
	protected $fileService;
	protected $formService;
	protected $fieldService;

    public function __construct($config, $dbAdapter,WorkflowTable $table,FormService $formService,FieldService $fieldService,FileService $fileService,WorkflowFactory $workflowFactory){
    	parent::__construct($config, $dbAdapter);
    	$this->baseFolder = $this->config['DATA_FOLDER'];
    	$this->table = $table;
    	$this->config = $config;
    	$this->workFlowFactory = $workflowFactory;
    	$this->processManager = $this->workFlowFactory->getProcessManager();
    	$this->formService = $formService;
    	$this->fieldService = $fieldService;
    	$this->fileService = $fileService;
    }
    public function deploy($file,$appId,$data){
		$baseFolder = $this->config['DATA_FOLDER'];
		$workflowName = $data['name'];
		if(!isset($data['workflowId'])){
			if(!isset($data['app_id'])){
				$data['app_id'] = $appId;
			}
			$workFlow = $this->saveWorkflow($appId,$data);
			if($workFlow==0){
				return 0;
			}
			$workFlowId = $data['id'];
		} else {
			$workFlowId = $data['workflowId'];
		}
		$workFlowStorageFolder = $baseFolder."app/".$appId."/bpmn/";
    	$fileName = FileUtils::storeFile($file,$workFlowStorageFolder);
		$formList = $this->processManager->parseBPMN($workFlowStorageFolder."/".$fileName,$appId,$workFlowId);
    	$startFormId = null;
    	$workFlowList = array();
		$workFlowFormIds = array();
    	foreach ($formList as $form) {
			$formData = array();
			if(isset($form['form']['properties'])){
				$formProperties = json_decode($form['form']['properties'],true);
			}
    		$oxForm = new Form();
    		$oxForm->exchangeArray($form['form']);
			$oxFormProperties = $oxForm->getKeyArray();
			if(isset($formProperties)){
				foreach ($formProperties as $formKey => $formValue) {
					if(in_array($formKey, $formProperties)){
						$oxForm->__set($key,$formValue);
					}
				}
			}
    		$formData = $oxForm->toArray();
    		try {
				$formResult = $this->formService->createForm($appId,$formData);
    			$formIdArray[] = $formData['id'];
                if(isset($form['start_form'])){
                    $startFormId = $form['start_form'];
                }
    			$processIds[] = $formData['process_id'];
    			if($formResult){
    				if(!$this->generateFields($form['fields'],$appId,$formData['id'],$workFlowId)) {
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
		if(isset($workflowName)){
			$deployedData = array('id'=>$workFlowId,'app_id'=>$appId,'name'=>$workflowName,'process_ids'=>json_encode(array_unique($processIds)),'form_id'=>$startFormId,'file'=>$workFlowStorageFolder.$fileName);
			$workFlow = $this->saveWorkflow($appId,$deployedData);
		}
		return $deployedData?$deployedData:0;
    }
    public function saveWorkflow($appId,&$data){
		if(!isset($data['id'])){
			$data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
			$data['date_created'] = date('Y-m-d H:i:s');
		}
    	$data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
    	$data['date_modified'] = date('Y-m-d H:i:s');
    	$form = new Workflow();
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
			if(!isset($data['id'])){
				$id = $this->table->getLastInsertValue();
				$data['id'] = $id;
			}
    		$this->commit();
    	}catch(Exception $e){
    		switch (get_class ($e)) {
    			case "Bos\ValidationException" :
    			$this->rollback();
    			return 0;
    			break;
    			default:
    			$this->rollback();
    			return 0;
    			break;
    		}
    	}
    	return $count;
    }
    private function generateFields($fieldsList,$appId,$formId,$workFlowId){
    	$i=0;
		$fieldIdArray = array();
		$existingFields = $this->fieldService->getFields($appId,array('workflow_id'=>$workFlowId));
    	foreach ($fieldsList as $field) {
    		$oxField = new Field();
			$field['app_id'] = $appId;
			$field['workflow_id'] = $workFlowId;
			$oxField->exchangeArray($field);
			$oxFieldProps = array();
    		if(isset($field['properties'])){
    			$fieldProperties = json_decode($field['properties'],true);
    			$oxFieldProperties = $oxField->getKeyArray();
    			foreach ($fieldProperties as $fieldKey => $fieldValue) {
    				if(in_array($fieldKey, $fieldProperties)){
    					$oxField->__set($fieldKey,$fieldValue);
    				} else {
    					$oxFieldProps[] = array('name'=>$fieldKey,'value'=>$fieldValue);
    				}
    			}
    		}
    		$oxField->__set('properties',json_encode($oxFieldProps));
    		$fieldData = $oxField->toArray();
    		try {
    			$fieldResult = $this->fieldService->saveField($appId,$fieldData);
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

	public function updateWorkflow($id,&$data){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $data['id'] = $id;
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $file = $obj->toArray();
        $changedArray = array_merge($obj->toArray(),$data);
        $workflow = new Workflow();
        $workflow->exchangeArray($changedArray);
        $workflow->validate();
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->save($workflow);
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


    public function deleteWorkflow($appId,$id){
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->delete($id, ['app_id'=>$appId]);
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

    public function getWorkflows($appId=null,$filterArray = array()) {
        if(isset($appId)){
            $filterArray['app_id'] = $appId;
        }
        $resultSet = $this->getDataByParams('ox_workflow',array("*"),$filterArray,null);
        $response = array();
        $response['data'] = $resultSet->toArray();
        return $response;
    }
    public function getWorkflow($appId,$id) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_workflow')
        ->columns(array("*"))
        ->where(array('id' => $id,'app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        if(count($response)==0){
            return 0;
        }
        return $response[0];
    }
    public function getFields($appId,$workflowId) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_field')
        ->columns(array("*"))
        ->where(array('workflow_id' => $workflowId,'app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        return $response;
    }
    public function getForms($appId,$workflowId) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_form')
        ->columns(array("*"))
        ->where(array('workflow_id' => $workflowId,'app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        return $response;
	}
	public function saveFile($params,$id=null){
		if(isset($params['formId'])){
			$params['form_id'] = $params['formId'];
		} else {
			return 0;
		}
		if(isset($id)){
			return $this->fileService->updateFile($id,$params);
		} else {
			return $this->fileService->createFile($params);
		}
		return 0;
	}
	public function getFile($params){
		if(isset($params['fileId'])){
			return $this->fileService->getFile($params['fileId']);
		} else {
			return 0;
		}
	}
	public function deleteFile($params){
		if(isset($params['fileId'])){
			return $this->fileService->deleteFile($params['fileId']);
		} else {
			return 0;
		}
	}
}
?>