<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\DelegateException;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
require_once __DIR__."/RateCard.php";

class RenewalRateCard extends RateCard
{
    use FileTrait;
    public function __construct()
    {
        parent::__construct();
        $this->unsetVariables = array('Individual Professional Liability' => array('workflowInstanceId','policy_id','certificate_no','documents','autoRenewalJob'),'Emergency First Response' => array('workflowInstanceId','policy_id','certificate_no','documents','autoRenewalJob'),'Dive Boat' => array('workflowInstanceId','policy_id','certificate_no','documents','autoRenewalJob'),'Dive Store' => array('workflowInstanceId','policy_id','certificate_no','documents','autoRenewalJob'));
        $this->coverages = array('Individual Professional Liability' => array('careerCoveragePrice'=>'careerCoverage','scubaFitPrice'=>'scubaFit','equipmentPrice'=>'equipment','cylinderPrice'=>'cylinder','excessLiabilityPrice'=>'excessLiability'),'Emergency First Response' => array('coverageAmount'=>'liabilityCoverage'));
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Renewal Rate Card DATA".print_r($data,true));
        $this->logger->info("CLEAN DATA");
        if(isset($data['data'])){
            if(is_string($data['data'])){
                $data['form_data'] = json_decode($data['data'],true);
                foreach ($data['form_data'] as $key => $value) {
                    if(is_string($data['form_data'][$key])){
                        try {
                            $data['form_data'][$key]  = json_decode($value, true);
                            if (is_null($data['form_data'][$key])) {
                                $data['form_data'][$key]  = $value;
                            }
                            } catch (Exception $e) {
                              $data['form_data'][$key]  = $value;
                            }
                    }
                }
            }
        }
        //Set Date PERIOD
        $startYear = date("Y");
        $endYear = date("Y") + 1;

        $data['form_data']['isRenewalFlow'] = true;
         if (AuthContext::isPrivileged('MANAGE_POLICY_APPROVAL_WRITE')) {
            $data['form_data']['initiatedByCsr'] = true;
        }else{
            $data['form_data']['initiatedByCsr'] = false;
        }

        if($data['form_data']['product'] == 'Dive Boat'){
            // SET DEFAULT DATE FOR DIVE
            $data['form_data']['workflowId'] = 'bb15e393-11b9-48ea-bc5a-5b7616047cb1';
            $data['form_data']['start_date'] = $startYear."-07-22";
            $data['form_data']['end_date'] = $endYear."-07-22";
            $date=date_create($data['form_data']['start_date']);
            $data['form_data']['policyPeriod'] = date_format($date,"m-d-Y");
        }else if($data['form_data']['product'] == 'Dive Store'){
            $data['form_data']['workflowId'] = 'cb99e634-de00-468d-9230-d6f77d241c5b';
            $data['form_data']['start_date'] = $startYear."-06-30";
            $data['form_data']['end_date'] = $endYear."-06-30";
            $date=date_create($data['form_data']['start_date']);
            $data['form_data']['policyPeriod'] = date_format($date,"m-d-Y");
            $select = "Select firstname, MI as initial, lastname, business_name,rating FROM padi_data WHERE member_number ='".$data['form_data']['padi']."'";
            $result = $persistenceService->selectQuery($select);
            $coverageOptions = array();
            if($result->count() > 0){
                $response = array();
                while ($result->next()) {
                    $response[] = $result->current();
                }
                if(isset($response[0]['business_name']) && $response[0]['business_name'] != ''){
                    $data['form_data']['padiVerified'] = false;
                    $data['form_data']['businessPadiVerified'] = true;
                    $data['form_data']['padiNotFound'] = false;
                } else if(isset($response[0]['firstname']) && ($response[0]['firstname'] != '' && $response[0]['firstname'] != null)){
                    $data['form_data']['padiVerified'] = false;
                    $data['form_data']['businessPadiVerified'] = false;
                    $data['form_data']['padiNotApplicable'] = true;
                    $data['form_data']['padiNotFound'] = false;
                } else {
                    $data['form_data']['padiVerified'] = false;
                    $data['form_data']['businessPadiVerified'] = false;
                    $data['form_data']['padiNotFound'] = true;
                }
            } else {
                $data['form_data']['padiVerified'] = false;
                $data['form_data']['padiNotApplicable'] = true;
                $data['form_data']['padiNotFound'] = true;
            }
            if(isset($data['form_data']['groupPL'])){
              $data['form_data']['groupPL'] = $this->verifyGroupPadi($data['form_data']['groupPL'],$persistenceService);
            }
        }
        else{
            // UPDATE YEAR + 1
            $data['form_data']['businessPadiVerified'] = "false";
            $data['form_data']['start_date'] = $startYear."-06-30";
            $data['form_data']['end_date'] = $endYear."-06-30";
            $policy_period = "June 30,".$startYear." - June 30,".$endYear;
            // UPDATE DEFAULT RANGE
            $date_range = array("label" => $policy_period,"value" => $data['form_data']['start_date']);
            $data['form_data']['start_date_range'] = $date_range;
        }
        if($data['form_data']['product']=='Emergency First Response'){
            $data['form_data']['workflowId'] = 'cb74d176-225a-11ea-978f-2e728ce88125';
            $select = "Select firstname, MI as initial, lastname, business_name,rating FROM padi_data WHERE member_number ='".$data['form_data']['padi']."' AND rating = 'EFR'";
            $result = $persistenceService->selectQuery($select);
            $coverageOptions = array();
            if($result->count() > 0){
                $response = array();
                while ($result->next()) {
                    $response[] = $result->current();
                }
                $data['form_data']['padiNotApplicable'] = false;
            } else {
                $data['form_data']['padiNotApplicable'] = true;
                $data['form_data']['padiNotFound'] = true;
            }
        }
        if($data['form_data']['product']=='Individual Professional Liability'){
            $data['form_data']['workflowId'] = 'f0efea9e-7863-4368-a9b2-baa1a1603067';
            $select = "Select firstname, MI as initial, lastname, business_name,rating FROM padi_data WHERE member_number ='".$data['form_data']['padi']."'";
            $result = $persistenceService->selectQuery($select);
            $coverageOptions = array();
            if($result->count() > 0){
                $response = array();
                while ($result->next()) {
                    $response[] = $result->current();
                }
            }
            if (isset($response) && count($response) > 0) {
                $coverageSelect = "Select coverage_name,coverage_level FROM coverage_options WHERE padi_rating ='".$response[0]['rating']."' and category IS NULL";
                $coverageLevels = $persistenceService->selectQuery($coverageSelect);
                if($coverageLevels->count() > 0){
                    while ($coverageLevels->next()) {
                        $coverage = $coverageLevels->current();
                        $coverageOptions[] = array('label'=>$coverage['coverage_name'],'value'=>$coverage['coverage_level']);
                    }
                    $data['form_data']['padiNotApplicable'] = false;
                } else {
                    $data['form_data']['padiNotApplicable'] = true;
                }
            } else {
                $coverageSelect = "Select DISTINCT coverage_name,coverage_level FROM coverage_options WHERE category IS NULL";
                $coverageLevels = $persistenceService->selectQuery($coverageSelect);
                while ($coverageLevels->next()) {
                    $coverage = $coverageLevels->current();
                    $coverageOptions[] = array('label'=>$coverage['coverage_name'],'value'=>$coverage['coverage_level']);
                }
                $data['form_data']['padiNotApplicable'] = true;
            }
            $data['form_data']['careerCoverageOptions'] = $coverageOptions;
        }
        $filterParams = array();
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'start_date','operator'=>'gte','value'=>$data['form_data']['start_date']);
        // $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$data['form_data']['end_date']);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'padi','operator'=>'eq','value'=>$data['form_data']['padi']);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'eq','value'=>$data['form_data']['product']);
        // print_r($filterParams);
        $policyList = $this->getFileList(null,$filterParams);
        $this->logger->info("RENEWAL RATE CARD POLICY FILE--- ".print_r($policyList,true));
        if(count($policyList['data']) > 0){
            $policy_exists = true;
        } else {
            $policy_exists = false;
        }

