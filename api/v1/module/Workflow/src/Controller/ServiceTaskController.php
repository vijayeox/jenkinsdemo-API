<?php
namespace Workflow\Controller;

/**
 * Activity Instance Api
 */
use Exception;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\EntityNotFoundException;
use Oxzion\Service\ServiceTaskService;
use Oxzion\Service\WorkflowInstanceService;
use Oxzion\ValidationException;

class ServiceTaskController extends AbstractApiControllerHelper
{
    private $serviceTaskService;
    private $workflowInstanceService;
    private $log;
    /**
     * @ignore __construct
    */
    public function __construct(ServiceTaskService $serviceTaskService, WorkflowInstanceService $workflowInstanceService)
    {
        $this->serviceTaskService = $serviceTaskService;
        $this->workflowInstanceService = $workflowInstanceService;
        $this->log = $this->getLogger();
    }
    /**
     * Activity Instance API
     * Supported commands
     *           mail : JSON containing the following fields
     *                   to : <recipients>
     *                   subject : <subject text>
     *                   body : <Email body>
     *                   atachments : <path to the files to be attached>
     *           schedule : Sets up a scheduled job using the values provided as below
     *                   url : The action to be called on the scheduler api
     *                   cron : The cron expression for triggering the job
     *                   jobUrl : The url to be invoked when the job is triggered
     *                   rest of the data is sent as payload to the job
     *
     *           delegate : execute the Delegate component provided in the
     *                       'delegate' property
     *
     *           fileSave : save the data passed to the file corresponding to the
     *                       'workflow_instance_id' property received in teh data
     *
     *           file : checks to get the fileId from the field specified in
     *                   'fileId_fieldName' attribute.
     *                  If not provided will use the value in 'fileId' attribute in the data
     *                  else throws EntityNotFoundException
     *
     *
     *
     * @api
     * @method POST
     * @link /execute/servicetask
     * @return array success|failure response
     */

    public function executeAction()
    {
        $data = $this->extractPostData();
        try {
            $response = $this->serviceTaskService->executeServiceTask($data, $this->getRequest());
            if ($response && is_array($response)) {
                $this->log->info(":Workflow Step Successfully Executed - " . print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        } catch (ValidationException $e) {
            $this->log->error(":Exception while Performing Service Task-" . $e->getMessage(), $e);
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 406, $response);
        } catch (EntityNotFoundException $e) {
            $this->log->info(":Entity Not found -" . $e->getMessage());
            $response = ['data' => $data];
            return $this->getErrorResponse($e->getMessage(), 404, $response);
        } catch (Exception $e) {
            $this->log->error(":Error -" . $e->getMessage(), $e);
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
                        if (!$response) {
                            return $this->getErrorResponse("Workflow Completion errors", 404, null);
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
