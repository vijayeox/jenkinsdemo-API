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
        $this->logger->info("Executing Endorsement Setup - Dive Boat".json_encode($data));
        $data['previous_excess_liability_coverage'] = $data['excess_liability_coverage'];
        $data['previous_excessLiabilityLimit'] = $data['excessLiabilityLimit'];
        $data['previous_totalLiabilityLimit'] = $data['totalLiabilityLimit'];
        $data['previous_LiabilityPremiumCost'] = $data['LiabilityPremiumCost'];
        $data['previous_ExcessLiabilityPremium'] = $data['ExcessLiabilityPremium'];
        $data['previous_HullPremium'] = $data['HullPremium'];
        $data['previous_DingyTenderPremium'] = $data['DingyTenderPremium'];
        $data['previous_TrailerPremium'] = $data['TrailerPremium'];
        $data['previous_CrewOnBoatPremium'] = $data['CrewOnBoatPremium'];
        $data['previous_CrewMembersinWaterPremium'] = $data['CrewMembersinWaterPremium'];
        $data['previous_hull_market_value'] = $data['hull_market_value'];
        $data['previous_purchase_price_currency'] = $data['purchase_price_currency'];
        $data['previous_dingy_value'] = $data['dingy_value'];
        $data['previous_trailer_value'] =$data['trailer_value'];
        $data['previous_CrewInBoatCount'] = $data['CrewInBoatCount'];
        $data['previous_CrewInWaterCount'] = $data['CrewInWaterCount'];
        $data['previous_PropertySubTotal'] = $data['PropertySubTotal'];
        $data['previous_PropertySubTotalProRated'] = $data['PropertySubTotalProRated'];
        $data['previous_LiabilitySubTotal'] = $data['LiabilitySubTotal'];
        $data['previous_LiabilitySubTotalProRated'] = $data['LiabilitySubTotalProRated'];
        $data['previous_premiumTotalProRated'] = $data['premiumTotalProRated'];
        $data['previous_groupCoverageSelect'] = $data['groupCoverageSelect'];
        $data['previous_groupCoverage'] = $data['groupCoverage'];
        $data['previous_groupExcessLiabilitySelect'] = $data['groupExcessLiabilitySelect'];
        $data['previous_groupProfessionalLiabilityPrice'] = $data['groupProfessionalLiabilityPrice'];
        $data['previous_groupTotalAmount'] = $data['groupTotalAmount'];
        $data['previous_total'] = $data['total'];
        $this->cleanUp($data);
        return $data;
    }


    private function cleanUp(&$data){
      unset($data['excessLiabilityLimit'],
        $data['totalLiabilityLimit'],
        $data['LiabilityPremiumCost'],
        $data['ExcessLiabilityPremium'],
        $data['HullPremium'],
        $data['DingyTenderPremium'],
        $data['TrailerPremium'],
        $data['CrewOnBoatPremium'],
        $data['CrewMembersinWaterPremium'],
        $data['PropertySubTotal'],
        $data['PropertySubTotalProRated'],
        $data['LiabilitySubTotal'],
        $data['LiabilitySubTotalProRated'],
        $data['premiumTotalProRated'],
        $data['groupCoverageSelect'],
        $data['groupCoverage'],
        $data['groupExcessLiabilitySelect'],
        $data['csrApproved'],
        $data['quoteRequirement'],
        $data['quote_due_date'],
        $data['groupPadiFee'],
        $data['groupExcessLiability9M'],
        $data['groupExcessLiability4M'],
        $data['groupExcessLiability3M'],
        $data['groupExcessLiability2M'],
        $data['groupExcessLiability1M'],
        $data['groupCoverageMoreThan500000'],
        $data['groupCoverageMoreThan350000'],
        $data['groupCoverageMoreThan250000'],
        $data['groupCoverageMoreThan200000'],
        $data['groupCoverageMoreThan150000'],
        $data['groupCoverageMoreThan100000'],
        $data['groupCoverageMoreThan50000'],
        $data['groupCoverageMoreThan25000'],
        $data['groupCoverageMoreThan0'],
        $data['stateTaxData'],
        $data['SuperiorRisk'],
        $data['DingyLiabilityPremium'],
        $data['ProRataDays'],
        $data['DateEffective'],
        $data['excessLiabilityCoverage9000000'],
        $data['excessLiabilityCoverage4000000'],
        $data['excessLiabilityCoverage3000000'],
        $data['excessLiabilityCoverage2000000'],
        $data['excessLiabilityCoverage1000000'],
        $data['excessLiabilityCoverageDeclined'],
        $data['CrewInBoat'],
        $data['CrewInWater'],
        $data['PassengerPremium'],
        $data['DeductibleGreaterthan24'],
        $data['DeductibleLessthan25'],
        $data['Layup2'],
        $data['Layup1'],
        $data['LayupA'],
        $data['PortRisk'],
        $data['Navigation'],
        $data['NavWaterSurcharge'],
        $data['FL-HISurcharge'],
        $data['boat_age'],
        $data['total'],
        $data['padiFee'],
        $data['groupProfessionalLiabilityPrice'],
        $data['premiumTotalProRated'],
        $data['PropertySubTotalProRated'],
        $data['PropertySubTotal'],
        $data['SuperiorRiskCredit'],
        $data['NavigationCredit'],
        $data['PortRiskCredit'],
        $data['NavWaterSurchargePremium'],
        $data['Age25Surcharge'],
        $data['PropertyBasePremium'],
        $data['hullRate'],
        $data['ProRataFactor'],
        $data['LiabilityPremium1M'],
        $data['primaryLimit'],
        $data['DingyLiability'],
        $data['PassengerPremiumCost'],
        $data['totalLiability'],
        $data['FlHiSurchargePremium'],
        $data['hull_age'],
        $data['layupDeductible'],
        $data['layup_period'],
        $data['hull25000LessThan5'],
        $data['hull25000LessThan11'],
        $data['hull25000LessThan25'],
        $data['hull25000GreaterThan25'],
        $data['hull50000LessThan5'],
        $data['hull50000LessThan11'],
        $data['hull50000LessThan25'],
        $data['hull50000GreaterThan25'],
        $data['hull100000LessThan5'],
        $data['hull100000LessThan11'],
        $data['hull100000LessThan25'],
        $data['hull100000GreaterThan25'],
        $data['hull150000LessThan5'],
        $data['hull150000LessThan11'],
        $data['hull150000LessThan25'],
        $data['hull150000GreaterThan25'],
        $data['hull200000LessThan5'],
        $data['hull200000LessThan11'],
        $data['hull200000LessThan25'],
        $data['hull200000GreaterThan25'],
        $data['hull250000LessThan5'],
        $data['hull250000LessThan11'],
        $data['hull250000LessThan25'],
        $data['hull250000GreaterThan25'],
        $data['hull300000LessThan5'],
        $data['hull300000LessThan11'],
        $data['hull300000LessThan25'],
        $data['hull300000GreaterThan25'],
        $data['hull350000LessThan5'],
        $data['hull350000LessThan11'],
        $data['hull350000LessThan25'],
        $data['hull350000GreaterThan25'],
        $data['hull400000LessThan5'],
        $data['hull400000LessThan11'],
        $data['hull400000LessThan25'],
        $data['hull400000GreaterThan25'],
        $data['hull500000LessThan5'],
        $data['hull500000LessThan11'],
        $data['hull500000LessThan25'],
        $data['hull500000GreaterThan25'],
        $data['hull600000LessThan5'],
        $data['hull600000LessThan11'],
        $data['hull600000LessThan25'],
        $data['hull600000GreaterThan25'],
        $data['groupTotalAmount'],
        $data['groupPAORfee'],
        $data['groupPadiFeeAmount'],
        $data['groupTaxAmount'],
        $data['groupTaxPercentage'],
        );
    }
}
