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
        if(isset($data['business_padi'])){
            $data['member_number'] = $data['business_padi'];
        }
        if(!isset($data['member_number'])){
            return;
        }
        if(isset($data['padi']) && !isset($data['business_padi'])){
            $select = "Select firstname, MI as initial, lastname, state FROM padi_data WHERE member_number ='".$data['member_number']."'";

        }else if(isset($data['business_padi'])){
            $select = "Select business_name, email as business_email, address1 as business_address1, address2 as business_address2, city as business_city, state as business_state, country_code as business_country_code, zip as business_zip, home_phone as business_home_phone, work_phone as business_work_phone, num as business_mobilephone FROM padi_data WHERE member_number ='".$data['member_number']."'";
        }
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }

            if(isset($data['padi']) && !isset($data['business_padi'])){
              $selectQuery = "Select state FROM state_license WHERE state_in_short ='".$response[0]['state']."'";

            }else if(isset($data['business_padi'])){
              $selectQuery = "Select state FROM state_license WHERE state_in_short = '".$response[0]['business_state']."'";
            }

            $resultSet = $persistenceService->selectQuery($selectQuery);
            $stateDetails = array();
            while ($resultSet->next()) {
                $stateDetails[] = $resultSet->current();
            }       
            if(isset($stateDetails) && count($stateDetails)>0){
                if(isset($response[0]['business_state'])){
                    $response[0]['business_state_in_short'] = $response[0]['business_state'];
                    $response[0]['business_state'] = $stateDetails[0]['state'];
                }else{
                    // $response[0]['state_in_short'] = $response[0]['state'];
                    $response[0]['state'] = "";
                }
            }
            
            if(isset($response[0]['business_country_code'])){
                $response[0]['business_country'] = Country::codeToCountryName($response[0]['business_country_code']);
            }else if(isset($response[0]['country_code'])){
                $response[0]['country'] = Country::codeToCountryName($response[0]['country_code']);
            }
            $returnArray = array_merge($data,$response[0]);
            if(isset($data['padi']) && !isset($data['business_padi'])){
               $returnArray['padiVerified'] = true;
            }else if(isset($data['business_padi'])){
               if(isset($response[0]['business_name'])){
                    $returnArray['businessPadiVerified'] = true;
               }else{
                        $returnArray['businessPadiVerified'] = true;
               }
            }
            if(isset($data['product'])){
                if(($data['product'] == 'Individual Professional Liability' || $data['product'] == 'Emergency First Response' ) && (!isset($response[0]['firstname']) || $response[0]['firstname'] == '')){
                    $returnArray['padiVerified'] = false;
                }
            }
            if(isset($data['business_padi'])){
                if($data['product'] == 'Dive Store' && (!isset($response[0]['business_name']) || empty($response[0]['business_name']))){
                    $returnArray['businessPadiVerified'] = false;
                }
                if($data['product'] == 'Dive Boat' && (!isset($response[0]['business_name']) || empty($response[0]['business_name']))){
                    $returnArray['businessPadiVerified'] = false;
                }
            }
            $returnArray['padiNotFound'] = false;
            $returnArray['verified'] = true;
            return $returnArray;
        } else {
            if(isset($data['padi']) && !isset($data['business_padi'])){
                $returnArray['padiVerified'] = false;
            }else if(isset($data['business_padi'])){
                $returnArray['businessPadiVerified'] = false;
            }
            $returnArray['verified'] = true;
            $returnArray['padiNotFound'] = true;
            $data = array_merge($data,$returnArray);
            return $data;
        }
    }
}
