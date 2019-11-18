<?php

namespace ErrorLog\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\ErrorLogService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class ErrorController extends AbstractApiControllerHelper
{
    /**
    * @var ErrorService Instance of Error Service
    */
    private $errorService;
    /**
    * @ignore __construct
    */
    public function __construct(ErrorLogService $errorService, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('errorId');
        $this->errorService = $errorService;
    }
    /**
    * GET Error API
    * @api
    * @link /error/errorid
    * @method GET
    * @return array $dataget of Errors by User
    * <code>status : "success|error",
    *       data :  {
                    string error,
                    string username,
                    string host,
                    integer isdefault,
                    integer userid,
                    integer id
                    }
    * </code>
    */
    public function get($id)
    {
        $result = $this->errorService->getErrorById($id);
        if ($result == 0||empty($result)) {
            return $this->getErrorResponse("File not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }

    /**
    * Create Error API
    * @api
    * @link /error
    * @method POST
    * @param array $data Array of elements as shown</br>
    * <code> name : string,
             description : string,
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Error.</br>
    * <code> status : "success|error",
    *        data : array Created Error Object
                    string error,
                    string username,
                    string host,
                    integer isdefault,
                    integer userid,
                    integer id
    * </code>
    */
    public function create($data)
    {
        try {
            $data = $this->errorService->saveError($data);
            if ($data == 0) {
                return $this->getFailureResponse("Failed to create a new entity", $data);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }

    public function update($id, $data) {
    	return $this->getInvalidMethod();
    }

    public function delete($id) {
        return $this->getInvalidMethod();
    }
    /**
    * GET List Error API
    * @api
    * @link /error
    * @method GET
    * @return array $dataget list of Errors by User
    * <code>status : "success|error",
    *       data :  {
                    string error,
                    string username,
                    string host,
                    integer isdefault,
                    integer userid,
                    integer id
                    }
    * </code>
    */
    public function getList()
    {
        $filterParams = $this->params()->fromQuery(); // empty method call
        $result = $this->errorService->getErrorList($filterParams);
        return $this->getSuccessResponseDataWithPagination($result['data'],$result['total']);
    }
    public function retryAction() {
        $errorId = $this->params()->fromRoute()['errorId'];
        $result = $this->errorService->retryError($errorId);
        return $this->getSuccessResponseDataWithPagination($result['data'],$result['total']);
    }
}
