<?php


use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;


require_once __DIR__."/RateCard.php";

class AutoRenewalRateCard extends RateCard{
    public function __construct(){
        parent::__construct();
        $this->unsetVariables = array('workflowInstanceId','policy_id','certificate_no','start_date','end_date','documents');
    }

    // Premium Calculation values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  
        $this->logger->info("AutoRenewal Rate Card");
        $this->cleanData($data);
        $startYear = date("Y");
        $endYear = date("Y") + 1;
        $data['start_date'] = $startYear."-07-01";
        $data['end_date'] = $endYear."-06-30";
        if($data['product'] == 'Dive Boat'){
            $data['start_date'] = $startYear."-07-22";
            $data['end_date'] = $endYear."-06-22";
        }
        // else{

        //     $data['policyPeriod'] = $data['start_date']."(MM DD YYYY)";
        // }
        $policy_period = "July 01,".$startYear." - June 30,".$endYear;
        $data['start_date_range'] = array("label" => $policy_period,"value" => $data['start_date']);
    
        $this->logger->info("AUTO RATE CARD PERSISTENCE".print_r($data,true));
        $data = parent::execute($data,$persistenceService);

        $this->logger->info("PRESENT RATE card".print_r($data,true));
        if($data['product'] == 'Individual Professional Liability'){
            $this->IPLRates($data);
        } else if($data['product'] == 'Emergency First Response'){
            $this->EFRRates($data);
        }else if($data['product'] == 'Dive Boat'){
            $this->DiveBoatRates($data);
        }
        $data['policyStatus'] = 'AutoRenewal Approval Pending';
        $this->logger->info("AutoRenewalRateCard Final DATA".print_r($data,true));
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
    }

    private function EFRRates(&$data){
        $this->logger->info("EFR RATES");
        $data['CoverageAmount'] = $data[$data['liabilityCoverageName']];
        $data['amount'] = (float)$data['CoverageAmount'];
    }

    private function DiveBoatRates(&$data){
        $this->logger->info("Dive Boat RATES");
        $data['groupTaxAmount'] = ((float)($data["groupCoverage"])+(float)($data["groupExcessLiability"]))*(float)(isset($data["groupTaxPercentage"])?$data["groupTaxPercentage"] : 0)/100 ;
        $data['groupPadiFeeAmount'] = (float)($data["groupPadiFee"]);
        $data['groupTotalAmount'] = (float)($data["groupTaxAmount"])+(float)($data["groupPadiFeeAmount"])+((float)(isset($data['groupPAORfee'])? ($data['groupPAORfee']) : 0))+(float)($data["groupCoverage"])+(float)($data["groupExcessLiability"]);
        $layUpPeriodTo = DateTime::createFromFormat(DateTime::ISO8601, $data['layup_period_to_date_time']);
        $layUpPeriodFrom = DateTime::createFromFormat(DateTime::ISO8601, $data['layup_period_from_date_time']);
        $data['layup_period'] = ($layUpPeriodTo->diff($layUpPeriodFrom))->format("%d");
        if($data['layup_period']<30){
            $data['layupDeductible'] = 0;
        } else if($data['layup_period'] > 30 && $data['layup_period'] <= 60) {
            $data['layupDeductible'] = ($data['PropertyBasePremium'] * $data['LayupA'])/100;
        } else if($data['layup_period'] > 60 && $data['layup_period'] <= 90) {
            $data['layupDeductible'] = ($data['PropertyBasePremium'] * $data['Layup1'])/100;
        }  else {
            $data['layupDeductible'] = ($data['PropertyBasePremium'] * $data['Layup2'])/100;
        }
        if((float)($data['boat_age'])<5){
            if((float)($data['hull_market_value']) >= 0 && (float)($data['hull_market_value']) <= 25000){
                $data['hullRate']  = (float)($data['hull25000LessThan5']);
            } else if((float)($data['hull_market_value']) > 25000 && (float)($data['hull_market_value']) <= 50000) {
                $data['hullRate'] = (float)($data['hull50000LessThan5']);
            } else if((float)($data['hull_market_value']) > 50000 && (float)($data['hull_market_value']) <= 100000) {
                $data['hullRate'] = (float)($data['hull100000LessThan5']);
            } else if((float)($data['hull_market_value']) > 100000 && (float)($data['hull_market_value']) <= 150000) {
                $data['hullRate'] = (float)($data['hull150000LessThan5']);
            } else if((float)($data['hull_market_value']) > 150000 && (float)($data['hull_market_value']) <= 200000) {
                $data['hullRate'] = (float)($data['hull200000LessThan5']);
            } else if((float)($data['hull_market_value']) > 200000 && (float)($data['hull_market_value']) <= 250000) {
                $data['hullRate'] = (float)($data['hull250000LessThan5']);
            } else if((float)($data['hull_market_value']) > 250000 && (float)($data['hull_market_value']) <= 300000) {
                $data['hullRate'] = (float)($data['hull300000LessThan5']);
            } else if((float)($data['hull_market_value']) > 300000 && (float)($data['hull_market_value']) <= 400000) {
                $data['hullRate'] = (float)($data['hull350000LessThan5']);
            } else if((float)($data['hull_market_value']) > 400000 && (float)($data['hull_market_value']) <= 500000) {
                $data['hullRate'] = (float)($data['hull400000LessThan5']);
            } else if((float)($data['hull_market_value']) > 500000 && (float)($data['hull_market_value']) <= 600000) {
                $data['hullRate'] = (float)($data['hull500000LessThan5']);
            } else {
                $data['hullRate'] = (float)($data['hull600000LessThan5']);
            }
        } else if((float)($data['boat_age']) > 5 && (float)($data['boat_age']) <= 11) {
            if((float)($data['hull_market_value']) >= 0 && (float)($data['hull_market_value']) <= 25000){
                $data['hullRate'] = (float)($data['hull25000LessThan11']);
            } else if((float)($data['hull_market_value']) > 25000 && (float)($data['hull_market_value']) <= 50000) {
                $data['hullRate'] = (float)($data['hull50000LessThan11']);
            } else if((float)($data['hull_market_value']) > 50000 && (float)($data['hull_market_value']) <= 100000) {
                $data['hullRate'] = (float)($data['hull100000LessThan11']);
            } else if((float)($data['hull_market_value']) > 100000 && (float)($data['hull_market_value']) <= 150000) {
                $data['hullRate'] = (float)($data['hull150000LessThan11']);
            } else if((float)($data['hull_market_value']) > 150000 && (float)($data['hull_market_value']) <= 200000) {
                $data['hullRate'] = (float)($data['hull200000LessThan11']);
            } else if((float)($data['hull_market_value']) > 200000 && (float)($data['hull_market_value']) <= 250000) {
                $data['hullRate'] = (float)($data['hull250000LessThan11']);
            } else if((float)($data['hull_market_value']) > 250000 && (float)($data['hull_market_value']) <= 300000) {
                $data['hullRate'] = (float)($data['hull300000LessThan11']);
            } else if((float)($data['hull_market_value']) > 300000 && (float)($data['hull_market_value']) <= 400000) {
                $data['hullRate'] = (float)($data['hull350000LessThan11']);
            } else if((float)($data['hull_market_value']) > 400000 && (float)($data['hull_market_value']) <= 500000) {
                $data['hullRate'] = (float)($data['hull400000LessThan11']);
            } else if((float)($data['hull_market_value']) > 500000 && (float)($data['hull_market_value']) <= 600000) {
                $data['hullRate'] = (float)($data['hull500000LessThan11']);
            } else {
                $data['hullRate'] = (float)($data['hull600000LessThan11']);
            }
        }else if((float)($data['boat_age']) > 11 && (float)($data['boat_age']) <= 25) {
            if((float)($data['hull_market_value']) >= 0 && (float)($data['hull_market_value']) <= 25000){
                $data['hullRate'] = (float)($data['hull25000LessThan25']);
            } else if((float)($data['hull_market_value']) > 25000 && (float)($data['hull_market_value']) <= 50000) {
                $data['hullRate'] = (float)($data['hull50000LessThan25']);
            } else if((float)($data['hull_market_value']) > 50000 && (float)($data['hull_market_value']) <= 100000) {
                $data['hullRate'] = (float)($data['hull100000LessThan25']);
            } else if((float)($data['hull_market_value']) > 100000 && (float)($data['hull_market_value']) <= 150000) {
                $data['hullRate'] = (float)($data['hull150000LessThan25']);
            } else if((float)($data['hull_market_value']) > 150000 && (float)($data['hull_market_value']) <= 200000) {
                $data['hullRate'] = (float)($data['hull200000LessThan25']);
            } else if((float)($data['hull_market_value']) > 200000 && (float)($data['hull_market_value']) <= 250000) {
                $data['hullRate'] = (float)($data['hull250000LessThan25']);
            } else if((float)($data['hull_market_value']) > 250000 && (float)($data['hull_market_value']) <= 300000) {
                $data['hullRate'] = (float)($data['hull300000LessThan25']);
            } else if((float)($data['hull_market_value']) > 300000 && (float)($data['hull_market_value']) <= 400000) {
                $data['hullRate'] = (float)($data['hull350000LessThan25']);
            } else if((float)($data['hull_market_value']) > 400000 && (float)($data['hull_market_value']) <= 500000) {
                $data['hullRate'] = (float)($data['hull400000LessThan25']);
            } else if((float)($data['hull_market_value']) > 500000 && (float)($data['hull_market_value']) <= 600000) {
                $data['hullRate'] = (float)($data['hull500000LessThan25']);
            } else {
                $data['hullRate'] = (float)($data['hull600000LessThan25']);
            }
        } else {
            if((float)($data['hull_market_value']) >= 0 && (float)($data['hull_market_value']) <= 25000){
                $data['hullRate'] = (float)($data['hull25000GreaterThan25']);
            } else if((float)($data['hull_market_value']) > 25000 && (float)($data['hull_market_value']) <= 50000) {
                $data['hullRate'] = (float)($data['hull50000GreaterThan25']);
            } else if((float)($data['hull_market_value']) > 50000 && (float)($data['hull_market_value']) <= 100000) {
                $data['hullRate'] = (float)($data['hull100000GreaterThan25']);
            } else if((float)($data['hull_market_value']) > 100000 && (float)($data['hull_market_value']) <= 150000) {
                $data['hullRate'] = (float)($data['hull150000GreaterThan25']);
            } else if((float)($data['hull_market_value']) > 150000 && (float)($data['hull_market_value']) <= 200000) {
                $data['hullRate'] = (float)($data['hull200000GreaterThan25']);
            } else if((float)($data['hull_market_value']) > 200000 && (float)($data['hull_market_value']) <= 250000) {
                $data['hullRate'] = (float)($data['hull250000GreaterThan25']);
            } else if((float)($data['hull_market_value']) > 250000 && (float)($data['hull_market_value']) <= 300000) {
                $data['hullRate'] = (float)($data['hull300000GreaterThan25']);
            } else if((float)($data['hull_market_value']) > 300000 && (float)($data['hull_market_value']) <= 400000) {
                $data['hullRate'] = (float)($data['hull350000GreaterThan25']);
            } else if((float)($data['hull_market_value']) > 400000 && (float)($data['hull_market_value']) <= 500000) {
                $data['hullRate'] = (float)($data['hull400000GreaterThan25']);
            } else if((float)($data['hull_market_value']) > 500000 && (float)($data['hull_market_value']) <= 600000) {
                $data['hullRate'] = (float)($data['hull500000GreaterThan25']);
            } else {
                $data['hullRate'] = (float)($data['hull600000GreaterThan25']);
            }
        }
        if(((float)($data['hull_market_value']) * (float)($data['hullRate'])/100)<500){
            $data['HullPremium'] = 500;
        }else {
            $data['HullPremium'] = ((float)($data['hull_market_value']) * (float)($data['hullRate'])/100);
        }
        $data['DingyTenderPremium'] = ((float)($data['dingy_value']) * (float)($data['hullRate'])/100);
        $data['TrailerPremium'] = ((float)($data['trailer_value']) * (float)($data['hullRate'])/100);
        $data['PropertyBasePremium'] = ((float)($data['TrailerPremium'])+ (float)($data['DingyTenderPremium'])+(float)($data['HullPremium']));
        if((float)($data['boat_age']>24)){
            $data['Age25Surcharge'] =  ((float)($data['PropertyBasePremium']) * (float)($data['DeductibleGreaterthan24'])/100);
        } else {
            $data['Age25Surcharge'] =  0;
        }
        $data['NavWaterSurchargePremium'] = $data['NavWaterSurchargeYN']?((float)($data['PropertyBasePremium'])*(float)($data['NavWaterSurcharge'])/100):0;
        $data['PortRiskCredit'] = $data['PortRiskYN']?((float)($data['PropertyBasePremium'])*(float)($data['PortRisk'])/100):0;
        $data['NavigationCredit'] = $data['NavigationCreditYN']?((float)($data['PropertyBasePremium'])*(float)($data['Navigation'])/100):0;
        $data['SuperiorRiskCredit'] = $data['SuperiorRiskCreditYN']?((float)($data['PropertyBasePremium'])*(float)($data['SuperiorRisk'])/100):0;
        $data['PropertySubTotal'] = ((float)($data['PropertyBasePremium'])+((float)($data['Age25Surcharge']) || 0)+((float)($data['NavWaterSurchargePremium']) || 0)) -  (((float)($data['PortRiskCredit']) || 0) + ((float)($data['SuperiorRiskCredit']) || 0) + ((float)($data['NavigationCredit']) || 0));
        $data['PropertySubTotalProRated'] = ((float)($data['ProRataFactor'])*(float)($data['PropertySubTotal']));
        $data['ProRataFactor'] = ((float)($data['ProRataDays'])/365);
        $data['LiabilityPremiumCost'] = (float)($data['LiabilityPremium1M']);
        $data['ExcessLiabilityPremium'] = $data[$data['excess_liability_coverage']] ;
        if((float)($data['DingyTenderPremium'])>0){
            $data['DingyLiability'] =  (float)($data['DingyLiabilityPremium']);
        } else {
            $data['DingyLiability'] =  0;
        }
        $data['PassengerPremiumCost'] = (float)($data['PassengerPremium']) * ((float)($data['certified_for_max_number_of_passengers']) || 0);
        $data['CrewOnBoatPremium'] = (((float)(isset($data['CrewInBoatCount']))?$data['CrewInBoatCount'] : 0)* ((float)(isset($data['CrewInBoat']))? $data['CrewInBoat']: 0));
        $data['CrewMembersinWaterPremium'] = (((float)(isset($data['CrewInWaterCount']))?$data['CrewInWaterCount']: 0) * ((float)(isset($data['CrewInWater']))?($data['CrewInWater']):0));
        $data['totalLiability'] = ((float)(isset($data['CrewMembersinWaterPremium']))?$data['CrewMembersinWaterPremium']:0) + ((float)(isset($data['CrewOnBoatPremium']))? $data['CrewOnBoatPremium']:0) + ((float)(isset($data['PassengerPremiumCost']))? $data['PassengerPremiumCost']:0) + ((float)(isset($data['DingyLiability']))?$data['DingyLiability']:0) + ((float)(isset($data['ExcessLiabilityPremium']))?$data['ExcessLiabilityPremium']:0) + ((float)(isset($data['LiabilityPremiumCost']))?$data['LiabilityPremiumCost']:0);
        if($data['FL-HISurchargeYN']){
            $data['FlHiSurchargePremium'] = ((float)($data['totalLiability'])*(float)($data['FL-HISurcharge']))/100;
        } else {
            $data['FlHiSurchargePremium'] = 0;
        }
        $data['LiabilitySubTotal'] = (((float)($data['FlHiSurchargePremium']) || 0) + ((float)($data['CrewMembersinWaterPremium']) || 0) + ((float)($data['CrewOnBoatPremium']) || 0) + ((float)($data['PassengerPremiumCost']) || 0) + ((float)($data['DingyLiability']) | 0) + ((float)($data['ExcessLiabilityPremium']) || 0) + ((float)($data['LiabilityPremiumCost']) || 0));
        $data['LiabilitySubTotalProRated'] = ((float)($data['ProRataFactor'])*(float)($data['LiabilitySubTotal']));
        $data['premiumTotalProRated'] = ((float)($data['PropertySubTotalProRated']) || 0)+((float)($data['LiabilitySubTotalProRated']) || 0);
        $data['groupProfessionalLiabilityPrice'] = ((float)($data["groupTotalAmount"]) || 0);

        $data['total'] = (((float)($data['premiumTotalProRated']) || 0)+((float)($data['groupProfessionalLiabilityPrice']) || 0)+((float)($data['padiFee']) || 0));
        $data['boat_age'] = date('YYYY') - $data['built_year'];
        $data['DateEffective'] = date('Y-m-d');

        $endDate = DateTime::createFromFormat('Y-m-d', $data['end_date']);
        $effectiveDate = DateTime::createFromFormat('Y-m-d', $data['DateEffective']);
        $data['ProRataDays'] = $endDate->diff($effectiveDate)->format("%d");
        $data['amount'] = (float)$data['total'];
    }

// Data Cleanup is done here
    private function cleanData(&$data){
        $this->logger->info("CLEAN DATA");
        $unsetVar = $this->unsetVariables;
        $this->logger->info("UNSET VARIABLES".print_r($unsetVar,true));
        for($i=0;$i< sizeof($unsetVar);$i++){
            $this->logger->info("CLEAN DATA FOR");
            if(isset($data[$unsetVar[$i]])){
                unset($data[$unsetVar[$i]]);
            }
        }
        $this->logger->info("CLEAN DATA END".print_r($data,true));
    }


}
