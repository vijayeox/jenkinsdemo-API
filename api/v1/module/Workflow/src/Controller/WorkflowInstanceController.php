<?php
namespace Workflow\Controller;

/**
* Workflow Api
*/
use Workflow\Model\WorkflowInstanceTable;
use Workflow\Model\WorkflowInstance;
use Workflow\Service\WorkflowInstanceService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Oxzion\Service\WorkflowService;
use Workflow\Service\ActivityInstanceService;
use Oxzion\Workflow\Camunda\WorkflowException;

class WorkflowInstanceController extends AbstractApiController
{
    private $workflowInstanceService;
    private $workflowService;
    private $activityInstanceService;
    /**
    * @ignore __construct
    */
    public function __construct(WorkflowInstanceTable $table, WorkflowInstanceService $workflowInstanceService, WorkflowService $workflowService, ActivityInstanceService $activityInstanceService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, WorkflowInstance::class);
        $this->setIdentifierName('activityId');
        $this->workflowInstanceService = $workflowInstanceService;
        $this->workflowService = $workflowService;
        $this->activityInstanceService = $activityInstanceService;
    }
    public function activityAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        switch ($this->request->getMethod()) {
            case 'POST':
                unset($params['controller']);
                unset($params['action']);
                unset($params['access']);
                return $this->executeWorkflow($params);
                break;
            case 'GET':
                return $this->getInvalidMethod();
                break;
            case 'DELETE':
                return $this->getInvalidMethod();
                break;
            default:
                return $this->getErrorResponse("Not Sure what you are upto");
                break;
        }
    }


    private function executeWorkflow($params)
    {
        $this->log->info("executeWorkflow");
        $this->updateOrganizationContext($params);
        try {
            $count = $this->workflowInstanceService->executeWorkflow($params);
        } catch (ValidationException $e) {
            $response = ['data' => $params, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity Not Found Errors", 404, $params);
        }
        if (isset($id)) {
            return $this->getSuccessResponseWithData($params, 200);
        } else {
            return $this->getSuccessResponseWithData($params, 201);
        }
    }

    public function getFileListAction()
    { 
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        try {
            $count = $this->workflowInstanceService->getFileList($params, $filterParams);
        } catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        catch(AccessDeniedException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse($e->getMessage(),403, $response);
        }
        return $this->getSuccessResponseDataWithPagination($count['data'], $count['total']);
    }
    public function claimActivityInstanceAction()
    {
        $data = array_merge($this->extractPostData(),$this->params()->fromRoute());
        $this->log->info("Post Data- ". print_r(json_encode($data), true));
        try {
            $response = $this->activityInstanceService->claimActivityInstance($data);
            $this->log->info("Complete Activity Instance Successful");
            if ($response == 0) {
                return $this->getErrorResponse("Entity not found", 404);
            }
            return $this->getSuccessResponse();
        } catch (ValidationException $e) {
            $this->log->info("Exception at Add Activity Instance-".$e->getMessage());
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }catch(WorkflowException $e){ 
            $this->log->info("-Error while claiming - ".$e->getReason().": ". $e->getMessage());
            if($e->getReason() == 'TaskAlreadyClaimedException'){
                return $this->getErrorResponse("Task is already claimed", 409);    
            }
            
            return $this->getErrorResponse($e->getMessage(), 409);
        }
    }
    public function activityInstanceFormAction(){
        $data = array_merge($this->extractPostData(),$this->params()->fromRoute());
        if(isset($data['activityInstanceId'])){
            try {
                $response = $this->activityInstanceService->getActivityInstanceForm($data);
                if ($response == 0) {
                    return $this->getErrorResponse("Entity not found", 404);
                }
                return $this->getSuccessResponseWithData($response);
            } catch (ValidationException $e) {
                $this->log->info("Exception at Add Activity Instance-".$e->getMessage());
                $response = ['data' => $data, 'errors' => $e->getErrors()];
                return $this->getErrorResponse("Validation Errors", 404, $response);
            }
        } else {
            return $this->getErrorResponse("Entity not found", 404);
        }
    }

    public function getFileDocumentListAction()
    {  
        $params = $this->params()->fromRoute();
        try {
            $result = $this->workflowInstanceService->getFileDocumentList($params);
        } catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        catch(AccessDeniedException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse($e->getMessage(),403, $response);
        }
        return $this->getSuccessResponseWithData($result,200);
    }
}
