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

    private $appService;
    public function __construct(AppTable $table, AppService $appService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, App::class);
        $this->setIdentifierName('appId');
        $this->appService = $appService;
    }

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

    public function getList() {
        $result = $this->appService->getApps($this->getEvent());
        return $this->getSuccessResponseWithData($result);
    }

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

    public function delete($id) {
        $response = $this->appService->deleteApp($id);
        if($response == 0){
            return $this->getErrorResponse("Announcement not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

}
