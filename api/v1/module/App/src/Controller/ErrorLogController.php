<?php
namespace App\Controller;

/**
 * ErrorLog Api
 */
use Oxzion\Controller\AbstractApiController;
use Oxzion\Encryption\Crypto;
use Oxzion\ServiceException;
use Oxzion\ValidationException;
use Oxzion\EntityNotFoundException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\ErrorLogService;

class ErrorLogController extends AbstractApiController
{
    private $errorLogService;
    /**
     * @ignore __construct
     */
    public function __construct(ErrorLogService $errorLogService)
    {
        $this->setIdentifierName('id');
        $this->errorLogService = $errorLogService;
    }
    public function create($data)
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        if(isset($data['error_type']) || isset($data['type'])){
            try {
                $count = $this->errorLogService->saveError(isset($data['error_type'])?$data['error_type']:$data['type'],isset($data['error_trace'])?$data['error_trace']:null,isset($data['payload'])?$data['payload']:null,isset($data['params'])?$data['params']:null,$appUuid);
            } catch (ValidationException $e) {
                $response = ['data' => $data, 'errors' => $e->getErrors()];
                return $this->getErrorResponse("Validation Errors", 404, $response);
            }
            if ($count == 0) {
                return $this->getFailureResponse("Failed to create a new entity", $data);
            }
        } else {
            return $this->getFailureResponse("Failed to log error", $data);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }
    public function getList()
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        $filterParams = $this->params()->fromQuery();
        $result = $this->errorLogService->getErrorList($filterParams,$appUuid);
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
    }
    public function update($id, $data)
    {
        return $this->getInvalidMethod();
    }
    public function delete($id)
    {
        return $this->getInvalidMethod();
    }
    public function get($id)
    {
        return $this->getInvalidMethod();
    }
    public function retryAction() {
        $errorId = $this->params()->fromRoute()['errorId'];
        $appUuid = $this->params()->fromRoute()['appId'];
        $result = $this->errorLogService->retryError($errorId,$this->getRequest(),$appUuid);
        return $this->getSuccessResponseDataWithPagination($result['data'],$result['total']);
    }
}
