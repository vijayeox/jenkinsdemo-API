<?php

namespace App\Controller;

use Zend\Log\Logger;
use App\Model\AppTable;
use App\Model\App;
use App\Service\AppService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;

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
}