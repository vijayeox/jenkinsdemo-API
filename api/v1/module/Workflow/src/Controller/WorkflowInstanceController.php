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
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Oxzion\Service\WorkflowService;

class WorkflowInstanceController extends AbstractApiController
{
    private $workflowInstanceService;
    private $workflowService;
    /**
    * @ignore __construct
    */
    public function __construct(WorkflowInstanceTable $table, WorkflowInstanceService $workflowInstanceService, WorkflowService $workflowService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __CLASS__, WorkflowInstance::class);
        $this->setIdentifierName('activityId');
        $this->workflowInstanceService = $workflowInstanceService;
        $this->workflowService = $workflowService;
    }
    public function activityAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        switch ($this->request->getMethod()) {
            case 'POST':
                unset($params['controller']);
                unset($params['action']);
                unset($params['access']);
                if (isset($params['instanceId'])) {
                    return $this->executeWorkflow($params, $params['instanceId']);
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
    private function executeWorkflow($params, $id = null)
    {
        try {
            $count = $this->workflowInstanceService->executeWorkflow($params, $id);
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
    private function getFieldData($params)
    {
        if (isset($params['instanceId'])) {
            $result = $this->workflowService->getFile($params);
        } else {
            return $this->getInvalidMethod();
        }
        if ($result == 0) {
            return $this->getErrorResponse("File not found", 404);
        }
        return $this->getSuccessResponseWithData($result);
    }
    private function deleteFieldData($params)
    {
        if (!isset($params['workflowId'])) {
            return $this->getInvalidMethod();
        }
        $response = $this->workflowService->deleteFile($params);
        if ($response == 0) {
            return $this->getErrorResponse("File not found", 404);
        }
        return $this->getSuccessResponse();
    }
}
