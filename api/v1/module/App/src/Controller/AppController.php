<?php

namespace App\Controller;

use App\Model\App;
use App\Model\AppTable;
use App\Service\AppService;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ValidationException;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ServiceException;
use Exception;
use Oxzion\Service\WorkflowService;

class AppController extends AbstractApiController
{
    /**
     * @var AppService Instance of AppService Service
     */
    private $appService;

    /**
     * @ignore __construct
     */
    public function __construct(AppTable $table, AppService $appService, AdapterInterface $dbAdapter,WorkflowService $workflowService)
    {
        parent::__construct($table, App::class);
        $this->setIdentifierName('appId');
        $this->appService = $appService;
        $this->workflowService = $workflowService;
    }
    public function setParams($params)
    {
        $this->params = $params;
    }
    /**
     * Create App API
     * @api
     * @link /app
     * @method POST
     * @param array $data Array of elements as shown</br>
     * <code> name : string,
     * description : string,
     * </code>
     * @return array Returns a JSON Response with Status Code and Created App.</br>
     * <code> status : "success|error",
     *        data : {
     * int id,
     * string name,
     * int uuid,
     * string description,
     * string type,
     * string logo,
     * string category,
     * datetime date_created,
     * datetime date_modified,
     * int created_by,
     * int modified_by,
     * int isdeleted
     * }
     * </code>
     */
    public function create($data)
    {
        try {
            $count = $this->appService->createApp($data);
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
     * GET List App API
     * @api
     * @link /app
     * @method GET
     * @return array $dataget list of Apps by User
     * <code>status : "success|error",
     *       data :  {
     * string name,
     * int uuid,
     * string description,
     * string type,
     * string logo,
     * string category,
     * datetime date_created,
     * datetime date_modified,
     * int created_by,
     * int modified_by,
     * int isdeleted,
     * int org_id,
     * string start_options
     * }
     * </code>
     */
    public function getList()
    {
        $result = $this->appService->getApps();
        if ($result == 0 || empty($result)) {
            return $this->getErrorResponse("No App found", 404);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
     * Update App API
     * @api
     * @link /app[/:appId]
     * @method PUT
     * @param array $id ID of App to update
     * @param array $data
     * <code> status : "success|error",
     *       "data": {
     * int id,
     * string name,
     * int uuid,
     * string description,
     * string type,
     * string logo,
     * string category,
     * datetime date_created,
     * datetime date_modified,
     * int created_by,
     * int modified_by,
     * int isdeleted
     * }
     * </code>
     * @return array Returns a JSON Response with Status Code and Created App.
     */
    public function update($id, $data)
    {
        try {
            $count = $this->appService->updateApp($id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("App not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
     * Delete App API
     * @api
     * @link /app[/:appId]
     * @method DELETE
     * @param $id ID of App to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        $response = $this->appService->deleteApp($id);
        if ($response == 0) {
            return $this->getErrorResponse("App not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

    /**
     * GET App API
     * @api
     * @link /app/appid
     * @method GET
     * @return array $dataget of Apps by User
     * <code>status : "success|error",
     *       data :  {
     * string name,
     * int uuid,
     * string description,
     * string type,
     * string logo,
     * string category,
     * datetime date_created,
     * datetime date_modified,
     * int created_by,
     * int modified_by,
     * int isdeleted,
     * int org_id,
     * string start_options
     * }
     * </code>
     */
    public function get($id)
    {
        $response = $this->appService->getApp($id);
        if ($response == 0 || empty($response)) {
            return $this->getErrorResponse("App not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($response);
    }

    /**
     * GET App API
     * @api
     * @link /app/a
     * @method GET
     * @return array of Apps
     */
    public function applistAction()
    {
        $filterParams = $this->params()->fromQuery(); // empty method call
        $response = $this->appService->getAppList($filterParams);
        if ($response == 0 || empty($response)) {
            return $this->getErrorResponse("No Apps to display", 404);
        }
        return $this->getSuccessResponseDataWithPagination($response['data'], $response['total']);
    }

    public function appInstallAction($data)
    {
        $data = $this->extractPostData();
        try {
            $count = $this->appService->installAppForOrg($data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }
    

    public function assignmentsAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        $filterParams = $this->params()->fromQuery();
        try {
            $assignments = $this->workflowService->getAssignments($params['appId'],$filterParams);
        }catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        catch(AccessDeniedException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse($e->getMessage(),403, $response);
        }
        return $this->getSuccessResponseDataWithPagination($assignments['data'], $assignments['total']);
    }

    /**
     * Deploy App API using YAML File
     * @api
     * @link /app/appdeployyml
     * @method GET
     * @param null </br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
    */
    public function deployAppAction() {
        $params = $this->extractPostData();
        if(isset($params['path']))
        {
            try {
                $path = $params['path'];
                $path .= substr($path, -1) == '/' ? '' : '/';
                $this->appService->deployApp($path);
                return $this->getSuccessResponse(200);
            }
            catch (ValidationException $e) {
                // print_r($e->getMessage());
                $this->log->error($e->getMessage(), $e);
                // print_r($e->getTraceAsString());exit;
                $response = ['data' => $data, 'errors' => $e->getErrors()];
                return $this->getErrorResponse("Validation Errors", 406, $response);
            }
            catch (ServiceException $e){
                // print_r($e->getMessage());
                $this->log->error($e->getMessage(), $e);
                // print_r($e->getTraceAsString());exit;
                return $this->getErrorResponse($e->getMessage(),406);
            }catch(Exception $e){
                // print_r($e->getMessage());
                $this->log->error($e->getMessage(), $e);
                // print_r($e->getTraceAsString());exit;
                return $this->getErrorResponse($e->getMessage(),500);
            }
        }else{
            // print_r($e->getMessage());
            $this->log->error("Path not provided");
            // print_r($e->getTraceAsString());exit;
            return $this->getErrorResponse("Invalid parameters",400);
        }
    }
}
