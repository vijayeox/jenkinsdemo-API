<?php
namespace Workflow\Controller;

/**
* Activity Instance Api
*/
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
    public function __construct(ActivityInstanceService $activityInstanceService)
    {
        $this->activityInstanceService = $activityInstanceService;
        $this->log = $this->getLogger();
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
            $response = $this->activityInstanceService->createActivityInstanceEntry($data);
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
     /**
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
            $this->log->info("Complete Activity Instance Successful");
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
