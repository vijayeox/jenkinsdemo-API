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
        return $this->getInvalidMethod();
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
