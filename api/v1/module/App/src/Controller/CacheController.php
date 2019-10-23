<?php

namespace App\Controller;

use App\Service\ImportService;
use Oxzion\Service\UserCacheService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\ServiceException;
use Exception;

class CacheController extends AbstractApiController
{
    /**
     * @var ImportService Instance of ImportService Service
     */
    private $cacheService;

    /**
     * @ignore __construct
     */
    public function __construct(UserCacheService $cacheService, AdapterInterface $dbAdapter)
    {
        parent::__construct(null, null);
        $this->setIdentifierName('appId');
        $this->cacheService = $cacheService;
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
        $data = array_merge($this->extractPostData(),$this->params()->fromRoute());
        $appUuid = $this->params()->fromRoute()['appId'];
        try {
            $data = $this->cacheService->storeUserCache($appUuid, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }

    public function cacheAction()
    {
        $appId = $this->params()->fromRoute()['appId'];
        $result = $this->cacheService->getCache(null,$appId,AuthContext::get(AuthConstants::USER_ID));
        if($result == 0){
            return $this->getSuccessResponseWithData(array());
        }
        return $this->getSuccessResponseWithData($result);
    }

    public function cacheDeleteAction()
    {
        $appId = $this->params()->fromRoute()['appId'];
        try{
            $result = $this->cacheService->deleteUserCache($appId);
        }
        catch(Exception $e) {
            return $this->getErrorResponse("The cache deletion has failed",400);
        }
        if($result == 0){
            return $this->getSuccessResponse("The cache has been successfully deleted");
        }
    }
}
