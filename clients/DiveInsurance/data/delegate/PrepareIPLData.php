<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class PrepareIPLData extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {  
        if(isset($data['iplPolicyData'])){
            $this->logger->info("PrepareIPLData Data Sent ---".json_encode($data['iplPolicyData']));
            return $data['iplPolicyData'];
        }
    }
}
