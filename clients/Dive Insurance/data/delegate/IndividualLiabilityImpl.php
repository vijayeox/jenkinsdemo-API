<?php

use Oxzion\AppDelegate\AppDelegate;
use Oxzion\Db\Persistence\Persistence;

class IndividualLiabilityImpl implements AppDelegate {
    private $logger;
    public function setLogger($logger){
        $this->logger = $logger;
    }
    public function execute(array $data,Persistence $persistenceService){ 
        $this->logger->info("executing IndividualLiability");
        if (in_array("Checking App Delegate", $data))
        {
           return array("Checking App Delegate");
        }

    }
}
?>