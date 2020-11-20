<?php

namespace App\Controller;

use App\Model\App;
use App\Service\AppService;
use Exception;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\UserService;

class AppDelegateController extends AbstractApiControllerHelper
{
    /**
     * @var AppService Instance of AppService Service
     */
    private $appDelegateService;
    private $userService;

    /**
     * @ignore __construct
     */
    public function __construct(AppDelegateService $appDelegateService, UserService $userService)
    {
        $this->appDelegateService = $appDelegateService;
        $this->userService = $userService;
        $this->log = $this->getLogger();
    }
    /**
     * App Register API
     * @api
     * @link /app/register
     * @method POST
     * @param array $data
     */
    public function delegateAction()
    {
        $data = $this->extractPostData();
        $appId = $this->params()->fromRoute()['appId'];
        $delegate = $this->params()->fromRoute()['delegate'];
        $data = array_merge($data, $this->params()->fromQuery());
        if (isset($_FILES['file'])) {
            $data['_FILES'] = $_FILES['file'];
        }
        $this->log->info(__CLASS__ . "-> \n Execute Delegate Start - " . print_r($data, true));
        try {
            $this->appDelegateService->updateAccountContext($data);
            $response = $this->appDelegateService->execute($appId, $delegate, $data);
            $this->log->info(__CLASS__ . "-> \n End of Delegate");
            return $this->getSuccessResponseWithData($response, 200);  
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
    }

    /**
     * User List API
     * @api
     * @link /app/:appId/org/:orgId/userlist
     * @method GET
     * @param array $data - List of all the users for the account
     */
    public function userlistAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        try {
            if (isset($params['appId']) && isset($params['accountId'])) {
                $users = $this->userService->getUsersList($params['appId'], $params);
            } else {
                $users = array();
            }
            return $this->getSuccessResponseWithData($users);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        
    }
}
