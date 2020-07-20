<?php

namespace App\Controller;

use App\Model\App;
use App\Service\AppService;
use Exception;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\UserService;
use Oxzion\ValidationException;

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
            $this->appDelegateService->updateOrganizationContext($data);
            $response = $this->appDelegateService->execute($appId, $delegate, $data);
            if ($response == 1) {
                return $this->getErrorResponse("Delegate not found", 404);
            } elseif ($response == 2) {
                return $this->getErrorResponse("Error while executing the delegate", 400);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 400);
        }
        $this->log->info(__CLASS__ . "-> \n End of Delegate");
        return $this->getSuccessResponseWithData($response, 200);
    }

    /**
     * User List API
     * @api
     * @link /app/:appId/org/:orgId/userlist
     * @method GET
     * @param array $data - List of all the users for the organization
     */
    public function userlistAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        try {
            if (isset($params['appId']) && isset($params['orgId'])) {
                $users = $this->userService->getUsersList($params['appId'], $params);
            } else {
                $users = array();
            }
        } catch (ValidationException $e) {
            $this->log->error($e->getMessage(), $e);
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 400);
        }
        return $this->getSuccessResponseWithData($users);
    }
}
