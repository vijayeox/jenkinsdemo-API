<?php
namespace Oxzion\AppDelegate;

use Oxzion\Service\WorkflowInstanceService;
use Logger;

trait WorkflowTrait
{
    protected $logger;
    private $workflowInstanceService;
    private $appId;
    
    public function __construct(){
        $this->logger = Logger::getLogger(__CLASS__);
    }

    public function setWorkflowInstanceService(WorkflowInstanceService $workflowInstanceService){
        $this->workflowInstanceService=$workflowInstanceService;
    }

    protected function startWorkflow($data){
        $this->workflowInstanceService->startWorkflow($data);
    }
}
