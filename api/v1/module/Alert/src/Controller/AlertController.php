<?php

namespace Alert\Controller;

use Zend\Log\Logger;
use Alert\Model\AlertTable;
use Alert\Model\Alert;
use Alert\Service\AlertService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;

class AlertController extends AbstractApiController {

    private $alertService;
    public function __construct(AlertTable $table, AlertService $alertService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, Alert::class);
        $this->setIdentifierName('alertId');
        $this->alertService = $alertService;
    }

    public function create($data){
        try{
            $count = $this->alertService->createAlert($data);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data,201);
    }

    public function getList() {
        $result = $this->alertService->getAlerts();
        return $this->getSuccessResponseWithData($result);
    }
    public function update($id, $data){
        try{
            $count = $this->alertService->updateAlert($id,$data);
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
        $response = $this->alertService->deleteAlert($id);
        if($response == 0){
            return $this->getErrorResponse("Announcement not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

    public function acceptAction() {
        $params = $this->params()->fromRoute();
        $count = $this->alertService->updateAlertStatus(1,$params[$this->getIdentifierName()]);
        if($count==0){
            return $this->getErrorResponse("Entity not found for id - ".$params[$this->getIdentifierName()], 404);
        }
        return $this->getSuccessResponse();
    }
    
    public function declineAction() {
        $params = $this->params()->fromRoute();
        $count = $this->alertService->updateAlertStatus(0,$params[$this->getIdentifierName()]);
        if($count==0){
            return $this->getErrorResponse("Entity not found for id - ".$params[$this->getIdentifierName()], 404);
        }
        return $this->getSuccessResponse();
    }

}
