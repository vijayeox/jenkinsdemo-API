<?php

use Oxzion\AppDelegate\AppDelegate;
use Oxzion\Db\Persistence\Persistence;

class IndividualLiabilityImpl implements AppDelegate {
    private $logger;
    public function setLogger($logger){
        $this->logger = $logger;
    }
    public function execute(array $data,Persistence $persistenceService=null){
        $this->logger->info("executing IndividualLiability");
        if (in_array("Checking App Delegate", $data))
        {
           return "Checking App Delegate";
        }
    }
}
?>