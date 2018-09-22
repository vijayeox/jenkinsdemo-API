<?php

namespace Alert\Controller;

use Zend\Log\Logger;
use Alert\Model\AlertTable;
use Alert\Model\Alert;
use Oxzion\Controller\AbstractApiController;

class AlertAcceptController extends AbstractApiController {

    private $alertService;
    public function __construct(AlertTable $table, AlertService $alertService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, Alert::class);
        $this->setIdentifierName('alertId');
        $this->alertService = $alertService;
    }

    public function acceptAction() {
        try{
            $count = $this->alertService->acceptAlert();
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        return $this->getSuccessResponseWithData($data,201);
    }
    public function declineAction() {
        try{
            $count = $this->alertService->declineAlert();
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        return $this->getSuccessResponseWithData($data,201);
    }

}
