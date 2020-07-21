<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class SetupEndorsementDiveStore extends AbstractAppDelegate
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
            'groupTaxPercentage','paymentVerified','premiumFinanceSelect','finalAmountPayable','paymentOptions','chequeNumber','orderId');
}

public function execute(array $data,Persistence $persistenceService)
{
        $this->logger->info("Executing Endorsement Setup - Dive Store".print_r($data,true));
        $endorsementPropertyDeductibles = array();
        $endorsementExcessLiabilityCoverage = array();
        $endorsementNonOwnedAutoLiabilityPL = array();
        $endorsementLiabilityCoverageOption = array();
        $data['initiatedByUser'] = isset($data['initiatedByUser']) ? $data['initiatedByUser'] : false;
        $data['upgradeStatus'] = true;
       if($data['initiatedByUser'] == false){
            $endorsementCoverage = array();
            $endorsementGroupCoverage = array();
            $endorsementGroupLiability = array();
            $policy =  array();
            $update_date =  date("Y-m-d");
            $start_date = date($data['start_date']);
            if($start_date  > $update_date){
                $policy['update_date'] = $data['update_date'] = $data['start_date'];
            }else{
                $policy['update_date'] = $data['update_date'] = $update_date;
            }
            $data['previous_policy_data'] = isset($data['previous_policy_data']) ? $data['previous_policy_data'] : array();
            $policy['previous_groupCoverage'] = isset($data['groupCoverage']) ? $data['groupCoverage'] : 0;
            $policy['previous_groupExcessLiabilitySelect'] = $groupExcessLiability = $data['groupExcessLiabilitySelect'];
            $policy['previous_groupTaxPercentage'] = isset($data['groupTaxPercentage']) ? $data['groupTaxPercentage'] : 0;
            $policy['previous_groupTaxAmount'] = isset($data['groupTaxAmount']) ? $data['groupTaxAmount'] : 0;
            $policy['previous_groupPadiFeeAmount'] = isset($data['groupPadiFeeAmount']) ? $data['groupPadiFeeAmount'] : 0;
            $policy['previous_groupPAORfee'] = isset($data['groupPAORfee']) ? $data['groupPAORfee'] : 0;
            $policy['groupProfessionalLiabilityPrice'] = isset($data['groupProfessionalLiabilityPrice']) ? $data['groupProfessionalLiabilityPrice'] : 0;
            $policy['previous_groupProfessionalLiabilityPrice'] = isset($data['groupProfessionalLiabilityPrice']) ? $data['groupProfessionalLiabilityPrice'] : 0;
            $policy['previous_groupTotalAmount'] = isset($data['groupTotalAmount']) ? $data['groupTotalAmount'] : 0;
            $policy['previous_groupProfessionalLiabilitySelect'] = $data['groupProfessionalLiabilitySelect'];
            $policy['previous_propertyDeductibles'] = $data['propertyDeductibles'];
            $policy['previous_excessLiabilityCoverage'] = $data['excessLiabilityCoverage'];
            $policy['previous_nonOwnedAutoLiabilityPL'] = $data['nonOwnedAutoLiabilityPL'];
            $policy['previous_liabilityCoverageOption'] = $data['liabilityCoverageOption'];
            $policy['previous_liabilityCoveragesTotalPL'] = $data['liabilityCoveragesTotalPL'];
            $policy['previous_propertyCoveragesTotalPL'] = isset($data['propertyCoveragesTotalPL'])?$data['propertyCoveragesTotalPL']:0;
            $policy['previous_liabilityPropertyCoveragesTotalPL'] = $data['liabilityPropertyCoveragesTotalPL'];
            $policy['previous_liabilityProRataPremium'] = isset($data['liabilityProRataPremium'])?$data['liabilityProRataPremium']:0;
            $policy['previous_propertyProRataPremium'] = isset($data['propertyProRataPremium'])?$data['propertyProRataPremium']:0;
            $policy['previous_ProRataPremium'] = $data['ProRataPremium'];
            $policy['previous_PropTax'] = $data['PropTax'];
            $policy['previous_LiaTax'] = $data['LiaTax'];
            $policy['previous_AddILocPremium'] = $data['AddILocPremium'];
            $policy['previous_AddILocTax'] = $data['AddILocTax'];
            $policy['previous_padiFeePL'] = $data['padiFeePL'];
            $policy['previous_annualAggregate'] = isset($data['annualAggregate']) ? $data['annualAggregate'] : 0;
            $policy['previous_combinedSingleLimit'] = isset($data['combinedSingleLimit']) ? $data['combinedSingleLimit'] : 0;
            $policy['previous_PropDeductibleCredit'] = $data['PropDeductibleCredit'];
            if(isset($data['PAORFee'])){
                $policy['previous_PAORFee'] = $data['PAORFee'];
            }
            $policy['previous_totalAmount'] = isset($data['totalAmount'])?$data['totalAmount']:0;
            if(isset($policy['previous_groupCoverageSelect'])){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_groupCoverageSelect']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Group Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $policy['previous_groupCoverageSelect']){
                            $data['groupCoverageSelect'] = $policy['previous_groupCoverageSelect'];
                        }
                        $endorsementGroupCoverage[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }
            if(isset($groupExcessLiability)){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$groupExcessLiability."' AND start_date <= '".$policy['update_date']."' AND end_date >= '".$policy['update_date']."'";
                    $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Boat".$selectCoverage);
                    $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                    while ($resultCoverage->next()) {
                        $rate = $resultCoverage->current();
                        if(isset($rate['key'])){
                            if($rate['key'] == $policy['previous_groupExcessLiabilitySelect']){
                                $data['groupExcessLiabilitySelect'] = $policy['previous_groupExcessLiabilitySelect'];
                            }
                            $endorsementGroupLiability[$rate['key']] = $rate['coverage'];
                        }
                        unset($rate);
                    }
            }
            if(isset($policy['previous_propertyDeductibles'])){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_propertyDeductibles']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $policy['previous_propertyDeductibles']){
                            $data['propertyDeductibles'] = $policy['previous_propertyDeductibles'];
                        }
                        $endorsementPropertyDeductibles[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }
            if(isset($policy['previous_excessLiabilityCoverage'])){
                $selectCoverage = "select rc.* from premium_rate_card rc WHERE product = '".$data['product']."' and is_upgrade = 0 and coverage_category='EXCESS_LIABILITY' and start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."' order by CAST(rc.previous_key as UNSIGNED) DESC";
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $policy['previous_excessLiabilityCoverage']){
                            $data['excessLiabilityCoverage'] = $policy['previous_excessLiabilityCoverage'];
                        }
                        $endorsementExcessLiabilityCoverage[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }
            if(isset($policy['previous_nonOwnedAutoLiabilityPL'])){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_nonOwnedAutoLiabilityPL']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $policy['previous_nonOwnedAutoLiabilityPL']){
                            $data['nonOwnedAutoLiabilityPL'] = $policy['previous_nonOwnedAutoLiabilityPL'];
                        }
                        $endorsementNonOwnedAutoLiabilityPL[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }
            if(isset($policy['previous_liabilityCoverageOption'])){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_liabilityCoverageOption']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $policy['previous_liabilityCoverageOption']){
                            $data['liabilityCoverageOption'] = $policy['previous_liabilityCoverageOption'];
                        }
                        $endorsementLiabilityCoverageOption[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }

            $data['endorsementGroupCoverage'] = $endorsementGroupCoverage;
            $data['endorsementGroupLiability'] = $endorsementGroupLiability;
            $data['endorsementPropertyDeductibles'] = $endorsementPropertyDeductibles;
            $data['endorsementExcessLiabilityCoverage'] = $endorsementExcessLiabilityCoverage;
            $data['endorsementNonOwnedAutoLiabilityPL'] = $endorsementNonOwnedAutoLiabilityPL;
            $data['endorsementLiabilityCoverageOption'] = $endorsementLiabilityCoverageOption;
            array_push($data['previous_policy_data'],$policy);
            $data['initial_combinedSingleLimit'] = $data['previous_policy_data'][0]['previous_combinedSingleLimit'];
            $data['initial_annualAggregate'] = $data['previous_policy_data'][0]['previous_annualAggregate'];
            $unsetOptions = $this->unsetOptions;
            for($i=0;$i< sizeof($unsetOptions);$i++){
                if(isset($data[$unsetOptions[$i]])){
                    unset($data[$unsetOptions[$i]]);
                }
            }
            $this->logger->info("Set UP Edorsement Dive Store - END",print_r($data,true));
        }
    
        if(isset($data['groupPL'])){
            if($data['groupPL'] != ""){
                foreach ($data['groupPL'] as $key => $value) {
                    if(!isset($value['effectiveDate'])){
                        $data['groupPL'][$key]['effectiveDate'] = date_format(date_create($data['start_date']),'m-d-Y');
                    }
                }
            }
        }
        if(isset($data['paymentOptions'])){
            unset($data['paymentOptions']);
        }
        if(isset($data['chequeNumber'])){
            unset($data['chequeNumber']);
        }
        if(isset($data['chequeConsentFile'])){
            unset($data['chequeConsentFile']);
        }
        if(isset($data['orderId'])){
            unset($data['orderId']);
        }
        if(isset($data['transactionId'])){
            unset($data['transactionId']);
        }
        return $data;
    }
}
