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
            'groupTaxPercentage','paymentVerified');
}

public function execute(array $data,Persistence $persistenceService)
{
        $this->logger->info("Executing Endorsement Setup - Dive Store".print_r($data,true));
        $endorsementGroupCoverage = array();
        $endorsementGroupLiability = array();
        $endorsementPropertyDeductibles = array();
        $endorsementExcessLiabilityCoverage = array();
        $endorsementNonOwnedAutoLiabilityPL = array();
        $endorsementLiabilityCoverageOption = array();
        $data['initiatedByCsr'] = false;
        $data['initiatedByUser'] = isset($data['initiatedByUser']) ? $data['initiatedByUser'] : false;
        if($data['initiatedByUser'] == false){
            $data['update_date'] =  date("Y-m-d H:i:s");
            $data['previous_groupTaxPercentage'] = $data['groupTaxPercentage'];
            $data['previous_groupTaxAmount'] = ($data['groupTaxAmount']==0)?$data['groupTaxAmount']:0.00;
            $data['previous_groupPadiFeeAmount'] = $data['groupPadiFeeAmount'];
            $data['previous_groupCoverageSelect'] = $data['groupCoverageSelect'];
            $data['previous_groupExcessLiabilitySelect'] = $data['groupExcessLiabilitySelect'];
            $data['previous_groupTotalAmount'] = $data['groupTotalAmount'];
            $data['previous_propertyDeductibles'] = $data['propertyDeductibles'];
            $data['previous_excessLiabilityCoverage'] = $data['excessLiabilityCoverage'];
            $data['previous_nonOwnedAutoLiabilityPL'] = $data['nonOwnedAutoLiabilityPL'];
            $data['previous_liabilityCoverageOption'] = $data['liabilityCoverageOption'];
            $data['previous_liabilityCoveragesTotalPL'] = $data['liabilityCoveragesTotalPL'];
            $data['previous_propertyCoveragesTotalPL'] = $data['propertyCoveragesTotalPL'];
            $data['previous_liabilityPropertyCoveragesTotalPL'] = $data['liabilityPropertyCoveragesTotalPL'];
            $data['previous_liabilityProRataPremium'] = $data['liabilityProRataPremium'];
            $data['previous_propertyProRataPremium'] = $data['propertyProRataPremium'];
            $data['previous_ProRataPremium'] = $data['ProRataPremium'];
            $data['previous_PropTax'] = $data['PropTax'];
            $data['previous_LiaTax'] = $data['LiaTax'];
            $data['previous_AddILocPremium'] = $data['AddILocPremium'];
            $data['previous_AddILocTax'] = $data['AddILocTax'];
            $data['previous_groupProfessionalLiability'] = $data['groupProfessionalLiability'];
            $data['previous_padiFeePL'] = $data['padiFeePL'];
            $data['previous_PropDeductibleCredit'] = $data['PropDeductibleCredit'];
            $data['previous_PAORFee'] = $data['PAORFee'];
            $data['previous_totalAmount'] = $data['totalAmount'];


            $data['previous_groupTaxAmount'] = $data['groupTaxAmount'];
            $data['previous_groupPadiFeeAmount'] = $data['groupPadiFeeAmount'];
            if(isset($data['groupPAORfee'])){
                $data['previous_groupPAORfee'] = $data['groupPAORfee'];
            }
            $data['previous_groupTotalAmount'] = $data['groupTotalAmount'];


            $data['update_date'] = $data['update_date'];        



            if(isset($data['previous_groupCoverageSelect'])){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_groupCoverageSelect']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Group Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $data['previous_groupCoverageSelect']){
                            $data['groupCoverageSelect'] = $data['previous_groupCoverageSelect'];
                        }
                        $endorsementGroupCoverage[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }
            if(isset($data['previous_groupExcessLiabilitySelect'])){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_groupExcessLiabilitySelect']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $data['previous_groupExcessLiabilitySelect']){
                            $data['groupExcessLiabilitySelect'] = $data['previous_groupExcessLiabilitySelect'];
                        }
                        $endorsementGroupLiability[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }
            if(isset($data['previous_propertyDeductibles'])){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_propertyDeductibles']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $data['previous_propertyDeductibles']){
                            $data['propertyDeductibles'] = $data['previous_propertyDeductibles'];
                        }
                        $endorsementPropertyDeductibles[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }
            if(isset($data['previous_excessLiabilityCoverage'])){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_excessLiabilityCoverage']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $data['previous_excessLiabilityCoverage']){
                            $data['excessLiabilityCoverage'] = $data['previous_excessLiabilityCoverage'];
                        }
                        $endorsementExcessLiabilityCoverage[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }
            if(isset($data['previous_nonOwnedAutoLiabilityPL'])){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_nonOwnedAutoLiabilityPL']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $data['previous_nonOwnedAutoLiabilityPL']){
                            $data['nonOwnedAutoLiabilityPL'] = $data['previous_nonOwnedAutoLiabilityPL'];
                        }
                        $endorsementNonOwnedAutoLiabilityPL[$rate['key']] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }
            if(isset($data['previous_liabilityCoverageOption'])){
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_liabilityCoverageOption']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['key'])){
                        if($rate['key'] == $data['previous_liabilityCoverageOption']){
                            $data['liabilityCoverageOption'] = $data['previous_liabilityCoverageOption'];
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
            $unsetOptions = $this->unsetOptions;
            for($i=0;$i< sizeof($unsetOptions);$i++){
                if(isset($data[$unsetOptions[$i]])){
                    unset($data[$unsetOptions[$i]]);
                }
            }
            $this->logger->info("Set UP Edorsement Dive Store - END",print_r($data,true));
        }
        return $data;
    }
}