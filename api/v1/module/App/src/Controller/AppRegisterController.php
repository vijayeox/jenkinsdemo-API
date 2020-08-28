<?php

namespace App\Controller;

use Oxzion\Model\App;
use Oxzion\Model\AppTable;
use Oxzion\Service\AppService;
use Exception;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

class AppRegisterController extends AbstractApiControllerHelper
{
    /**
     * @var AppService Instance of AppService Service
     */
    private $appService;
    private $log;
    /**
     * @ignore __construct
     */
    public function __construct(AppTable $table, AppService $appService, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('appId');
        $this->log = $this->getLogger();
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
        $this->log->info(__CLASS__ . "-> \n Create App Registry- " . print_r($data, true));
        try {
            $this->appService->registerApps($data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    public function addToAppregistryAction()
    {
        $params = $this->extractPostData();
        $data = array_merge($params, $this->params()->fromRoute());
        $this->log->info(__CLASS__ . "-> \n Create App Registry- " . print_r($data, true) . "Parameters - " . print_r($params, true));
        try {
            $count = $this->appService->addToAppRegistry($data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Duplicate Entry", 409);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }
}
