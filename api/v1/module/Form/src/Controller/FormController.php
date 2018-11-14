<?php
namespace Form\Controller;
/**
* Form Api
*/
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
    /**
    * @ignore __construct
    */
	public function __construct(FormTable $table, FormService $formService, Logger $log, AdapterInterface $dbAdapter) {
		parent::__construct($table, $log, __CLASS__, Form::class);
		$this->setIdentifierName('formId');
		$this->formService = $formService;
	}
	/**
    * Create Form API
    * @api
    * @link /form
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               Fields from Form
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created Form.
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
    
    /**
    * GET List Forms API
    * @api
    * @link /form
    * @method GET
    * @return array Returns a JSON Response list of Forms based on Access.
    */
    public function getList() {
        $result = $this->formService->getForms();
        return $this->getSuccessResponseWithData($result);
    }
    /**
    * Update Form API
    * @api
    * @link /form[/:formId]
    * @method PUT
    * @param array $id ID of Form to update 
    * @param array $data 
    * @return array Returns a JSON Response with Status Code and Created Form.
    */
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
    /**
    * Delete Form API
    * @api
    * @link /form[/:formId]
    * @method DELETE
    * @param $id ID of Form to Delete
    * @return array success|failure response
    */
    public function delete($id){
        $response = $this->formService->deleteForm($id);
        if($response == 0){
            return $this->getErrorResponse("Form not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    /**
    * GET Form API
    * @api
    * @link /form[/:formId]
    * @method GET
    * @param $id ID of Form
    * @return array $data 
    * @return array Returns a JSON Response with Status Code and Created Form.
    */
    public function get($id){
        $result = $this->formService->getForm($id);
        if($result == 0){
            return $this->getErrorResponse("Form not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }
}