<?php

namespace App\Controller;

use App\Model\App;
use App\Model\AppTable;
use App\Service\AppService;
use Exception;
use Oxzion\AccessDeniedException;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\WorkflowService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\AppDelegate\AppDelegateService;

class AppController extends AbstractApiController
{
    /**
     * @var AppService Instance of AppService Service
     */
    private $appService;

    /**
     * @ignore __construct
     */
    public function __construct(AppTable $table, AppService $appService, AdapterInterface $dbAdapter, WorkflowService $workflowService,AppDelegateService $appDelegateService)
    {
        parent::__construct($table, App::class);
        $this->setIdentifierName('appId');
        $this->appService = $appService;
        $this->workflowService = $workflowService;
        $this->appDelegateService = $appDelegateService;
        $this->log = $this->getLogger();
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
        $this->log->info(__CLASS__ . "-> Create App - " . print_r($data, true));
        try {
            $returnData = $this->appService->createApp($data);
            return $this->getSuccessResponseWithData($returnData, 201);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
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
        $this->log->info(__CLASS__ . "-> Get app list.");
        try {
            $result = $this->appService->getApps();
            return $this->getSuccessResponseWithData($result);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
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
    public function update($uuid, $data)
    {
        $this->log->info(__CLASS__ . "-> Update App - ${uuid}, " . print_r($data, true));
        try {
            $returnData = $this->appService->updateApp($uuid, $data);
            return $this->getSuccessResponseWithData($returnData, 200);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * Delete App API
     * @api
     * @link /app[/:appId]
     * @method DELETE
     * @param $uuid UUID of App to Delete
     * @return array success|failure response
     */
    public function delete($uuid)
    {
        $this->log->info(__CLASS__ . "-> Delete App for ID ${uuid}.");
        try {
            $this->appService->deleteApp($uuid);
            return $this->getSuccessResponse();
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
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
    public function get($uuid)
    {
        $this->log->info(__CLASS__ . "-> Get App for ID- ${uuid}.");
        try {
            $response = $this->appService->getApp($uuid);
            return $this->getSuccessResponseWithData($response);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
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
        $this->log->info(__CLASS__ . "-> Get App List - " . print_r($filterParams, true));
        try {
            $response = $this->appService->getAppList($filterParams);
            return $this->getSuccessResponseDataWithPagination($response['data'], $response['total']);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

/*-------------------------------------------------------------------------------------------------------------*/
/* Removed because AppService->installAppForOrg is deprecated/removed. */
/*-------------------------------------------------------------------------------------------------------------*/
    /**
     * POST App Install API
     * @api
     * @link /app/:appId/appinstall
     * @method POST
     * ! Deprecated - Does not look like this api is being used any more, the method that calls the service isnt available.
     * ? Need to check if this can be removed
     * @return array of Apps
     */
/*
    public function appInstallAction($data)
    {
        $data = $this->extractPostData();
        try {
            $count = $this->appService->installAppForOrg($data);
            return $this->getSuccessResponseWithData($data, 201);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }
*/

    /**
     * POST Assignment API
     * @api
     * @link /app/:appId/assignments
     * @method POST
     * @return array of Apps
     */
    public function assignmentsAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        $filterParams = $this->params()->fromQuery();
        try {
            $assignments = $this->workflowService->getAssignments($params['appId'], $filterParams);
            return $this->getSuccessResponseDataWithPagination($assignments['data'], $assignments['total']);
        }
        catch (AccessDeniedException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse($e->getMessage(), 403, $response);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * Deploy App API using YAML File
     * @api
     * @link /app/appdeployyml
     * @method GET
     * @param  $path - Enter the path of the Application to deploy.
     * @param  $parameters(optional) - Enter the parameters option in a CSV 
     * format to deploy and these options can be specified in any order. 
     * It is recommended that if you are deploying for the first time,
     * then specify the 'initialize' option first and then specify other options. 
     * Parameters options are : 
     * initialize, entity, workflow, form, menu, page, job
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
     */
    public function deployAppAction()
    {
        $params = $this->extractPostData();
        $this->log->info(__CLASS__ . "-> Deploy App - " . print_r($params, true));
        if (!isset($params['path'])) {
            $this->log->error("Path not provided");
            return $this->getErrorResponse("Invalid parameters", 406);
        }

        try {
            $path = $params['path'];
            $path .= substr($path, -1) == '/' ? '' : '/';
            if(isset($params['parameters']) && !empty($params['parameters'])){
                $params['parameters'] = strtolower($params['parameters']);
                $params['parameters'] = preg_replace("/[^a-zA-Z\,]/", "", $params['parameters']);
                $params['parameters'] = rtrim($params['parameters'],",");
                $params['parameters'] = ltrim($params['parameters'],",");
                if(strpos($params['parameters'], ',') !== false){
                    $params = explode(",",$params['parameters']);
                }else{
                    $params = array($params['parameters']);
                }                    
            }
            else{
                $params = null;
            }
            $appData = $this->appService->deployApp($path, $params);
            return $this->getSuccessResponseWithData($appData);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * Deploy App API for AppBuilder. AppBuilder creates the application in <EOX_APP_SOURCE_DIR> on
     * the server and assigns a UUID for the application in OX_APP table in database. This action 
     * uses the UUID of the application for deployment.
     *
     * @api
     * @method POST.
     * @param  $appId - UUID application id.
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
     */
    public function deployApplicationAction()
    {
        $routeParams = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . '-> Deploy Application - ' . $routeParams['appId'], true);
        if (!isset($routeParams['appId'])) {
            $this->log->error('Application ID not provided.');
            return $this->getErrorResponse('Invalid parameters', 406);
        }

        try {
            $appData = $this->appService->deployApplication($routeParams['appId']);
            return $this->getSuccessResponseWithData($appData);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    public function delegateCommandAction()
    {
        $routeParams = $this->params()->fromRoute();
        $appId = $routeParams['appId'];
        $delegate = $routeParams['delegate'];
        $data = $this->extractPostData();
        $data = array_merge($data, $this->params()->fromQuery());
        $this->log->info(__CLASS__ . "-> Execute Delegate Start - " . print_r($data, true));
        try {
            $response = $this->appDelegateService->execute($appId, $delegate, $data);
            if ($response == 1) {
                return $this->getErrorResponse("Delegate not found", 404);
            } elseif ($response == 2) {
                return $this->getErrorResponse("Error while executing the delegate", 400);
            }
            return $this->getSuccessResponseWithData($response, 200);
        }
        catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }
}