        if($policy_exists == true){
            if(isset($data['form_data']['policyPeriod'])){
                throw new DelegateException("Policy exists for the Policy Period ".$data['form_data']['policyPeriod']." - ".date_format(date_create($data['form_data']['end_date']),"m-d-Y"), "policy_initiated");
            } else {
                if(isset($data['form_data']['start_date'])){
                    throw new DelegateException("Policy exists for the for the Policy Period ".$data['form_data']['start_date']." - ".date_format(date_create($data['form_data']['end_date']),"m-d-Y"), "policy_initiated");
                }
            }
        }

        //END DATE PERIOD SELECTION
        $this->logger->info("AUTO RATE CARD PERSISTENCE".print_r($data,true));
        $data['form_data'] = parent::execute($data['form_data'],$persistenceService);
        //GET RATES FOR CURRENT YEAR

        $data['form_data'] = $this->rateCalculation($data['form_data']);
        //CLEAN FILE DATA FOR CURRENT YEAR
        $data['form_data'] = $this->cleanExistingData($data['form_data']);
        $data['form_data']['policyStatus'] = "Renewal Approval Pending";
        //END CLEAN FILE DATA FOR CURRENT YEAR
        $this->logger->info("CLEAN DATA END" . print_r($data, true));
        $data['form_data']['userApproved'] = "";
        $data['form_data']['premiumFinanceSelect'] = "";
        $data['form_data']['paymentOptions'] = "";
        $data['form_data']['paymentVerified'] = "";
        $data['form_data']['verified'] = true;
        $data['form_data']['padiVerified'] = true;
        $data['data'] = json_encode($data['form_data']);
        unset($data['form_data']);
        return $data;
    }
    private function cleanExistingData(&$data){
        $product = $data['product'];
        $this->logger->info("CLEAN DATA FOR");
        if(isset($this->unsetVariables[$product])){
            foreach ($this->unsetVariables[$product] as $key => $value) {
                unset($data[$this->unsetVariables[$product][$key]]);
            }
        }
        return $data;
    }
    private function rateCalculation(&$data){
        $this->logger->info("RATES");
        $product = $data['product'];
        if(isset($this->coverages[$product])){
            foreach ($this->coverages[$product] as $key => $value) {
                if(isset($data[$data[$value]]) && $data[$value]){
                    $data[$key] = $data[$data[$value]];
                }
            }
            $data['amount'] = 0;
            if(isset($this->coverages[$data['product']])){
                foreach ($this->coverages[$data['product']] as $key => $value) {
                    $data['amount'] = (float) $data['amount'] + (float) $data[$key];
                }
            }
        }
        return $data;
    }
    private function verifyGroupPadi(&$groupMembers,$persistenceService){
      $verifiedPadiMembers = array();
      $i = 0;
      foreach ($groupMembers as $key => $member) {
        $select = "Select firstname, MI as initial, lastname, business_name,rating FROM padi_data WHERE member_number ='".$member['padi']."'";
        $result = $persistenceService->selectQuery($select);
        $coverageOptions = array();
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
        }
        if (isset($response) && count($response) > 0) {
            $member['rating'] = implode(',',array_column($response, 'rating'));
            $verifiedPadiMembers[] = $member;
            $i++;
        }
      }
      return $verifiedPadiMembers;
    }
}
