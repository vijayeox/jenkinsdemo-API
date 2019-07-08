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
use Zend\Log\Logger;

class AppController extends AbstractApiController
{
    /**
     * @var AppService Instance of AppService Service
     */
    private $appService;

    /**
     * @ignore __construct
     */
    public function __construct(AppTable $table, AppService $appService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __CLASS__, App::class);
        $this->setIdentifierName('appId');
        $this->appService = $appService;
    }
    public function setParams($params){
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
        $data = $this->params()->fromPost();
        try {
            $count = $this->appService->deployAppForOrg($data);
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
     * Upload the app from the UI and extracting the zip file in a folder that will start the installation of app.
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
    public function appUploadAction()
    {
        $file_name = $_FILES["file"]["name"];
        $destinationFolder = $this->appService->getAppUploadFolder() . "/uploads/";
        $target_file = $destinationFolder . $file_name;
        try {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $this->appService->getDataFromDeploymentDescriptorUsingYML($this->appService->getAppUploadFolder());
                return $this->getSuccessResponse();
            } else {
                return $this->getErrorResponse("Files cannot be uploaded");
            }
        } catch (Exception $e) {
            return $this->getErrorResponse("Files cannot be uploaded!");
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
        $response = $this->appService->getAppList($filterParams);
        if ($response == 0 || empty($response)) {
            return $this->getErrorResponse("No Apps to display", 404);
        }
        return $this->getSuccessResponseDataWithPagination($response['data'],$response['total']);
    }

    public function appInstallAction($data)
    {
        $data = $this->params()->fromPost();
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
    /**
     * Upload the app from the UI and extracting the zip file in a folder that will start the installation of app.
     * @api
     * @link /app/:appId/deployworkflow
     * @method POST
     * @param null </br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
     */
    public function workflowDeployAction()
    {
        $params = array_merge($this->params()->fromPost(),$this->params()->fromRoute());
        $files = $_FILES['files'];
        try {
            if ($files&&isset($params['name'])) {
                $response = $this->appService->deployWorkflow($params['appId'],$params,$files);
                if($response == 1){
                    return $this->getErrorResponse("Error Parsing BPMN");
                }
                if($response == 2){
                    return $this->getErrorResponse("More Than 1 Process Found in BPMN Please Define only one Process per BPMN");
                }
                return $this->getSuccessResponse($response);
             } else {
                return $this->getErrorResponse("Files cannot be uploaded");
            }
        } catch (Exception $e) {
            return $this->getErrorResponse("Files cannot be uploaded!");
        }
    }

    public function assignmentsAction(){
        $params = array_merge($this->params()->fromPost(),$this->params()->fromRoute());
        $assignments = $this->appService->getAssignments($params['appId']);
        return $this->getSuccessResponseWithData($assignments);
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
    /*    public function getDataFromDeploymentDescriptorUsingYML($appFolder) {
            $appUploadFolder = $appFolder;
            try {
                $appUploadedZipFile = $appUploadFolder . "/uploads/App.zip";
                $destinationFolder = $appUploadFolder . "/temp";
                $this->appService->extractZipFilefromAppUploader($appUploadedZipFile, $destinationFolder);
                $fileName = file_get_contents($appUploadFolder . "/temp/App/web.yml");
            } catch (Exception $e) {
                return $this->getErrorResponse("The files could not be extracted!");
            }
            $ymlArray = $this->appService->ymlToArrayParser($fileName);
            //Code to insert the details of the app to the app table. Returns 1 or 0 for success or failure
            $app = $this->appService->insertAppDetail($ymlArray['config']);
            if($app === 0) {
                return $this->getErrorResponse("App could not be installed, please check your deployment descriptor and try again!");
            }
            //Code to add the default privilege to the app installed.
            $appPrivileges = $this->appService->applyAppPrivilege($ymlArray['config'], $app);
            if($appPrivileges === 0){
                return $this->getErrorResponse("App Privileges could not be set, please check your application and try again!");
            }
            $count = $this->appService->getFormInsertFormat($ymlArray['config']);
            if ($count === 1) {
                return $this->getSuccessResponse();
            } else {
                return $this->getErrorResponse("Form could not be created, please check your deployment descriptor and try again!");
            }
        }*/

    /**
     * Deploy App API using XML File
     * @api
     * @link /app/appdeployxml
     * @method GET
     * @param null </br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
     */
    /*    public function getDataFromDeploymentDescriptorUsingXMLAction($appFolder) {
            try {
                $appUploadedZipFile = $appFolder . "/uploads/App.zip";
                $destinationFolder = $appFolder . "/temp";
                $fileExtract = $this->appService->extractZipFilefromAppUploader($appUploadedZipFile, $destinationFolder);
                $fileName = file_get_contents($appFolder . "/temp/App/web.xml");
            } catch (Exception $e) {
                return $this->getErrorResponse("The files could not be extracted!");
            }
            $xmlArray = $this->appService->xmlToArrayParser($fileName);
            $count = $this->appService->getFormInsertFormat($xmlArray);
            if ($count === 1) {
                return $this->getSuccessResponse();
            } else {
                return $this->getErrorResponse("Form could not be created, please check your deployment descriptor");
            }
        }*/

}