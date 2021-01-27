<?php
namespace Oxzion\AppDelegate;

use Oxzion\Service\WorkflowInstanceService;
use Oxzion\Service\ActivityInstanceService;
use Logger;

trait WorkflowTrait
{
    protected $logger;
    private $workflowInstanceService;
    private $activityInstanceService;
    private $appId;
    
    public function __construct(){
        $this->logger = Logger::getLogger(__CLASS__);
    }

    public function setWorkflowInstanceService(WorkflowInstanceService $workflowInstanceService){
        $this->workflowInstanceService=$workflowInstanceService;
    }
    public function setActivityInstanceService(ActivityInstanceService $activityInstanceService){
        $this->activityInstanceService=$activityInstanceService;
    }

    protected function startWorkflow($data){
        $this->workflowInstanceService->startWorkflow($data);
    }

    public function getActivityChangeLog($activityInstanceId,$labelMapping){
       return $this->activityInstanceService->getActivityChangeLog($activityInstanceId,$labelMapping);
    }

    public function getFileDataByActivityInstanceId($activityInstanceId){
        return $this->activityInstanceService->getFileDataByActivityInstanceId($activityInstanceId);
    }
    public function getWorkflowSubmissionData($workflowInstanceId){
        return $this->workflowInstanceService->getWorkflowSubmissionData($workflowInstanceId);
    }
    public function getWorkflowChangeLog($workflowInstanceId,$labelMapping){
       return $this->workflowInstanceService->getWorkflowChangeLog($workflowInstanceId,$labelMapping);
    }

    public function getWorkflowInstanceDataFromFileId($fileId){
       return $this->workflowInstanceService->getWorkflowInstanceDataFromFileId($fileId);
    }
    public function getWorkflowCompletedData($params,$filterparams = null) {
        return $this->workflowInstanceService->getWorkflowCompletedData($params,$filterparams);
    }
}
