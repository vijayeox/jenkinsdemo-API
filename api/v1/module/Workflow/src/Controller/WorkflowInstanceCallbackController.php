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
use Oxzion\Service\WorkflowService;
use Oxzion\Service\ActivityInstanceService;
use Exception;

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
                    }catch (Exception $e) {
                        $this->log->error($e->getMessage(), $e);
                        return $this->exceptionToResponse($e);
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
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }
}
