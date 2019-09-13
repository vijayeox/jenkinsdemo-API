<?php

use Oxzion\Rule\RuleEngine;
use Oxzion\Db\Persistence\Persistence;

class IndividualLiabilityImpl implements RuleEngine {
    
    public function runRule(array $data,Persistence $persistenceService=null){
        
        if (in_array("Checking Rule Engine", $data))
        {
           return "Checking Rule Engine";
        }
    }
}
?>