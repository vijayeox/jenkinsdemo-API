<?php
namespace Workflow\Controller;

/**
 * Activity Instance Api
 */
use Exception;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\ServiceTaskService;
use Oxzion\Service\WorkflowInstanceService;

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
            unset($response['version']);
            if ($response && is_array($response)) {
                $this->log->info(":Workflow Step Successfully Executed - " . print_r($response, true));
                return $this->getSuccessResponseWithData($response, 200);
            } else {
                return $this->getSuccessResponse();
            }
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
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
                    } catch (Exception $e) {
                        $this->log->error($e->getMessage(), $e);
                        return $this->exceptionToResponse($e);
                    }
                    return $this->getSuccessResponseWithData($response, 200);
                } else {
                    return $this->getErrorResponse("Process Instance Id not set", 404, $response);
                }
                break;
        }
    }
}
