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
        $policy = array();
        $policy_data = array();
        $endorsementCoverage = array();
        $endorsementGroupCoverage = array();
        $endorsementGroupLiability = array();
        $data['previous_policy_data'] = isset($data['previous_policy_data']) ? $data['previous_policy_data'] : array();
        $data['update_date'] =  date("Y-m-d H:i:s");
        $policy['previous_excess_liability_coverage'] = $data['excess_liability_coverage'];
        $policy['previous_excessLiabilityLimit'] = $data['excessLiabilityLimit'];
        $policy['previous_totalLiabilityLimit'] = $data['totalLiabilityLimit'];
        $policy['previous_LiabilityPremiumCost'] = $data['LiabilityPremiumCost'];
        $policy['previous_ExcessLiabilityPremium'] = $data['ExcessLiabilityPremium'];
        $policy['previous_HullPremium'] = $data['HullPremium'];
        $policy['previous_DingyTenderPremium'] = $data['DingyTenderPremium'];
        $policy['previous_TrailerPremium'] = $data['TrailerPremium'];
        $policy['previous_CrewOnBoatPremium'] = isset($data['CrewOnBoatPremium']) ? $data['CrewOnBoatPremium'] : 0;
        $policy['previous_CrewMembersinWaterPremium'] = isset($data['CrewMembersinWaterPremium']) ? $data['CrewMembersinWaterPremium'] : 0;
        $policy['previous_hull_market_value'] = $data['hull_market_value'];
        $policy['previous_purchase_price_currency'] = $data['purchase_price_currency'];
        $policy['previous_dingy_value'] = $data['dingy_value'];
        $policy['previous_trailer_value'] =$data['trailer_value'];
        $policy['previous_CrewInBoatCount'] = isset($data['CrewInBoatCount']) ? $data['CrewInBoatCount'] : 0;
        $policy['previous_CrewInWaterCount'] = isset($data['CrewInWaterCount']) ? $data['CrewInWaterCount'] : 0;
        $policy['previous_PropertySubTotal'] = $data['PropertySubTotal'];
        $policy['previous_PropertySubTotalProRated'] = $data['PropertySubTotalProRated'];
        $policy['previous_LiabilitySubTotal'] = $data['LiabilitySubTotal'];
        $policy['previous_LiabilitySubTotalProRated'] = $data['LiabilitySubTotalProRated'];
        $policy['previous_premiumTotalProRated'] = $data['premiumTotalProRated'];
        $policy['previous_groupCoverageSelect'] = $data['groupCoverageSelect'];
        $policy['previous_groupCoverage'] = isset($data['groupCoverage']) ? $data['groupCoverage'] : 0;
        $policy['previous_groupExcessLiabilitySelect'] = $data['groupExcessLiabilitySelect'];
        $policy['groupProfessionalLiabilityPrice'] = isset($data['groupProfessionalLiabilityPrice']) ? $data['groupProfessionalLiabilityPrice'] : 0;
        $policy['previous_groupProfessionalLiabilityPrice'] = $data['groupProfessionalLiabilityPrice'];
        $policy['previous_groupTotalAmount'] = $data['groupTotalAmount'];
        $policy['previous_total'] = $data['total'];
        $policy['update_date'] = $data['update_date'];        



        $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_excess_liability_coverage']."' AND start_date <= '".$policy['update_date']."' AND end_date >= '".$policy['update_date']."'";
            $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Boat".$selectCoverage);
            $resultCoverage = $persistenceService->selectQuery($selectCoverage);
            while ($resultCoverage->next()) {
                $rate = $resultCoverage->current();
                if(isset($rate['key'])){
                    if($rate['key'] == $policy['previous_excess_liability_coverage']){
                            $data['update_excess_liability_coverage'] = array('value'=>$policy['previous_excess_liability_coverage'],'label'=>$rate['coverage']);
                    }
                    $endorsementCoverage[$rate['key']] = $rate['coverage'];
                }
                unset($rate);
            }

        if(isset($policy['previous_groupCoverageSelect'])){
            $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_groupCoverageSelect']."' AND start_date <= '".$policy['update_date']."' AND end_date >= '".$policy['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Group Coverage - Dive Boat".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $policy['previous_groupCoverageSelect']){
                            $data['update_groupCoverageSelect'] = array('value'=>$policy['previous_groupCoverageSelect'],'label'=>$rate['coverage']);
                        }
                        $endorsementGroupCoverage[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
         }


         if(isset($policy['previous_groupExcessLiabilitySelect'])){
            $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_groupExcessLiabilitySelect']."' AND start_date <= '".$policy['update_date']."' AND end_date >= '".$policy['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Boat".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $policy['previous_groupExcessLiabilitySelect']){
                            $data['update_groupExcessLiabilitySelect'] = array('value'=>$policy['previous_groupExcessLiabilitySelect'],'label'=>$rate['coverage']);
                        }
                        $endorsementGroupLiability[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
         }



        array_push($data['previous_policy_data'],$policy);


        $unsetOptions = $this->unsetOptions;
        for($i=0;$i< sizeof($unsetOptions);$i++){
            if(isset($data[$unsetOptions[$i]])){
                unset($data[$unsetOptions[$i]]);
            }
        }

        $data['endorsementCoverage'] = $endorsementCoverage;
        $data['endorsementGroupCoverage'] = $endorsementGroupCoverage;
        $data['endorsementGroupLiability'] = $endorsementGroupLiability;
       $this->logger->info("Set UP Edorsement Dive Boat - END",print_r($data,true));
       return $data;
    }
}
