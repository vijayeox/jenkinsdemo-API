<?php
namespace Workflow\Controller;

/**
* Activity Instance Api
*/
use Zend\Log\Logger;
use Workflow\Model\ServiceTaskInstance;
use Workflow\Service\ServiceTaskService;
use Workflow\Service\WorkflowInstanceService;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;

class ServiceTaskController extends AbstractApiControllerHelper
{
    private $serviceTaskService;
    private $workflowInstanceService;
    private $log;
    
    /**
    * @ignore __construct
    */
    public function __construct(ServiceTaskService $serviceTaskService,WorkflowInstanceService $workflowInstanceService, Logger $log)
    {
        $this->serviceTaskService = $serviceTaskService;
        $this->workflowInstanceService = $workflowInstanceService;
        $this->log = $log;
    }
    /**
    * Activity Instance API
    * @api
    * @method POST
    * @link /execute/servicetask
    * @return array success|failure response
    */

    public function executeAction()
    {
        $data = $this->extractPostData();
        $this->log->info(ServiceTask::class.":Post Data- ". print_r(json_encode($data), true));
        try {
            $response = $this->serviceTaskService->runCommand($this->extractPostData());
            if($response == 1){
                return $this->getSuccessResponse();
            }
            if ($response) {
                $this->log->info(ServiceTask::class.":Workflow Step Successfully Executed");
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getErrorResponse("Failed to perform Service Task", 200);
            }
        } catch (ValidationException $e) {
            $this->log->info(ServiceTask::class.":Exception while Performing Service Task-".$e->getMessage());
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
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
                        $response = ['data' => $params, 'errors' => $e->getErrors()];
                        return $this->getErrorResponse("workflow Instance errors Errors", 404, $response);
                    }
                    return $this->getSuccessResponseWithData($response, 200);
                } else {
                    return $this->getErrorResponse("Process Instance Id not set", 404, $response);
                }
                break;
        }
    }
}
