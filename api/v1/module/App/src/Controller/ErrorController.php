<?php

namespace App\Controller;

use App\Service\ImportService;
use Oxzion\Service\ErrorLogService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ServiceException;
use Exception;

class ErrorController extends AbstractApiController
{
    /**
     * @var ImportService Instance of ImportService Service
     */
    private $errorService;

    /**
     * @ignore __construct
     */
    public function __construct(ErrorLogService $errorService, AdapterInterface $dbAdapter)
    {
        parent::__construct(null, null);
        $this->setIdentifierName('appId');
        $this->errorService = $errorService;
    }

    /*
     * POST Log the Error
     * @api
     * @link /app/appId/error
     * @method POST
     * @return Status mesassge based on success and failure
     * <code>status : "success|error",
     *       data :  {
     * string: app_id
     * }
     * </code>
     */
    public function logAction()
    {
        $data = array_merge($this->extractPostData(),$this->params()->fromRoute());
        $appUuid = $this->params()->fromRoute()['appId'];
        if(isset($data['type'])){
            $type = $data['type'];
        } else {
            $type = 'unspecified';
        }
        if(isset($data['errorTrace'])){
            $errorTrace = $data['errorTrace'];
        } else {
            $errorTrace = 'unspecified';
        }
        if(isset($data['payload'])){
            $payload = $data['payload'];
        } else {
            $payload = 'unspecified';
        }
        if(isset($data['params'])){
            $params = $data['params'];
        } else {
            $params = 'unspecified';
        }
        try {
            $data = $this->errorService->saveError($type,$errorTrace,$payload,$params);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }
}
