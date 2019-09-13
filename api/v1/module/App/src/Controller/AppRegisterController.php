<?php

namespace App\Controller;

use App\Model\App;
use App\Model\AppTable;
use App\Service\AppService;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiControllerHelper;
use Zend\Log\Logger;

class AppRegisterController extends AbstractApiControllerHelper
{
    /**
     * @var AppService Instance of AppService Service
     */
    private $appService;

    /**
     * @ignore __construct
     */
    public function __construct(AppTable $table,AppService $appService, Logger $log, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('appId');
        $this->appService = $appService;
    }
    /**
     * App Register API
     * @api
     * @link /app/register
     * @method POST
     * @param array $data
     */
    public function appregisterAction()
    {   
        $data = $this->extractPostData();
        try {
            $count = $this->appService->registerApps($data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if($count == 0){
          return $this->getErrorResponse("Failed to Register",404);   
        }
        return $this->getSuccessResponseWithData($data, 200);
    }
}