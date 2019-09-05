<?php

use Oxzion\AppDelegate\DocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class IndividualLiabilityImpl implements DocumentAppDelegate {
    private $logger;
    private $builder;
    public function setLogger($logger){
        $this->logger = $logger;
    }
    public function setDocumentBuilder($builder){
        $this->builder = $builder;
    }
    public function setDocumentDestination($destination)
    {
        $this->destination = $destination;
    }
    public function execute(array $data,Persistence $persistenceService){ 
        $this->logger->info("executing IndividualLiability");
        if(!$this->builder){
            return array("Document Builder Not set");
        }
        if (in_array("Checking App Delegate", $data))
        {
           return array("Checking App Delegate");
        }

    }
}
?>