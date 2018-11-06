<?php
namespace Form\Controller;

use Zend\Log\Logger;
use Form\Model\Field;
use Form\Model\FieldTable;
use Form\Service\FieldService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;

class FieldController extends AbstractApiController
{
    private $fieldService;

	public function __construct(FieldTable $table, FieldService $fieldService, Logger $log, AdapterInterface $dbAdapter) {
		parent::__construct($table, $log, __CLASS__, Field::class);
		$this->setIdentifierName('id');
		$this->fieldService = $fieldService;
	}

	/**
    *   $data should be in the following JSON format
    *   {
    *   }
    *
    *
    */
    public function create($data){
        $formId = $this->params()->fromRoute()['formId'];
        try{
            $count = $this->fieldService->createField($formId,$data);
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
        $formId = $this->params()->fromRoute()['formId'];
        $result = $this->fieldService->getFields($formId);
        return $this->getSuccessResponseWithData($result);
    }
    public function update($id, $data){
        try{
            $count = $this->fieldService->updateField($id,$data);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data,200);
    }
    public function delete($id){
        $formId = $this->params()->fromRoute()['formId'];
        $response = $this->fieldService->deleteField($formId,$id);
        if($response == 0){
            return $this->getErrorResponse("Field not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    public function get($id){
        $formId = $this->params()->fromRoute()['formId'];
        $result = $this->fieldService->getField($formId,$id);
        if($result == 0){
            return $this->getErrorResponse("Field not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }
}
