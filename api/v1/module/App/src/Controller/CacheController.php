<?php

namespace App\Controller;

use App\Service\ImportService;
use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ValidationException;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\UserCacheService;
use Zend\Db\Adapter\AdapterInterface;

class CacheController extends AbstractApiController
{
    /**
     * @var ImportService Instance of ImportService Service
     */
    private $userCacheService;

    /**
     * @ignore __construct
     */
    public function __construct(UserCacheService $UserCacheService, AdapterInterface $dbAdapter)
    {
        parent::__construct(null, null);
        $this->setIdentifierName('appId');
        $this->userCacheService = $UserCacheService;
        $this->log = $this->getLogger();
    }

    /*
     * POST Import the CSV fuction
     * @api
     * @link /app/appId/cache
     * @method POST
     * @return Status mesassge based on success and failure
     * <code>status : "success|error",
     *       data :  {
     * String stored_procedure_name
     * int: org_id
     * string: app_id
     * string: app_name
     * }
     * </code>
     */
    /**
     * Create Entity API
     * @api
     * @link /app/appId/cache
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               data : integer,
     *               name : string,
     *               Fields from Entity
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Entity.
     */
    public function storeAction()
    {
        $data = array_merge($this->extractPostData(), $this->params()->fromRoute());
        $appUuid = $this->params()->fromRoute()['appId'];
        $this->log->info(__CLASS__ . "-> \n Store Cache - " . print_r($data, true));
        try {
            $count = $this->userCacheService->storeUserCache($appUuid, $data);
            if ($count == 0) {
                return $this->getErrorResponse("Failed to store cache", 404, $data);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }

    public function cacheAction()
    {
        $appId = $this->params()->fromRoute()['appId'];
        if(isset($this->params()->fromRoute()['cacheId'])){
          $cacheId = $this->params()->fromRoute()['cacheId'];
        } else {
          $cacheId = null;
        }
        $this->log->info(__CLASS__ . "-> \n Get Cache - " . print_r($appId, true));
        $result = $this->userCacheService->getCache($cacheId, $appId, AuthContext::get(AuthConstants::USER_ID));
        if ($result == 0) {
            return $this->getSuccessResponseWithData(array());
        }
        return $this->getSuccessResponseWithData($result);
    }

    public function cacheDeleteAction()
    {
        $appId = $this->params()->fromRoute()['appId'];
        try {
            $result = $this->userCacheService->deleteUserCache($appId);
        } catch (Exception $e) {
            return $this->getErrorResponse("The cache deletion has failed", 400);
        }
        if ($result == 0) {
            return $this->getSuccessResponse("The cache has been successfully deleted");
        }
    }
}
