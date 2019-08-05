<?php
namespace Workflow\Controller;

/**
* Activity Instance Api
*/
use Zend\Log\Logger;
use Workflow\Model\ServiceTaskInstance;
use Workflow\Service\ServiceTaskService;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;

class ServiceTaskController extends AbstractApiControllerHelper
{
    private $serviceTaskService;
    private $log;
    
    /**
    * @ignore __construct
    */
    public function __construct(ServiceTaskService $serviceTaskService, Logger $log)
    {
        $this->serviceTaskService = $serviceTaskService;
        $this->log = $log;
    }
    /**
    * Activity Instance API
    * @api
    * @method POST
    * @link /execute/servicetask
    * @return array success|failure response
    */

    public function executeAction()
    {
        $data = $this->extractPostData();
        $this->log->info(ServiceTask::class.":Post Data- ". print_r(json_encode($data), true));
        try {
            $response = $this->serviceTaskService->runCommand($this->extractPostData());
            if($response){
                $this->log->info(ServiceTask::class.":Workflow Step Successfully Executed");
                return $this->getSuccessResponse();
            } else {
                return $this->getErrorResponse("Failed to perform Service Task", 200);
            }
        } catch (ValidationException $e) {
            $this->log->info(ServiceTask::class.":Exception while Performing Service Task-".$e->getMessage());
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
    }
}
