<?php
namespace Workflow\Controller;

/**
 * Workflow Api
 */
use Exception;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\WorkflowService;
use Oxzion\Workflow\Camunda\WorkflowException;
use Oxzion\Model\WorkflowInstance;
use Oxzion\Model\WorkflowInstanceTable;
use Oxzion\Service\ActivityInstanceService;
use Oxzion\Service\WorkflowInstanceService;
use Zend\Db\Adapter\AdapterInterface;

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

    public function startWorkflowAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        $this->log->info(print_r($params, true));
        $this->log->info("executeWorkflow");
        $this->workflowInstanceService->updateAccountContext($params);
        try {
            $count = $this->workflowInstanceService->startWorkflow($params);
            $this->log->info(WorkflowInstanceController::class . "ExecuteWorkflow Response  - " . print_r($count, true));
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseWithData($params, 200);
    }

    public function submitAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        $this->log->info(print_r($params, true));
        $this->workflowInstanceService->updateAccountContext($params);
        try {
            $count = $this->workflowInstanceService->submitActivity($params);
            $this->log->info(WorkflowInstanceController::class . "SubmitActivity Response  - " . print_r($count, true));
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        return $this->getSuccessResponseWithData($params, 200);
    }

    public function claimActivityInstanceAction()
    {
        $data = array_merge($this->extractPostData(), $this->params()->fromRoute());
        $this->log->info("Post Data- " . print_r(json_encode($data), true));
        try {
            $this->activityInstanceService->claimActivityInstance($data);
            $this->log->info("Claim Activity Instance Successful");
            return $this->getSuccessResponse();
        }catch (WorkflowException $e) {
            $this->log->info("-Error while claiming - " . $e->getReason() . ": " . $e->getMessage());
            if ($e->getReason() == 'TaskAlreadyClaimedException') {
                return $this->getErrorResponse("Task is already claimed", 409);
            }
            return $this->exceptionToResponse($e);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    public function unclaimActivityInstanceAction()
    {
        $data = array_merge($this->extractPostData(), $this->params()->fromRoute());
        $this->log->info("Post Data- " . print_r(json_encode($data), true));
        try {
            $this->activityInstanceService->unclaimActivityInstance($data);
            $this->log->info("Unclaim Activity Instance Successful");
            return $this->getSuccessResponse();
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    public function reclaimActivityInstanceAction()
    {
        $data = array_merge($this->extractPostData(), $this->params()->fromRoute());
        $this->log->info("Post Data- " . print_r(json_encode($data), true));
        try {
            $this->activityInstanceService->reclaimActivityInstance($data);
            $this->log->info("Reclaim Activity Instance Successful");
            return $this->getSuccessResponse();
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }



    public function activityInstanceFormAction()
    {
        $data = array_merge($this->extractPostData(), $this->params()->fromRoute());
        if (isset($data['activityInstanceId'])) {
            try {
                $response = $this->activityInstanceService->getActivityInstanceForm($data);
                return $this->getSuccessResponseWithData($response);
            } catch (ValidationException $e) {
                $this->log->info("Exception while getting Activity Instance form-" . $e->getMessage());
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
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (AccessDeniedException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse($e->getMessage(), 403, $response);
        }
        return $this->getSuccessResponseWithData($result, 200);
    }

    public function getActivityLogAction(){
        $params = $this->params()->fromRoute();
        $filterParams = $this->params()->fromQuery();
        try{
            $result = $this->workflowInstanceService->getActivityLog($params['fileId'], $params['appId'], $filterParams);
            if ($result == 0) {
                return $this->getErrorResponse("No Activity found for the specified file", 404, ['id' => $fileId]);
            }
        }catch(Exception $e){
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 400);
        }
        return $this->getSuccessResponseWithData($result,200);
    }

    public function getFieldDiffAction(){
        $params = $this->params()->fromRoute();
        try{
            $result = $this->activityInstanceService->getActivityChangeLog($params['activityInstanceId']);
            if (count($result) == 0) {
                return $this->getErrorResponse("No Activity found for the specified ID", 404, ['id' => $params['activityInstanceId']]);
            }
        }catch(Exception $e){
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 400);
        }
        return $this->getSuccessResponseWithData($result,200);
    }
}
