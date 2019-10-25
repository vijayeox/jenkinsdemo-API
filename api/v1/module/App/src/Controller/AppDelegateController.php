<?php

namespace App\Controller;

use App\Model\App;
use App\Model\AppTable;
use App\Service\AppService;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\AppDelegate\AppDelegateService;

class AppDelegateController extends AbstractApiControllerHelper
{
    /**
     * @var AppService Instance of AppService Service
     */
    private $appDelegateService;
    
    /**
     * @ignore __construct
     */
    public function __construct(AppDelegateService $appDelegateService)
    {
        $this->appDelegateService = $appDelegateService;
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
    
}
