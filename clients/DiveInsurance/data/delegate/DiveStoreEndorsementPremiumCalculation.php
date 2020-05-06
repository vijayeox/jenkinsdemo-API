<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class DiveStoreEndorsementPremiumCalculation extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }
    
    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Premium Calculation".print_r($data,true));

		$policy = array();
        if(is_string($data['previous_policy_data'])){
            $policy = json_decode($data['previous_policy_data'],true);
        } else {
            $policy = $data['previous_policy_data'];
        }
        $length = sizeof($policy) - 1;
        $policy =  $policy[$length];
      
        unset($data['increased_liability'],$data['new_auto_liability'],$data['paymentVerified'],$data['premiumFinanceSelect'],$data['finalAmountPayable'],$data['paymentOptions'],$data['chequeNumber'],$data['orderId']);

        $data['update_date'] = $policy['update_date'];
        if(isset($data['nonOwnedAutoLiabilityPL']) && isset($policy['previous_nonOwnedAutoLiabilityPL'])){
            if($policy['previous_nonOwnedAutoLiabilityPL'] == 'no' && $data['nonOwnedAutoLiabilityPL'] !='no'){
                $data['new_auto_liability'] = true;
            }
    	}
        
        if(isset($data['liabilityCoverageOption']) && isset($policy['previous_liabilityCoverageOption'])){
            if($policy['previous_liabilityCoverageOption'] == $data['liabilityCoverageOption']){
                $data['increased_liability_limit'] = false;
            } else {
                $selectCoverage = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND key = '".$data['liabilityCoverageOption']."' AND previous_key = '".$policy['previous_nonOwnedAutoLiabilityPL']."'";
                $this->logger->info("Executing Endorsement Rate Card Coverage - Dive Store".$selectCoverage);
                $resultCoverage = $persistenceService->selectQuery($selectCoverage);
                while ($resultCoverage->next()) {
                    $rate = $resultCoverage->current();
                    if(isset($rate['coverage'])){
                        $data['increased_liability'] = $rate['coverage'];
                    }
                    unset($rate);
                }
            }
        }
        
        return $data;
    }
}
