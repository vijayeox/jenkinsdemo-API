<?php

use Oxzion\AppDelegate\AppDelegate;
use Oxzion\Db\Persistence\Persistence;

class PadiVerification implements AppDelegate
{
    private $logger;
    public function setLogger($logger){
        $this->logger = $logger;
    }

    // Padi Verification is performed here
    public function execute(array $data,Persistence $persistenceService)
    {  
        $this->logger->info("Padi Verification");
        $select = "Select * FROM padi_data WHERE member_number ='".$data['member_number']."'";
        $result = $persistenceService->selectQuery($select);
        $response = array();
        while ($result->next()) {
            $response[] = $result->current();
        }
        return $response;
    }
}
