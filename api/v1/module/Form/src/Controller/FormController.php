<?php
namespace Form\Controller;

use Zend\Log\Logger;
use Form\Model\Form;
use Form\Model\FormTable;
use Form\Service\FormService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;

class FormController extends AbstractApiController
{
    private $formService;

	public function __construct(FormTable $table, FormService $formService, Logger $log, AdapterInterface $dbAdapter) {
		parent::__construct($table, $log, __CLASS__, Form::class);
		$this->setIdentifierName('formId');
		$this->formService = $formService;
	}
	/**
    *   $data should be in the following JSON format
    *   {

    *   }
    *
    *
    */
    public function create($data){
        try{
            $count = $this->formService->createForm($data);
        } catch (ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data,201);
    }
    
    public function getList() {
        $result = $this->formService->getForms();
        return $this->getSuccessResponseWithData($result);
    }
    public function update($id, $data){
        try{
            $count = $this->formService->updateForm($id,$data);
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
        $response = $this->formService->deleteForm($id);
        if($response == 0){
            return $this->getErrorResponse("Form not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    public function get($id){
        $result = $this->formService->getForm($id);
        if($result == 0){
            return $this->getErrorResponse("Form not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }
}