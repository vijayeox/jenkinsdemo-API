<?php

namespace App\Controller;

use App\Model\App;
use App\Model\AppTable;
use App\Service\AppService;
use Oxzion\ValidationException;
use Oxzion\ServiceException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\AppDelegate\AppDelegateService;
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
    public function __construct(AppDelegateService $appDelegateService,UserService $userService)
    {
        $this->appDelegateService = $appDelegateService;
        $this->userService = $userService;
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
        $this->appDelegateService->updateOrganizationContext($data);
        $response = $this->appDelegateService->execute($appId, $delegate, $data);
        if ($response == 1) {
            return $this->getErrorResponse("Delegate not found", 404);
        }else if($response == 2){
            return $this->getErrorResponse("Error while executing the delegate", 400);
        }
        return $this->getSuccessResponseWithData($response, 200);
    }
    
    public function userlistAction()
    {
        $params = array_merge($this->extractPostData(), $this->params()->fromRoute());
        try {
            if(isset($params['appId']) && isset($params['orgId'])){
                $users = $this->userService->getUsersList($params['appId'],$params  );
            } else {
                $users = array();
            }
        }catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }catch (ServiceException $e) {
            $response = ['errors' => $e->getMessageCode()];
            return $this->getErrorResponse("App not found Errors",403, $response);
        }
        return $this->getSuccessResponseWithData($users);
    }
}
