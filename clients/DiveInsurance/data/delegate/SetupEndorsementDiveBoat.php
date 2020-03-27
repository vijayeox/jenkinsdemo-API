<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class SetupEndorsementDiveBoat extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
        $this->unsetOptions = array('excessLiabilityLimit',
            'totalLiabilityLimit',
            'LiabilityPremiumCost',
            'ExcessLiabilityPremium',
            'HullPremium',
            'DingyTenderPremium',
            'TrailerPremium',
            'CrewOnBoatPremium',
            'CrewMembersinWaterPremium',
            'PropertySubTotal',
            'PropertySubTotalProRated',
            'LiabilitySubTotal',
            'LiabilitySubTotalProRated',
            'premiumTotalProRated',
            'groupCoverage',
            'csrApproved',
            'quoteRequirement',
            'quote_due_date',
            'groupPadiFee',
            'groupExcessLiability9M',
            'groupExcessLiability4M',
            'groupExcessLiability3M',
            'groupExcessLiability2M',
            'groupExcessLiability1M',
            'groupCoverageMoreThan500000',
            'groupCoverageMoreThan350000',
            'groupCoverageMoreThan250000',
            'groupCoverageMoreThan200000',
            'groupCoverageMoreThan150000',
            'groupCoverageMoreThan100000',
            'groupCoverageMoreThan50000',
            'groupCoverageMoreThan25000',
            'groupCoverageMoreThan0',
            'stateTaxData',
            'SuperiorRisk',
            'DingyLiabilityPremium',
            'ProRataDays',
            'DateEffective',
            'excessLiabilityCoverage9000000',
            'excessLiabilityCoverage4000000',
            'excessLiabilityCoverage3000000',
            'excessLiabilityCoverage2000000',
            'excessLiabilityCoverage1000000',
            'excessLiabilityCoverageDeclined',
            'CrewInBoat',
            'CrewInWater',
            'PassengerPremium',
            'DeductibleGreaterthan24',
            'DeductibleLessthan25',
            'Layup2',
            'Layup1',
            'LayupA',
            'PortRisk',
            'Navigation',
            'NavWaterSurcharge',
            'FL-HISurcharge',
            'boat_age',
            'total',
            'padiFee',
            'groupProfessionalLiabilityPrice',
            'premiumTotalProRated',
            'PropertySubTotalProRated',
            'PropertySubTotal',
            'SuperiorRiskCredit',
            'NavigationCredit',
            'PortRiskCredit',
            'NavWaterSurchargePremium',
            'Age25Surcharge',
            'PropertyBasePremium',
            'hullRate',
            'ProRataFactor',
            'LiabilityPremium1M',
            'primaryLimit',
            'DingyLiability',
            'PassengerPremiumCost',
            'totalLiability',
            'FlHiSurchargePremium',
            'hull_age',
            'layupDeductible',
            'layup_period',
            'hull25000LessThan5',
            'hull25000LessThan11',
            'hull25000LessThan25',
            'hull25000GreaterThan25',
            'hull50000LessThan5',
            'hull50000LessThan11',
            'hull50000LessThan25',
            'hull50000GreaterThan25',
            'hull100000LessThan5',
            'hull100000LessThan11',
            'hull100000LessThan25',
            'hull100000GreaterThan25',
            'hull150000LessThan5',
            'hull150000LessThan11',
            'hull150000LessThan25',
            'hull150000GreaterThan25',
            'hull200000LessThan5',
            'hull200000LessThan11',
            'hull200000LessThan25',
            'hull200000GreaterThan25',
            'hull250000LessThan5',
            'hull250000LessThan11',
            'hull250000LessThan25',
            'hull250000GreaterThan25',
            'hull300000LessThan5',
            'hull300000LessThan11',
            'hull300000LessThan25',
            'hull300000GreaterThan25',
            'hull350000LessThan5',
            'hull350000LessThan11',
            'hull350000LessThan25',
            'hull350000GreaterThan25',
            'hull400000LessThan5',
            'hull400000LessThan11',
            'hull400000LessThan25',
            'hull400000GreaterThan25',
            'hull500000LessThan5',
            'hull500000LessThan11',
            'hull500000LessThan25',
            'hull500000GreaterThan25',
            'hull600000LessThan5',
            'hull600000LessThan11',
            'hull600000LessThan25',
            'hull600000GreaterThan25',
            'groupTotalAmount',
            'groupPAORfee',
            'groupPadiFeeAmount',
            'groupTaxAmount',
            'groupTaxPercentage');
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Executing Endorsement Setup - Dive Boat".print_r($data,true));
        $endorsementCoverage = array();
        $endorsementGroupCoverage = array();
        $endorsementGroupLiability = array();
        $policy =  array();
        $data['initiatedByCsr'] = false;
        $data['previous_policy_data'] = isset($data['previous_policy_data']) ? $data['previous_policy_data'] : array();
        $policy['update_date'] =  date("Y-m-d");
        $policy['previous_hull_market_value'] = isset($data['hull_market_value']) ? $data['hull_market_value'] : 0;
        $policy['previous_hull_deductible'] = isset($data['hull_deductible']) ? $data['hull_deductible'] : 0;
        $policy['previous_purchase_price_currency'] = isset($data['purchase_price_currency']) ? $data['purchase_price_currency'] : 0;
        $policy['previous_dingy_value'] = isset($data['dingy_value']) ? $data['dingy_value'] : 0;
        $policy['previous_trailer_value'] = isset($data['trailer_value']) ? $data['trailer_value'] : 0;
        $policy['previous_CrewInBoatCount'] = isset($data['CrewInBoatCount']) ? $data['CrewInBoatCount'] : 0;
        $policy['previous_CrewInWaterCount'] = isset($data['CrewInWaterCount']) ? $data['CrewInWaterCount'] : 0;
        $policy['previous_certified_for_max_number_of_passengers'] = isset($data['certified_for_max_number_of_passengers']) ? $data['certified_for_max_number_of_passengers'] : 0;
        $policy['previous_excess_liability_coverage'] = $data['excess_liability_coverage'];
        $policy['previous_excessLiabilityLimit'] = $data['excess_liability_coverage'];
        $policy['previous_totalLiabilityLimit'] = isset($data['totalLiabilityLimit'])?$data['totalLiabilityLimit']:0;
        $policy['previous_HullPremium'] = isset($data['HullPremium']) ? $data['HullPremium'] : 0;
        $policy['previous_DingyTenderPremium'] = isset($data['DingyTenderPremium']) ? $data['DingyTenderPremium'] : 0;
        $policy['previous_TrailerPremium'] = isset($data['TrailerPremium']) ? $data['TrailerPremium'] : 0;
        $policy['previous_PropertyBasePremium'] = isset($data['PropertyBasePremium']) ? $data['PropertyBasePremium'] : 0;
        $policy['previous_Age25Surcharge'] = $data['Age25Surcharge'];
        $policy['previous_NavWaterSurchargeYN'] = $data['NavWaterSurchargeYN'];
        $policy['previous_NavWaterSurchargePremium'] = $data['NavWaterSurchargePremium'];
        $policy['previous_PortRiskYN'] = $data['PortRiskYN'];
        $policy['previous_PortRiskCredit'] = $data['PortRiskCredit'];
        $policy['previous_NavigationCreditYN'] = $data['NavigationCreditYN'];
        $policy['previous_NavigationCredit'] = $data['NavigationCredit'];
        $policy['previous_SuperiorRiskCreditYN'] = $data['SuperiorRiskCreditYN'];
        $policy['previous_SuperiorRiskCredit'] = $data['SuperiorRiskCredit'];
        $policy['previous_PropertySubTotal'] = $data['PropertySubTotal'];
        $policy['previous_PropertySubTotalProRated'] = $data['PropertySubTotalProRated'];
    

        $policy['previous_LiabilityPremiumCost'] = $data['LiabilityPremiumCost'];
        $policy['previous_ExcessLiabilityPremium'] = $data['ExcessLiabilityPremium'];
        $policy['previous_DingyLiability'] = $data['DingyLiability'];
        $policy['previous_PassengerPremiumCost'] = $data['PassengerPremiumCost'];
        $policy['previous_CrewOnBoatPremium'] = isset($data['CrewOnBoatPremium']) ? $data['CrewOnBoatPremium'] : 0;
        $policy['previous_CrewMembersinWaterPremium'] = isset($data['CrewMembersinWaterPremium']) ? $data['CrewMembersinWaterPremium'] : 0;
        $policy['prevoius_FL-HISurchargeYN'] = $data['FL-HISurchargeYN'];
        $policy['previous_LiabilitySubTotal'] = $data['LiabilitySubTotal'];
        $policy['previous_LiabilitySubTotalProRated'] = $data['LiabilitySubTotalProRated'];



        $policy['previous_ProRataFactor'] = $data['ProRataFactor'];
        $policy['previous_premiumTotalProRated'] = $data['premiumTotalProRated'];
        $policy['previous_groupCoverageSelect'] = $data['groupCoverageSelect'];
        $policy['previous_groupCoverage'] = isset($data['groupCoverage']) ? $data['groupCoverage'] : 0;
        $policy['previous_groupExcessLiabilitySelect'] = $data['groupExcessLiabilitySelect'];
        $policy['previous_groupTaxPercentage'] = isset($data['groupTaxPercentage']) ? $data['groupTaxPercentage'] : 0;
        $policy['previous_groupTaxAmount'] = isset($data['groupTaxAmount']) ? $data['groupTaxAmount'] : 0;
        $policy['previous_groupPadiFeeAmount'] = isset($data['groupPadiFeeAmount']) ? $data['groupPadiFeeAmount'] : 0;
        $policy['previous_groupPAORfee'] = isset($data['groupPAORfee']) ? $data['groupPAORfee'] : 0;
        $policy['groupProfessionalLiabilityPrice'] = isset($data['groupProfessionalLiabilityPrice']) ? $data['groupProfessionalLiabilityPrice'] : 0;
        $policy['previous_groupProfessionalLiabilityPrice'] = isset($data['groupProfessionalLiabilityPrice']) ? $data['groupProfessionalLiabilityPrice'] : 0;
        $policy['previous_groupTotalAmount'] = isset($data['groupTotalAmount']) ? $data['groupTotalAmount'] : 0;

    
        $policy['previous_hullRate'] = $data['hullRate'];
        $policy['previous_total'] = $data['total'];
        // $policy['update_date'] = $data['update_date'];        


        if(!isset($data['previous_excess_liability_coverage'])){
            $data['previous_excess_liability_coverage'] = 'coverageNoneSelected';
        }


        $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_excess_liability_coverage']."' AND start_date <= '".$policy['update_date']."' AND end_date >= '".$policy['update_date']."'";
            $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Boat".$selectCoverage);
            $resultCoverage = $persistenceService->selectQuery($selectCoverage);
            while ($resultCoverage->next()) {
                $rate = $resultCoverage->current();
                if(isset($rate['key'])){
                    if($rate['key'] == $policy['previous_excess_liability_coverage']){
                        if($data['previous_excess_liability_coverage'] == 'coverageNoneSelected'){
                            $data['previous_excess_liability_coverage'] = '';
                        }
                        $data['excess_liability_coverage'] = $policy['previous_excess_liability_coverage'];
                    }
                    $endorsementCoverage[$rate['key']] = $rate['coverage'];
                }
                unset($rate);
            }


        if(!isset($data['previous_groupCoverageSelect'])){
            $data['previous_groupCoverageSelect'] = 'groupCoverageNoneSelected';
        }


        $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_groupCoverageSelect']."' AND start_date <= '".$policy['update_date']."' AND end_date >= '".$policy['update_date']."'";
            $this->logger->info("Executing Endorsement Rate Card Group Coverage - Dive Boat".$selectCoverage);
            $resultCoverage = $persistenceService->selectQuery($selectCoverage);
            while ($resultCoverage->next()) {
                $rate = $resultCoverage->current();
                if(isset($rate['key'])){
                    if($rate['key'] == $policy['previous_groupCoverageSelect']){
                        if($data['previous_groupCoverageSelect'] == 'groupCoverageNoneSelected'){
                                $data['previous_groupCoverageSelect'] = '';
                        }
                        $data['groupCoverageSelect'] = $policy['previous_groupCoverageSelect'];
                    }
                    $endorsementGroupCoverage[$rate['key']] = $rate['coverage'];
                }
                unset($rate);
            }
       

         if(!isset($data['previous_groupExcessLiabilitySelect'])){
            $data['previous_groupExcessLiabilitySelect'] = 'groupLiabilityNoneSelected';
         }


         // if(isset($data['previous_groupExcessLiabilitySelect'])){
            $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_groupExcessLiabilitySelect']."' AND start_date <= '".$policy['update_date']."' AND end_date >= '".$policy['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Boat".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $policy['previous_groupExcessLiabilitySelect']){
                            if($data['previous_groupExcessLiabilitySelect'] == 'groupCoverageNoneSelected'){
                                $data['previous_groupExcessLiabilitySelect'] = '';
                            }
                            $data['groupExcessLiabilitySelect'] = $policy['previous_groupExcessLiabilitySelect'];
                        }
                        $endorsementGroupLiability[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
         // }
        array_push($data['previous_policy_data'],$policy);

        $this->logger->info("Endorsement CleanUp Setup - Dive Boat");
        $unsetOptions = $this->unsetOptions;
        for($i=0;$i< sizeof($unsetOptions);$i++){
            if(isset($data[$unsetOptions[$i]])){
                unset($data[$unsetOptions[$i]]);
            }
        }
        if(!isset($data['initiatedByCsr']) || !$data['initiatedByCsr']){
            unset($data['endorsement_options']);
        }
        $data['endorsementCoverage'] = $endorsementCoverage;
        $data['endorsementGroupCoverage'] = $endorsementGroupCoverage;
        $data['endorsementGroupLiability'] = $endorsementGroupLiability;


       $this->logger->info("Set UP Edorsement Dive Boat - END",print_r($data,true));
       return $data;
    }
}
