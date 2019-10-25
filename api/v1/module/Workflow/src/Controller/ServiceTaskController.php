<?php
namespace Workflow\Controller;

/**
* Activity Instance Api
*/
use Workflow\Model\ServiceTaskInstance;
use Workflow\Service\ServiceTaskService;
use Workflow\Service\WorkflowInstanceService;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Oxzion\EntityNotFoundException;
use Exception;

class ServiceTaskController extends AbstractApiControllerHelper
{
    private $serviceTaskService;
    private $workflowInstanceService;
    private $log;
    /**
    * @ignore __construct
    */
    public function __construct(ServiceTaskService $serviceTaskService,WorkflowInstanceService $workflowInstanceService)
    {
        $this->serviceTaskService = $serviceTaskService;
        $this->workflowInstanceService = $workflowInstanceService;
        $this->log = $this->getLogger();
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

            $response = $this->serviceTaskService->runCommand($data);
            if ($response && is_array($response)) {
                $this->log->info(ServiceTask::class.":Workflow Step Successfully Executed - ".print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        } catch (ValidationException $e) {
            $this->log->info(ServiceTask::class.":Exception while Performing Service Task-".$e->getMessage());
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        }catch (EntityNotFoundException $e){
            $this->log->info(ServiceTask::class.":Entity Not found -".$e->getMessage());
            $response = ['data' => $data];
            return $this->getErrorResponse($e->getMessage(), 404, $response);
        }
        catch (Exception $e){
            $this->log->error(ServiceTask::class.":Entity Not found -".$e->getMessage());
            $response = ['data' => $data];
            return $this->getErrorResponse($e->getMessage(), 500, $response);
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
