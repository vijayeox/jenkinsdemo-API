<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class SetupEndorsementDiveBoat extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    
    }

    public function execute(array $data,Persistence $persistenceService)
    {

        $this->logger->info("Executing Endorsement Setup - Dive Boat".print_r($data,true));
        $endorsementCoverage = array();
        $endorsementGroupCoverage = array();
        $endorsementGroupLiability = array();
        $data['update_date'] =  date("Y-m-d H:i:s");
        $data['previous_hull_market_value'] = $data['hull_market_value'];
        $data['previous_purchase_price_currency'] = $data['purchase_price_currency'];
        $data['previous_dingy_value'] = $data['dingy_value'];
        $data['previous_trailer_value'] =$data['trailer_value'];
        $data['previous_CrewInBoatCount'] = isset($data['CrewInBoatCount']) ? $data['CrewInBoatCount'] : 0;
        $data['previous_CrewInWaterCount'] = isset($data['CrewInWaterCount']) ? $data['CrewInWaterCount'] : 0;
        $data['previous_excess_liability_coverage'] = $data['excess_liability_coverage'];
        $data['previous_excessLiabilityLimit'] = $data['excessLiabilityLimit'];
        $data['previous_totalLiabilityLimit'] = $data['totalLiabilityLimit'];
        $data['previous_HullPremium'] = $data['HullPremium'];
        $data['previous_DingyTenderPremium'] = $data['DingyTenderPremium'];
        $data['previous_TrailerPremium'] = $data['TrailerPremium'];
        $data['previous_PropertyBasePremium'] = $data['PropertyBasePremium'];
        $data['previous_Age25Surcharge'] = $data['Age25Surcharge'];
        $data['previous_NavWaterSurchargeYN'] = $data['NavWaterSurchargeYN'];
        $data['previous_NavWaterSurchargePremium'] = $data['NavWaterSurchargePremium'];
        $data['previous_PortRiskYN'] = $data['PortRiskYN'];
        $data['previous_PortRiskCredit'] = $data['PortRiskCredit'];
        $data['previous_NavigationCreditYN'] = $data['NavigationCreditYN'];
        $data['previous_NavigationCredit'] = $data['NavigationCredit'];
        $data['previous_SuperiorRiskCreditYN'] = $data['SuperiorRiskCreditYN'];
        $data['previous_SuperiorRiskCredit'] = $data['SuperiorRiskCredit'];
        $data['previous_PropertySubTotal'] = $data['PropertySubTotal'];
        $data['previous_PropertySubTotalProRated'] = $data['PropertySubTotalProRated'];
      

        $data['previous_LiabilityPremiumCost'] = $data['LiabilityPremiumCost'];
        $data['previous_ExcessLiabilityPremium'] = $data['ExcessLiabilityPremium'];
        $data['previous_DingyLiability'] = $data['DingyLiability'];
        $data['previous_PassengerPremiumCost'] = $data['PassengerPremiumCost'];
        $data['previous_CrewOnBoatPremium'] = isset($data['CrewOnBoatPremium']) ? $data['CrewOnBoatPremium'] : 0;
        $data['previous_CrewMembersinWaterPremium'] = isset($data['CrewMembersinWaterPremium']) ? $data['CrewMembersinWaterPremium'] : 0;
        $data['prevoius_FL-HISurchargeYN'] = $data['FL-HISurchargeYN'];
        $data['previous_LiabilitySubTotal'] = $data['LiabilitySubTotal'];
        $data['previous_LiabilitySubTotalProRated'] = $data['LiabilitySubTotalProRated'];



        $data['previous_ProRataFactor'] = $data['ProRataFactor'];
        $data['previous_premiumTotalProRated'] = $data['premiumTotalProRated'];
        $data['previous_groupCoverageSelect'] = $data['groupCoverageSelect'];
        $data['previous_groupCoverage'] = isset($data['groupCoverage']) ? $data['groupCoverage'] : 0;
        $data['previous_groupExcessLiabilitySelect'] = $data['groupExcessLiabilitySelect'];
        $data['previous_groupTaxPercentage'] = $data['groupTaxPercentage'];
        $data['previous_groupTaxAmount'] = $data['groupTaxAmount'];
        $data['previous_groupPadiFeeAmount'] = $data['groupPadiFeeAmount'];
        $data['previous_groupPAORfee'] = isset($data['groupPAORfee']) ? $data['groupPAORfee'] : 0;
        $data['groupProfessionalLiabilityPrice'] = isset($data['groupProfessionalLiabilityPrice']) ? $data['groupProfessionalLiabilityPrice'] : 0;
        $data['previous_groupProfessionalLiabilityPrice'] = $data['groupProfessionalLiabilityPrice'];
        $data['previous_groupTotalAmount'] = $data['groupTotalAmount'];

    
        $data['previous_hullRate'] = $data['hullRate'];
        $data['previous_total'] = $data['total'];
        $data['update_date'] = $data['update_date'];        



        $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_excess_liability_coverage']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
            $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Boat".$selectCoverage);
            $resultCoverage = $persistenceService->selectQuery($selectCoverage);
            while ($resultCoverage->next()) {
                $rate = $resultCoverage->current();
                if(isset($rate['key'])){
                    if($rate['key'] == $data['previous_excess_liability_coverage']){
                        $data['excess_liability_coverage'] = $data['previous_excess_liability_coverage'];
                    }
                    $endorsementCoverage[$rate['key']] = $rate['coverage'];
                }
                unset($rate);
            }

        if(isset($data['previous_groupCoverageSelect'])){
            $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_groupCoverageSelect']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Group Coverage - Dive Boat".$selectCoverage);
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
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Boat".$selectCoverage);
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

        $data['endorsementCoverage'] = $endorsementCoverage;
        $data['endorsementGroupCoverage'] = $endorsementGroupCoverage;
        $data['endorsementGroupLiability'] = $endorsementGroupLiability;


       $this->logger->info("Set UP Edorsement Dive Boat - END",print_r($data,true));
       return $data;
    }
}
