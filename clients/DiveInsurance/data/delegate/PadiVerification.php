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
        if(isset($data['padi']) && $data['padi'] != ''){
            $data['member_number'] = $data['padi'];
        } 
        if(isset($data['business_padi']) && $data['business_padi'] != ''){
            $data['member_number'] = $data['business_padi'];
        }
        if(!isset($data['member_number'])){
            $data['padi_empty'] = true;
            $data['padiNotFound'] = false;
            $data['verified'] = false;
            $data['user_exists'] = 0;
            return $data;
        }
        $select = "Select firstname, MI as initial, lastname, business_name FROM padi_data WHERE member_number ='".$data['member_number']."'";
        
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
            $returnArray = array_merge($data,$response[0]);
            if(isset($response[0]['firstname']) && (!isset($response[0]['business_name']) || ($response[0]['business_name'] == '') || ($data['product'] == 'Individual Professional Liability' || $data['product'] == 'Emergency First Response' ))){
                $returnArray['business_name'] = isset($data['business_name']) ? $data['business_name'] : "";
                $returnArray['padiVerified'] = true;
            }else if(isset($response[0]['business_name']) && $response[0]['business_name'] != ''){
                $returnArray['firstname'] = isset($data['firstname']) ? $data['firstname'] : "";
                $returnArray['lastname'] = isset($data['lastname']) ? $data['lastname'] : "";
                $returnArray['initial'] = isset($data['initial']) ? $data['initial'] : "";
                $returnArray['businessPadiVerified'] = true;
                $returnArray['padiVerified'] = true;
            }else{
                $returnArray['businessPadiVerified'] = false;
            }
            if(isset($data['product'])){
                if(($data['product'] == 'Individual Professional Liability' || $data['product'] == 'Emergency First Response' ) && (!isset($response[0]['firstname']) || $response[0]['firstname'] == '')){
                    $returnArray['padiVerified'] = false;
                }else if($data['product'] == 'Dive Store' && (!isset($response[0]['business_name']) || empty($response[0]['business_name']))){
                    $returnArray['businessPadiVerified'] = false;
                }else if($data['product'] == 'Dive Boat' && (!isset($response[0]['business_name']) || empty($response[0]['business_name']))){
                    $returnArray['businessPadiVerified'] = false;
                }
            } else {
                $returnArray['address1'] = isset($data['address1']) ? $data['address1'] : "";
                $returnArray['address2'] = isset($data['address2']) ? $data['address2'] : "";
                $returnArray['city'] = isset($data['city']) ? $data['city'] : "";
                $returnArray['state'] = isset($data['state']) ? $data['state'] : "";
                $returnArray['zip'] = isset($data['zip']) ? $data['zip'] : "";
                $returnArray['country_code'] = isset($data['country_code']) ? $data['country_code'] : "";
                $returnArray['home_phone'] = isset($data['home_phone']) ? $data['home_phone'] : "";
                $returnArray['work_phone'] = isset($data['work_phone']) ? $data['work_phone'] : "";
            }
            $returnArray['padiNotFound'] = false;
            $returnArray['verified'] = true;
            $returnArray['padi_empty'] = false;
            unset($returnArray['member_number']);
            return $returnArray;
        } else {
            if(isset($response[0]['firstname']) && (!isset($response[0]['business_name']) || $response[0]['business_name'] == '')){
                $returnArray['padiVerified'] = false;
            }else if(isset($response[0]['business_name']) && $response[0]['business_name'] != ''){
                $returnArray['businessPadiVerified'] = false;
            }
            $returnArray['verified'] = true;
            $returnArray['padi_empty'] = false;
            $returnArray['padiNotFound'] = true;
            $data = array_merge($data,$returnArray);
            unset($data['member_number']);
            return $data;
        }
    }
}
