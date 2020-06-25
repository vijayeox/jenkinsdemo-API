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
        // unset($data['policy_exists']);
        unset($data['firstname']);
        unset($data['lastname']);
        unset($data['business_name']);
        unset($data['initial']);
        unset($data['address1']);
        unset($data['address2']);
        unset($data['city']);
        unset($data['state']);
        unset($data['zip']);
        unset($data['country']);
        $data['sameasmailingaddress'] = true;
        unset($data['mailaddress1']);
        unset($data['mailaddress2']);
        unset($data['physical_city']);
        unset($data['physical_state']);
        unset($data['physical_zip']);
        unset($data['physical_country']);
        unset($data['home_phone_number']);
        unset($data['phone_number']);
        unset($data['padiNotApplicable']);
        unset($data['fax']);
        unset($data['email']);
        $privileges = $this->getPrivilege();
        $coverageOptions = array();
        if(isset($privileges['MANAGE_POLICY_APPROVAL_WRITE']) && 
            $privileges['MANAGE_POLICY_APPROVAL_WRITE'] == true){
            $data['initiatedByCsr'] = true;
        }else{
            $data['initiatedByCsr'] = false;
        }
        $data['firstname'] = "";
        $data['lastname'] = "";
        $data['initial'] = "";
        if(isset($data['user_exists']) && ($data['user_exists'] == 1 || $data['user_exists'] == "1")){
            $data['padiNotApplicable'] = false;
            $data['padi_empty'] = false;
            $data['padiNotFound'] = false;
            $data['businessPadiNotFound'] = false;
            $data['verified'] = false;
            return $data;
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
            return $data;
        }
        $select = "Select firstname, MI as initial, lastname, business_name,rating FROM padi_data WHERE member_number ='".$data['member_number']."'";
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
            $returnArray = array_merge($data,$response[0]);
            $returnArray['padiNotApplicable'] = false;
            if(isset($data['product']) && ($data['product'] == 'Individual Professional Liability' || $data['product'] == 'Emergency First Response'|| $data['product'] == 'Dive Boat' )){
                if($data['product'] == 'Individual Professional Liability' || $data['product'] == 'Emergency First Response'){
                    if(isset($response[0]['firstname']) && ($response[0]['firstname'] != '' && $response[0]['firstname'] != null)){
                        $returnArray['padiVerified'] = true;
                        $returnArray['businessPadiVerified'] = false;
                        $returnArray['padiNotFound'] = false;
                        if($response[0]['rating']=='EFR' && $data['product']=='Individual Professional Liability'){
                            $returnArray['padiVerified'] = false;
                            $returnArray['businessPadiVerified'] = false;
                            $returnArray['padiNotApplicable'] = true;
                            $returnArray['padiNotFound'] = false;
                        } else if($response[0]['rating']=='PM' && $data['product']=='Individual Professional Liability'){
                            $returnArray['padiVerified'] = false;
                            $returnArray['businessPadiVerified'] = false;
                            $returnArray['padiNotApplicable'] = true;
                            $returnArray['padiNotFound'] = false;
                        } else {
                            $coverageSelect = "Select coverage_name,coverage_level FROM coverage_options WHERE padi_rating ='".$response[0]['rating']."' and category IS NULL";
                            $coverageLevels = $persistenceService->selectQuery($coverageSelect);
                            if($result->count() > 0){
                                while ($coverageLevels->next()) {
                                    $coverage = $coverageLevels->current();
                                    $coverageOptions[] = array('label'=>$coverage['coverage_name'],'value'=>$coverage['coverage_level']);
                                }
                            } else {
                                $coverageSelect = "Select DISTINCT coverage_name,coverage_level FROM coverage_options";
                                $coverageLevels = $persistenceService->selectQuery($coverageSelect);
                                while ($coverageLevels->next()) {
                                    $coverage = $coverageLevels->current();
                                    $coverageOptions[] = array('label'=>$coverage['coverage_name'],'value'=>$coverage['coverage_level']);
                                }
                            }
                        }
                    } else {
                        $returnArray['padiVerified'] = false;
                        $returnArray['businessPadiVerified'] = true;
                        $returnArray['padiNotApplicable'] = true;
                        $returnArray['padiNotFound'] = false;
                    }
                } else if($data['product'] == 'Dive Boat'){
                    if(isset($response[0]['business_name']) && $response[0]['business_name'] != ''){
                        $returnArray['padiVerified'] = false;
                        $returnArray['businessPadiVerified'] = true;
                        $returnArray['padiNotFound'] = false;
                    } else if(isset($response[0]['firstname']) && ($response[0]['firstname'] != '' && $response[0]['firstname'] != null)){
                        $returnArray['padiVerified'] = true;
                        $returnArray['businessPadiVerified'] = false;
                        $returnArray['padiNotApplicable'] = false;
                        $returnArray['padiNotFound'] = false;
                    } else {
                        $returnArray['padiVerified'] = false;
                        $returnArray['businessPadiVerified'] = false;
                        $returnArray['padiNotFound'] = true;
                    }
                } else {
                    $returnArray['padiVerified'] = true;
                    $returnArray['businessPadiVerified'] = false;
                }
            } else if(isset($data['product']) && ($data['product'] == 'Dive Store')){
                if(isset($response[0]['business_name']) && $response[0]['business_name'] != ''){
                    $returnArray['padiVerified'] = false;
                    $returnArray['businessPadiVerified'] = true;
                    $returnArray['padiNotFound'] = false;
                } else if(isset($response[0]['firstname']) && ($response[0]['firstname'] != '' && $response[0]['firstname'] != null)){
                    $returnArray['padiVerified'] = false;
                    $returnArray['businessPadiVerified'] = false;
                    $returnArray['padiNotApplicable'] = true;
                    $returnArray['padiNotFound'] = false;
                } else {
                    $returnArray['padiVerified'] = false;
                    $returnArray['businessPadiVerified'] = false;
                    $returnArray['padiNotFound'] = true;
                }
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
            $returnArray['careerCoverageOptions'] = $coverageOptions;
            unset($returnArray['member_number']);
            unset($privileges);
            return $returnArray;
        } else {
            $returnArray = array();
            $coverageSelect = "Select DISTINCT coverage_name,coverage_level FROM coverage_options WHERE category IS NULL";
            $coverageLevels = $persistenceService->selectQuery($coverageSelect);
            while ($coverageLevels->next()) {
                $coverage = $coverageLevels->current();
                $coverageOptions[] = array('label'=>$coverage['coverage_name'],'value'=>$coverage['coverage_level']);
            }
            $returnArray['businessPadiVerified'] = false;
            $returnArray['padiVerified'] = false;
            $returnArray['verified'] = true;
            $returnArray['padi_empty'] = false;
            $returnArray['businessPadiEmpty'] = false;
            $returnArray['padiNotFound'] = true;
            $returnArray['businessPadiNotFound'] = true;
            $returnArray['careerCoverageOptions'] = $coverageOptions;
            $returnArray['padiNotFoundCsrReview'] = true;
            $returnArray['padiNotApplicable'] = false;
            $data = array_merge($data,$returnArray);
            unset($data['member_number']);
            unset($privileges);
            return $data;
        }
    }
}