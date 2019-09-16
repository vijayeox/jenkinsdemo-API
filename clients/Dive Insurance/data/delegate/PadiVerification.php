<?php

use Oxzion\AppDelegate\AppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\Country;

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
        if(isset($data['padi'])){
            $data['member_number'] = $data['padi'];
        }
        if(!isset($data['member_number'])){
            return;
        }
        $select = "Select * FROM padi_data WHERE member_number ='".$data['member_number']."'";
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
            if(isset($response['country_code'])){
                $response['country'] = Country::codeToCountryName($response['country_code']);
            }
            $returnArray = array_merge($data,$response[0]);
            $returnArray['padiVerified'] = true;
            return $returnArray;
        } else {
            $returnArray['padiVerified'] = false;
            return $data;
        }
    }
}
