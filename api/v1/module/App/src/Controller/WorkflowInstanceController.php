<?php
namespace App\Controller;
/**
* Workflow Api
*/
use Zend\Log\Logger;
use Oxzion\Model\Workflow;
use Oxzion\Model\WorkflowTable;
use Oxzion\Service\WorkflowService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Bos\ValidationException;

class WorkflowInstanceController extends AbstractApiController
{
    private $workflowService;
    /**
    * @ignore __construct
    */
	public function __construct(WorkflowTable $table, WorkflowService $workflowService, Logger $log, AdapterInterface $dbAdapter) {
		parent::__construct($table, $log, __CLASS__, Workflow::class);
		$this->setIdentifierName('activityId');
		$this->workflowService = $workflowService;
	}
	public function activityAction(){
        $params = array_merge($this->params()->fromPost(),$this->params()->fromRoute());
        switch ($this->request->getMethod()) {
            case 'POST':
                unset($params['controller']);
                unset($params['action']);
                unset($params['access']);
                if(isset($params['workflowId'])){
                    return $this->executeWorkflow($params,$params['workflowId']);
                } else {
                    return $this->executeWorkflow($params);
                }
                break;
            case 'GET':
                return $this->getFieldData($params);
                break;
            case 'DELETE':
                return $this->deleteFieldData($params);
                break;
            default:
                return $this->getErrorResponse("Not Sure what you are upto");
                break;
        }
    }
    private function executeWorkflow($params,$id = null){
        try{
            $count = $this->workflowService->executeWorkflow($params,$id);
        } catch (ValidationException $e){
            $response = ['data' => $params, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getFailureResponse("Failed to create a new entity", $params);
        }
        if(isset($id)){
            return $this->getSuccessResponseWithData($params,200);
        } else {
            return $this->getSuccessResponseWithData($params,201);
        }
    }
    private function getFieldData($params){
        if(!isset($params['workflowId'])){
            return $this->getInvalidMethod();
        }
        $result = $this->workflowService->getFile($params);
        if($result == 0){
            return $this->getErrorResponse("File not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }
    private function deleteFieldData($params){
        if(!isset($params['instanceId'])){
        
        }
        $response = $this->workflowService->deleteFile($params);
        if($response == 0){
            return $this->getErrorResponse("File not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
}