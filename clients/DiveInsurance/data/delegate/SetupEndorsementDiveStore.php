<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class SetupEndorsementDiveStore extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    
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


        $data['update_date'] =  date("Y-m-d H:i:s");

        $data['previous_groupCoverageSelect'] = $data['groupCoverageSelect'];
        $data['previous_groupExcessLiabilitySelect'] = $data['groupExcessLiabilitySelect'];
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
        $data['previous_groupPAORfee'] = $data['groupPAORfee'];
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
       $this->logger->info("Set UP Edorsement Dive Store - END",print_r($data,true));
       return $data;
    }
}
