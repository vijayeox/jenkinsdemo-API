<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\Country;

class PadiVerification extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
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
        $select = "Select firstname, MI, lastname, email, address1, address2, city, state, country_code, zip, home_phone, work_phone, num as mobilephone FROM padi_data WHERE member_number ='".$data['member_number']."'";  
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
            
            $selectQuery = "Select state FROM state_license WHERE state_in_short ='".$response[0]['state']."'";  
            $resultSet = $persistenceService->selectQuery($selectQuery);
            while ($resultSet->next()) {
                $stateDetails[] = $resultSet->current();
            }
            $response[0]['state'] = $stateDetails[0]['state'];

            if(isset($response[0]['country_code'])){
                $response[0]['country'] = Country::codeToCountryName($response[0]['country_code']);
            }
            $returnArray = array_merge($data,$response[0]);
            $returnArray['padiVerified'] = true;
            return $returnArray;
        } else {
            $returnArray['padiVerified'] = false;
            $data = array_merge($data,$returnArray);
            return $data;
        }
    }
}
