<?php

namespace App\Controller;

use Zend\Log\Logger;
use App\Model\AppTable;
use App\Model\App;
use App\Service\AppService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Bos\ValidationException;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use PHPUnit\DbUnit\DataSet\XmlDataSet;

class AppController extends AbstractApiController {
    /**
     * @var AppService Instance of AppService Service
     */
    private $appService;
    /**
     * @ignore __construct
     */
    public function __construct(AppTable $table, AppService $appService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, App::class);
        $this->setIdentifierName('appId');
        $this->appService = $appService;
    }
    /**
     * Create App API
     * @api
     * @link /app/installAppForOrg
     * @method POST
     * @param array $data Array of elements as shown</br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code and Created App.</br>
     * <code> status : "success|error",
     *        data : array Created App Object
     * </code>
     */
    public function installAppForOrgAction() {
        $data = $this->params()->fromPost();
        try {
            $count = $this->appService->installAppForOrg($data);
        } catch(ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new App", $data);
        }
        return $this->getSuccessResponseWithData($data,201);
    }

    /**
     * GET List App API
     * @api
     * @link /app
     * @method GET
     * @return array $data get list of Apps by User
     * <code>
     * {
     * }
     * </code>
     */
    public function getList() {
        $result = $this->appService->getApps();
        return $this->getSuccessResponseWithData($result);
    }
    /**
     * Update App API
     * @api
     * @link /app[/:appId]
     * @method PUT
     * @param array $id ID of App to update
     * @param array $data
     * <code>
     * {
     *
     * }
     * </code>
     * @return array Returns a JSON Response with Status Code and Created App.
     */
    public function update($id, $data) {
        try{
            $count = $this->appService->updateApp($id,$data);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data,200);
    }

    /**
     * Delete App API
     * @api
     * @link /app[/:appId]
     * @method DELETE
     * @param $id ID of App to Delete
     * @return array success|failure response
     */
    public function delete($id) {
        $response = $this->appService->deleteApp($id);
        if($response == 0){
            return $this->getErrorResponse("App not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

    /**
     * Upload the app from the UI and extracting the zip file in a folder that will start the installation of app.
     * @api
     * @link /app/appdeployyml
     * @method GET
     * @param null</br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
     */
    public function appUploadAction() {
        $file_name = $_FILES["file"]["name"];
        $destinationFolder = $this->appService->getAppUploadFolder() . "/uploads/";
        $target_file = $destinationFolder . $file_name;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            return $this->getSuccessResponse();
        } else {
            return $this->getErrorResponse("Files cannot be uploaded");
        }
    }

    /**
     * Deploy App API using XML File
     * @api
     * @link /app/appdeployxml
     * @method GET
     * @param null</br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
     */
    public function getDataFromDeploymentDescriptorUsingXMLAction() {
        try {
            $appUploadedZipFile = $this->appService->getAppUploadFolder() . "/uploads/App.zip";
            $destinationFolder = $this->appService->getAppUploadFolder() . "/temp";
            $fileExtract = $this->appService->extractZipFilefromAppUploader($appUploadedZipFile, $destinationFolder);
            $fileName = file_get_contents($this->appService->getAppUploadFolder() . "/temp/App/web.xml");
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
    }

    /**
     * Deploy App API using YAML File
     * @api
     * @link /app/appdeployyml
     * @method GET
     * @param null</br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
     */
    public function getDataFromDeploymentDescriptorUsingYMLAction() {
        try {
            $appUploadedZipFile = $this->appService->getAppUploadFolder() . "/uploads/App.zip";
            $destinationFolder = $this->appService->getAppUploadFolder() . "/temp";
            $fileExtract = $this->appService->extractZipFilefromAppUploader($appUploadedZipFile, $destinationFolder);
            $fileName = file_get_contents($this->appService->getAppUploadFolder() . "/temp/App/web.yml");
        } catch (Exception $e) {
            return $this->getErrorResponse("The files could not be extracted!");
        }
        $xmlArray = $this->appService->ymlToArrayParser($fileName);
        $count = $this->appService->getFormInsertFormat($xmlArray['config']);
        if ($count === 1) {
            return $this->getSuccessResponse();
        } else {
            return $this->getErrorResponse("Form could not be created, please check your deployment descriptor");
        }
    }
}