<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\ArtifactUtils;

class RenewalPreprocess extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Renewal Data - ".json_encode($data));
        if(isset($data['fileId'])){
            $data['previous_fileId'] = $data['fileId'];
            // unset($data['fileId']);
        }
        if(isset($data['workflowId'])){
            $data['parent_workflow_id'] = $data['workflowId'];
            unset($data['workflowId']);
        }
        // unset($data['approved']);
        unset($data['parentWorkflowInstanceId']);
        return $data;
    }
}
