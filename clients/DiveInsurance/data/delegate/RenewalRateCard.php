<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

require_once __DIR__."/RateCard.php";

class RenewalRateCard extends RateCard
{
    public function __construct()
    {
        parent::__construct();
        $this->unsetVariables = array('Individual Professional Liability' => array ('workflowInstanceId','policy_id','certificate_no','start_date','end_date','documents'));
        $this->coverages = array('Individual Professional Liability' => array ('careerCoveragePrice'=>'careerCoverage','scubaFitPrice'=>'scubaFit','equipmentPrice'=>'equipment','cylinderPrice'=>'cylinder','excessLiabilityPrice'=>'excessLiability'));
    }

//  Data Cleanup is done here
    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("CLEAN DATA");
        if(isset($data['data'])){
            if(is_string($data['data']['data'])){
                $data['form_data'] = json_decode($data['data']['data'],true);
            }
        }
        $startYear = date("Y");
        $endYear = date("Y") + 1;
        $policy_period = "July 01,".$startYear." - June 30,".$endYear;
        $data['form_data']['start_date'] = $startYear."-07-01";
        $data['form_data']['end_date'] = $endYear."-06-30";
        $date_range = array("label" => $policy_period,"value" => $data['form_data']['start_date']);
        $data['form_data']['start_date_range'] = $date_range;

        $this->logger->info("AUTO RATE CARD PERSISTENCE".print_r($data,true));

        $data['form_data'] = parent::execute($data['form_data'],$persistenceService);
        $unsetVar = $this->unsetVariables[$data['form_data']['product']];
        $coverages = $this->coverages[$data['form_data']['product']];
        $data['form_data'] = $this->rateCalculation($data['form_data']);
        $this->logger->info("UNSET VARIABLES" . print_r($unsetVar, true));
        for ($i = 0; $i < sizeof($unsetVar); $i++) {
            $this->logger->info("CLEAN DATA FOR");
            unset($data['form_data'][$unsetVar[$i]]);
        }
        
        $this->logger->info("CLEAN DATA END" . print_r($data, true));

        return $data;
    }
    private function rateCalculation(&$data){
        $this->logger->info("RATES");
        foreach ($this->coverages[$data['product']] as $key => $value) {
            if(isset($data[$data[$value]])){
                $data[$key] = $data[$data[$value]];
            }
        }
        $data['amount'] = 0;
        foreach ($this->coverages[$data['product']] as $key => $value) {
           $data['amount'] = (float) $data['amount'] + (float) $data[$key];
        }
        return $data;
    }
}
