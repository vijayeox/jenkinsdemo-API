<?php

namespace ErrorLog\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\ErrorLogService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class RetryController extends AbstractApiController
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

    public function retryAction() {
        $errorId = $this->params()->fromRoute()['errorId'];
        $result = $this->errorService->retryError($errorId,$this->getRequest());
        return $this->getSuccessResponseDataWithPagination($result['data'],$result['total']);
    }
}
