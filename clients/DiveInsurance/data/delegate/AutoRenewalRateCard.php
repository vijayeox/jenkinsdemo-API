<?php


use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;


require_once __DIR__."/RateCard.php";

class AutoRenewalRateCard extends RateCard{
    public function __construct(){
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  
        $this->logger->info("AutoRenewal Rate Card");
        $startYear = date("Y");
        $endYear = date("Y") + 1;
        $policy_period = "July 01,".$startYear." - June 30,".$endYear;
        $data['start_date'] = $startYear."-07-01";
        $data['end_date'] = $endYear."-06-30";
        $date_range = array("label" => $policy_period,"value" => $data['start_date']);
        $data['start_date_range'] = $date_range;
        $this->logger->info("AUTO RATE CARD PERSISTENCE".print_r($data,true));
        $data = parent::execute($data,$persistenceService);

        $this->logger->info("PRESENT RATE card".print_r($data,true));
        if($data['product'] == 'Individual Professional Liability'){
            $this->IPLRates($data);
        }
        $data['policyStatus'] = 'Renewal Pending';
        return $data;
    }

    private function IPLRates(&$data){
        $this->logger->info("IPL RATES");
        $data['careerCoveragePrice'] = $data[$data['careerCoverage']];
        $data['scubaFitPrice'] = $data[$data['scubaFit']];
        $data['equipmentPrice'] = $data[$data['equipment']];
        $data['cylinderPrice'] = $data[$data['cylinder']];
        $data['excessLiabilityPrice'] = $data[$data['excessLiability']];
        $data['amount'] = (float)$data['careerCoveragePrice']+ (float)$data['scubaFitPrice']+ (float)$data['equipmentPrice'] +  (float)$data['cylinderPrice'] +(float)$data['excessLiabilityPrice'];
        return $data;
    }

// Data Cleanup is done here
    // private function cleanData(&$data){
    //     $this->logger->info("CLEAN DATA");
    //     $unsetVar = $this->unsetVariables[$data['product']];
    //     $this->logger->info("UNSET VARIABLES".print_r($unsetVar,true));
    //     for($i=0;$i< sizeof($unsetVar);$i++){
    //         $this->logger->info("CLEN DATA FOR");
    //         unset($data[$unsetVar[$i]]);
    //     }
    //     $this->logger->info("CLEAN DATA END".print_r($data,true));
    // }
}