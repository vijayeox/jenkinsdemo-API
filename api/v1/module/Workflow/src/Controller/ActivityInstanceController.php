<?php
namespace Workflow\Controller;

/**
* Activity Instance Api
*/
use Workflow\Model\ActivityInstance;
use Workflow\Service\ActivityInstanceService;
use Workflow\Service\WorkflowInstanceService;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Oxzion\EntityNotFoundException;
use Logger;

class ActivityInstanceController extends AbstractApiControllerHelper
{
    private $activityInstanceService;
    private $workflowInstanceService;
    private $log;
    /**
    * @ignore __construct
    */
    public function __construct(ActivityInstanceService $activityInstanceService,
        WorkflowInstanceService $workflowInstanceService)
    {
        $this->activityInstanceService = $activityInstanceService;
        $this->workflowInstanceService = $workflowInstanceService;
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
            $response = $this->createActivityInstanceEntry($data);
            $this->log->info("Add Activity Instance Successful");
            if ($response == 0) {
                return $this->getErrorResponse("Entity not found", 404);
            }
            return $this->getSuccessResponseWithData($response);
        } catch (ValidationException $e) {
            $this->log->info("Exception at Add Activity Instance-".$e->getMessage());
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
    }

    private function createActivityInstanceEntry($data){
        $this->log->info("CREATE ACTIVITY INSTANCE ENTRY - Activity INstance");
        try{
            $response = $this->activityInstanceService->createActivityInstanceEntry($data);
            $this->log->info(ActivityInstanceController::class.":Add Activity Instance Successful");
            
        }catch(EntityNotFoundException $e){
            $this->log->info("Entity Not FOund Instance");
            if(isset($data['processVariables'])){
                $variables = $data['processVariables'];
                if(isset($variables['workflow_id']) || isset($variables['workflowId'])){
                    $workflowId = isset($variables['workflow_id'])?$variables['workflow_id']:$variables['workflowId'];
                } else {
                    return 0;
                }
                $workflowInstance = $this->workflowInstanceService->setupWorkflowInstance($workflowId,$data['processInstanceId'],$variables);
                if($workflowInstance){
                    return $this->createActivityInstanceEntry($data);
                }
            }
        }
        return $response;
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
            $response = $this->activityInstanceService->completeActivityInstance($data);
            $this->log->info("Complete Activity Instance response - ".print_r($response, true));
            if ($response == 0 || empty($response)) {
                return $this->getErrorResponse("Entity not found", 404);
            }
            return $this->getSuccessResponseWithData($response);
        } catch (ValidationException $e) {
            $this->log->info("Exception at Add Activity Instance-".$e->getMessage());
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
    }
}
