<?php
namespace Workflow\Controller;

/**
* Workflow Api
*/
use Zend\Log\Logger;
use Workflow\Model\WorkflowInstanceTable;
use Workflow\Model\WorkflowInstance;
use Workflow\Service\WorkflowInstanceService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Oxzion\Service\WorkflowService;
use Workflow\Service\ActivityInstanceService;

class WorkflowInstanceCallbackController extends AbstractApiControllerHelper
{
    private $workflowInstanceService;
    private $workflowService;
    private $activityInstanceService;
    /**
    * @ignore __construct
    */
    public function __construct(WorkflowInstanceTable $table, WorkflowInstanceService $workflowInstanceService, Logger $log, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('activityId');
        $this->workflowInstanceService = $workflowInstanceService;
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
                    return $this->getSuccessResponse();
                } else {
                    return $this->getErrorResponse("Process Instance Id not set", 404, $response);
                }
                break;
        }
    }
}
