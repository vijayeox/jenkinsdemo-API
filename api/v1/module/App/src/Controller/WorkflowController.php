<?php
namespace App\Controller;

/**
* Workflow Api
*/
use Oxzion\Model\Workflow;
use Oxzion\Model\WorkflowTable;
use Oxzion\Service\WorkflowService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Exception;

/**
 * Workflow Controller
 */
class WorkflowController extends AbstractApiController
{
    /**
    * @ignore WorkflowService
    */
    private $workflowService;
    /**
    * @ignore __construct
    */
    public function __construct(WorkflowTable $table, WorkflowService $workflowService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Workflow::class);
        $this->setIdentifierName('workflowId');
        $this->workflowService = $workflowService;
    }
    /**
    * Create Workflow API
    * @api
    * @link /Workflow
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               formid : integer,
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created Workflow.
    */
    public function create($data)
    {
        $appId = $this->params()->fromRoute()['appId'];
        try {
            $count = $this->workflowService->saveWorkflow($appId, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }
    
    /**
    * GET List Workflows API
    * @api
    * @link /Workflow
    * @method GET
    * @return array Returns a JSON Response list of Workflows based on Form id.
    */
    public function getList()
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        $result = $this->workflowService->getWorkflows($appUuid);
        return $this->getSuccessResponseWithData($result['data']);
    }
    /**
    * Update Workflow API
    * @api
    * @link /Workflow[/:WorkflowId]
    * @method PUT
    * @param array $id ID of Workflow to update
    * @param array $data
    * @return array Returns a JSON Response with Status Code and Created Workflow.
    */
    public function update($workflowUuid, $data)
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        try {
            $count = $this->workflowService->updateWorkflow($appUuid,$workflowUuid, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $workflowUuid", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }
    /**
    * Delete Workflow API
    * @api
    * @link /Workflow[/:WorkflowId]
    * @method DELETE
    * @param $id ID of Workflow to Delete
    * @return array success|failure response
    */
    public function delete($id)
    {
        $appId = $this->params()->fromRoute()['appId'];
        $response = $this->workflowService->deleteWorkflow($appId, $id);
        if ($response == 0) {
            return $this->getErrorResponse("Workflow not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    /**
    * GET Workflow API
    * @api
    * @link /workflow[/:workflowId]
    * @method GET
    * @param $id ID of Workflow
    * @return array $data
    * @return array Returns a JSON Response with Status Code and Created Workflow.
    */
    public function get($id)
    {
        $appId = $this->params()->fromRoute()['appId'];
        $result = $this->workflowService->getWorkflow($id, $appId);
        if ($result == 0) {
            return $this->getErrorResponse("Workflow not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * GET START FORM
     * @api
     * @link /app/:appId/workflow/workflowId/startform
     * @method POST
     * @param null </br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
     */
    public function startformAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        try {
            $response = $this->workflowService->getStartForm($params['appId'], $params['workflowId']);
            return $this->getSuccessResponseWithData($response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(),$e);
            return $this->getErrorResponse($e->getMessage(), 404, $params);
        }
    }
}
