<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\WorkflowTrait;
use Oxzion\AppDelegate\FileTrait;


class ProcessEFRCancellation extends AbstractAppDelegate
{
	use WorkflowTrait;
	use FileTrait;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {  
        $fileData = $this->getFile($data['EFRFileId']);
        $fileData = $fileData['data'];
        $fileData['reasonforCsrCancellation'] = array("value"=>"others");
        $fileData['othersCsr'] = "Upgrade To IPL";
        $fileData['cancellationStatus'] = "approved";
        $fileData['disableReinstate'] = true;
        $fileData['workflowId'] = "81cb9e10-5845-4379-97c9-f9486b702bda";
        $fileData['fileId'] = $data['EFRFileId'];
        $fileData['parentWorkflowInstanceId'] = $data['EFRWorkflowInstanceId'];
        $this->logger->info("EFR Cancellation Data sent to WF ---".json_encode($fileData));

        $result = $this->startWorkflow($fileData);
        $this->logger->info("EFR Cancellation Result ---".json_encode($result));
        return $data;
    }
}
