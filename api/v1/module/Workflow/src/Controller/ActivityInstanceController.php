<?php
namespace Workflow\Controller;

/**
* Activity Instance Api
*/
use Oxzion\Model\ActivityInstance;
use Oxzion\Service\ActivityInstanceService;
use Oxzion\Service\WorkflowInstanceService;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Oxzion\Service\CommandService;
use Oxzion\InvalidParameterException;
use Oxzion\EntityNotFoundException;
use Logger;
use Exception;

class ActivityInstanceController extends AbstractApiControllerHelper
{
    private $activityInstanceService;
    private $workflowInstanceService;
    private $log;
    /**
    * @ignore __construct
    */
    public function __construct(ActivityInstanceService $activityInstanceService,
        WorkflowInstanceService $workflowInstanceService,CommandService $commandService)
    {
        $this->activityInstanceService = $activityInstanceService;
        $this->workflowInstanceService = $workflowInstanceService;
        $this->commandService = $commandService;
        $this->log = Logger::getLogger(__CLASS__);
    }
    /**
    * Activity Instance API
    * @api
    * @method POST
    * @link /activityInstance
    * @return array success|failure response
    */

    public function addActivityInstanceAction()
    {
        $data = $this->extractPostData();
        $this->log->info("Post Data- ". print_r(json_encode($data), true));
        try {
            $this->createActivityInstanceEntry($data);
            $this->log->info("Add Activity Instance Successful");
            return $this->getSuccessResponseWithData($data);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    private function createActivityInstanceEntry(&$data){
        $this->log->info("CREATE ACTIVITY INSTANCE ENTRY - Activity INstance");
        try{
            $this->activityInstanceService->createActivityInstanceEntry($data,$this->commandService);
            $this->log->info(ActivityInstanceController::class.":Add Activity Instance Successful");
        }catch(EntityNotFoundException $e){
            $this->log->info("Entity Not FOund Instance");
            if(isset($data['processVariables'])){
                $variables = $data['processVariables'];
                if(isset($variables['workflow_id']) || isset($variables['workflowId'])){
                    $workflowId = isset($variables['workflow_id'])?$variables['workflow_id']:$variables['workflowId'];
                } else {
                    throw new InvalidParameterException("Workflow Id not set");
                }
                $workflowInstance = $this->workflowInstanceService->setupWorkflowInstance($workflowId,$data['processInstanceId'],$variables);
                if($workflowInstance){
                    return $this->createActivityInstanceEntry($data);
                }
            }
        }
    }
     /**
    }
    * Complete Activity Instance API
    * @api
    * @method POST
    * @link /activitycomplete
    * @return array success|failure response
    */

    public function completeActivityInstanceAction()
    {
        $data = $this->extractPostData();
        $this->log->info("Post Data- ". print_r(json_encode($data), true));
        try {
            $this->activityInstanceService->completeActivityInstance($data);
            return $this->getSuccessResponseWithData($data);
        }catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }
}
