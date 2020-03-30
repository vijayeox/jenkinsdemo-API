<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\Country;
use Oxzion\AppDelegate\UserContextTrait;

class PadiVerification extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }

    // Padi Verification is performed here
    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Padi Verification new".json_encode($data));
        unset($data['businessPadiVerified']);
        unset($data['padiVerified']);
        unset($data['verified']);
        unset($data['padi_empty']);
        unset($data['businessPadiEmpty']);
        unset($data['padiNotFound']);
        unset($data['businessPadiNotFound']);
        unset($data['padiNotFoundCsrReview']);
        unset($data['padiNotFound']);
        unset($data['user_exists']);
        unset($data['policy_exists']);
        unset($data['firstname']);
        unset($data['lastname']);
        unset($data['business_name']);
        unset($data['initial']);
        $privileges = $this->getPrivilege();
        if(isset($privileges['MANAGE_POLICY_APPROVAL_WRITE']) && 
            $privileges['MANAGE_POLICY_APPROVAL_WRITE'] == true){
            $data['initiatedByCsr'] = true;
        }else{
            $data['initiatedByCsr'] = false;
        }
        if(isset($data['padi']) && $data['padi'] != ''){
            $data['member_number'] = $data['padi'];
        } 
        if(isset($data['business_padi']) && $data['business_padi'] != ''){
            $data['member_number'] = $data['business_padi'];
        }
        if(!isset($data['member_number'])){
            $data['businessPadiEmpty'] = true;
            $data['padi_empty'] = true;
            $data['padiNotFound'] = false;
            $data['businessPadiNotFound'] = false;
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
            if(isset($data['product']) && (($data['product'] == 'Individual Professional Liability' || $data['product'] == 'Emergency First Response' ) && (isset($response[0]['firstname']) && $response[0]['firstname'] != '' && $response[0]['firstname'] != null))){
                $returnArray['padiVerified'] = true;
                $returnArray['businessPadiVerified'] = false;

            }else if(isset($data['product']) && (($data['product'] == 'Dive Store'|| $data['product'] == 'Dive Boat') && (isset($response[0]['business_name']) && !empty($response[0]['business_name'])))) {
                $returnArray['businessPadiVerified'] = true;
                $returnArray['padiVerified'] = false;
            } else {
                if(isset($response[0]['firstname']) && $response[0]['firstname'] != '' && $response[0]['firstname'] != null ){
                    $returnArray['padiVerified'] = true;
                    $returnArray['businessPadiVerified'] = false;
                } else if(isset($response[0]['business_name']) && $response[0]['business_name'] != ''){
                    $returnArray['padiVerified'] = false;
                    $returnArray['businessPadiVerified'] = true;
                } else {
                    $returnArray['padiVerified'] = false;
                    $returnArray['businessPadiVerified'] = false;
                }
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
            $returnArray['businessPadiNotFound'] = false;
            $returnArray['verified'] = true;
            $returnArray['padi_empty'] = false;
            $returnArray['businessPadiEmpty'] = false;
            $returnArray['padiNotFoundCsrReview'] = false;
            unset($returnArray['member_number']);
            unset($privileges);
            return $returnArray;
        } else {
            $returnArray = array();
            $returnArray['businessPadiVerified'] = false;
            $returnArray['padiVerified'] = false;
            $returnArray['verified'] = true;
            $returnArray['padi_empty'] = false;
            $returnArray['businessPadiEmpty'] = false;
            $returnArray['padiNotFound'] = true;
            $returnArray['businessPadiNotFound'] = true;
            $returnArray['padiNotFoundCsrReview'] = true;
            $data = array_merge($data,$returnArray);
            unset($data['member_number']);
            unset($privileges);
            return $data;
        }
    }
}