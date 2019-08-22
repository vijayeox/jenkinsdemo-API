<?php

use Oxzion\AppDelegate\AppDelegate;
use Oxzion\Db\Persistence\Persistence;

class IndividualLiabilityImpl implements AppDelegate {
    
    public function execute(array $data,Persistence $persistenceService=null){
        
        if (in_array("Checking App Delegate", $data))
        {
           return "Checking App Delegate";
        }
    }
}
?>