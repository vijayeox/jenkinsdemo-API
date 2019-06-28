<?php
namespace Workflow\Controller;
/**
* Activity Instance Api
*/
use Zend\Log\Logger;
use Workflow\Model\ActivityInstance;
use Workflow\Service\ActivityInstanceService;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;

class ActivityInstanceController extends AbstractApiControllerHelper
{
    private $activityInstanceService;
    private $log;
    
    /**
    * @ignore __construct
    */
	public function __construct(ActivityInstanceService $activityInstanceService, Logger $log) {
        $this->activityInstanceService = $activityInstanceService;
        $this->log = $log;
	}
	 /**
    * Activity Instance API
    * @api
    * @method POST
    * @link /activityInstance/:Id/save
    * @return array success|failure response
    */

    public function addActivityInstanceAction() { 
        $data = $this->params()->fromPost();
        $this->log->info(ActivityInstanceController::class.":Post Data- ". print_r($data, true));
        try {
            $count = $this->activityInstanceService->createActivityInstanceEntry($data);
            $this->log->info(ActivityInstanceController::class.":Add Activity Instance Successful");
            return $this->getSuccessResponse(200);
        } catch (ValidationException $e) {
            $this->log->info(ActivityInstanceController::class.":Exception at Add Activity Instance-".$e->getMessage());
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        $this->log->info(ActivityInstanceController::class.":Entity not found -");
            return $this->getErrorResponse("Entity not found", 404);
        // }
        
    }
}