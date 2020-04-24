<?php
namespace Workflow\Controller;

/**
* Workflow Api
*/
use Oxzion\Model\WorkflowInstanceTable;
use Oxzion\Model\WorkflowInstance;
use Oxzion\Service\WorkflowInstanceService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Oxzion\Service\WorkflowService;
use Oxzion\Service\ActivityInstanceService;

class WorkflowInstanceCallbackController extends AbstractApiControllerHelper
{
    private $workflowInstanceService;
    private $workflowService;
    private $activityInstanceService;
    private $log;
    /**
    * @ignore __construct
    */
    public function __construct(WorkflowInstanceTable $table, WorkflowInstanceService $workflowInstanceService, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('activityId');
        $this->workflowInstanceService = $workflowInstanceService;
        $this->log = $this->getLogger();
    }
    public function completeWorkflowAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        switch ($this->request->getMethod()) {
            case 'POST':
                if (isset($params['processInstanceId'])) {
                    try {
                        $response = $this->workflowInstanceService->completeWorkflow($params);
                        if(!$response){
                            return $this->getErrorResponse("Workflow Completion errors", 404,null);
                        }
                    } catch (ValidationException $e) {
                        $this->log->error($e->getMessage(), $e);
                        $response = ['data' => $params, 'errors' => $e->getErrors()];
                        return $this->getErrorResponse("workflow Instance errors Errors", 404, $response);
                    }catch (Exception $e){
                        $this->log->error($e->getMessage(), $e);
                        return $this->getErrorResponse($e->getMessage(), 500, $response);
                    }
                    return $this->getSuccessResponse();
                } else {
                    return $this->getErrorResponse("Process Instance Id not set", 404);
                }
                break;
        }
    }

    public function initiateWorkflowAction(){
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        try {
            $response = $this->workflowInstanceService->initiateWorkflow($params);
            if(!$response){
                return $this->getErrorResponse("Workflow Start errors", 404,null);
            }
            return $this->getSuccessResponse();
        }
        catch (ValidationException $e) {
            $this->log->error($e->getMessage(), $e);
            $response = ['data' => $params, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("workflow Instance errors Errors", 404, $response);
        }  
        catch (InvalidParameterException $e) {
            $this->log->error($e->getMessage(), $e);
            $response = ['data' => $params, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Invalid Data", 404, $response);
        }   
    }
}
