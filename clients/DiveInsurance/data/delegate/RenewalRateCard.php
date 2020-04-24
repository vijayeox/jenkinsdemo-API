<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\ServiceException;
require_once __DIR__."/RateCard.php";

class RenewalRateCard extends RateCard
{
    use FileTrait;
    public function __construct()
    {
        parent::__construct();
        $this->unsetVariables = array('Individual Professional Liability' => array('workflowInstanceId','policy_id','certificate_no','documents','autoRenewalJob'),'Emergency First Response' => array('workflowInstanceId','policy_id','certificate_no','documents','autoRenewalJob'),'Dive Boat' => array('workflowInstanceId','policy_id','certificate_no','documents','autoRenewalJob'),'Dive Store' => array('workflowInstanceId','policy_id','certificate_no','documents','autoRenewalJob'));
        $this->coverages = array('Individual Professional Liability' => array('careerCoveragePrice'=>'careerCoverage','scubaFitPrice'=>'scubaFit','equipmentPrice'=>'equipment','cylinderPrice'=>'cylinder','excessLiabilityPrice'=>'excessLiability'),'Emergency First Response' => array('coverageAmount'=>'liabilityCoverageName'));
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Renewal Rate Card DATA".print_r($data,true));
        $this->logger->info("CLEAN DATA");
        if(isset($data['data'])){
            if(is_string($data['data'])){
                $data['form_data'] = json_decode($data['data'],true);
            }
        }
        //Set Date Period
        $startYear = date("Y");
        $endYear = date("Y") + 1;
        
        if($data['form_data']['product'] == 'Dive Boat'){
            // SET DEFAULT DATE FOR DIVE 
            $data['form_data']['start_date'] = $startYear."-07-22";
            $data['form_data']['end_date'] = $endYear."-07-22";
            $date=date_create($data['form_data']['start_date']);
            $data['form_data']['policyPeriod'] = date_format($date,"m-d-Y");
        }
        else{
            // UPDATE YEAR + 1
            $data['form_data']['start_date'] = $startYear."-07-01";
            $data['form_data']['end_date'] = $endYear."-06-30";
            $policy_period = "July 01,".$startYear." - June 30,".$endYear;
            // UPDATE DEFAULT RANGE
            $date_range = array("label" => $policy_period,"value" => $data['form_data']['start_date']);
            $data['form_data']['start_date_range'] = $date_range;
        }

        $filterParams = array();
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'start_date','operator'=>'gte','value'=>$data['form_data']['start_date']);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$data['form_data']['end_date']);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'padi','operator'=>'eq','value'=>$data['form_data']['padi']);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'eq','value'=>$data['form_data']['product']);
        $policyList = $this->getFileList(null,$filterParams);
        if(count($policyList['data']) > 0){
            $policy_exists = true;
        } else {
            $policy_exists = false;
        }

        if($policy_exists == true){
            throw new ServiceException("Renewal Process has already been initiated for this PADI Number for the Policy Period(".$data['form_data']['policyPeriod']." - ".date_format(date_create($data['form_data']['end_date']),"m-d-Y"), "policy_initiated");
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
                if(isset($data[$data[$value]])){
                    $data[$key] = $data[$data[$value]];
                }
            }
            $data['amount'] = 0;
            foreach ($this->coverages[$data['product']] as $key => $value) {
                $data['amount'] = (float) $data['amount'] + (float) $data[$key];
            }
        }
        return $data;
    }
}
