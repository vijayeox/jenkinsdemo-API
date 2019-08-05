<?php
namespace App\Controller;

/**
* Form Api
*/
use Zend\Log\Logger;
use Oxzion\Model\Form;
use Oxzion\Model\FormTable;
use Oxzion\Service\FormService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;

class FormController extends AbstractApiController
{
    private $formService;
    /**
    * @ignore __construct
    */
    public function __construct(FormTable $table, FormService $formService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __CLASS__, Form::class);
        $this->setIdentifierName('id');
        $this->formService = $formService;
    }
    /**
    * Create Form API
    * @api
    * @link /app/appId/form
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               Fields from Form
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created Form.
    */
    public function create($data)
    {
        $appId = $this->params()->fromRoute()['appId'];
        try {
            $count = $this->formService->createForm($appId, $data);
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
    * GET List Forms API
    * @api
    * @link /app/appId/form
    * @method GET
    * @return array Returns a JSON Response list of Forms based on Access.
    */
    public function getList()
    {
        $appId = $this->params()->fromRoute()['appId'];
        $result = $this->formService->getForms($appId);
        return $this->getSuccessResponseWithData($result['data']);
    }
    /**
    * Update Form API
    * @api
    * @link /app/appId/form[/:id]
    * @method PUT
    * @param array $id ID of Form to update
    * @param array $data
    * @return array Returns a JSON Response with Status Code and Created Form.
    */
    public function update($id, $data)
    {
        $appId = $this->params()->fromRoute()['appId'];
        try {
            $count = $this->formService->updateForm($appId, $id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }
    /**
    * Delete Form API
    * @api
    * @link /app/appId/form[/:id]
    * @method DELETE
    * @param $id ID of Form to Delete
    * @return array success|failure response
    */
    public function delete($id)
    {
        $response = $this->formService->deleteForm($id);
        if ($response == 0) {
            return $this->getErrorResponse("Form not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    /**
    * GET Form API
    * @api
    * @link /app/appId/form[/:id]
    * @method GET
    * @param $id ID of Form
    * @return array $data
    * @return array Returns a JSON Response with Status Code and Created Form.
    */
    public function get($id)
    {
        $result = $this->formService->getForm($id);
        if ($result == 0) {
            return $this->getErrorResponse("Form not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }
}
