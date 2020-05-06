<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class DiveBoatEndorsementPremiumCalculation extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }
    
    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Premium Calculation".print_r($data,true));

		$policy = array();
        $policy = is_string($data['previous_policy_data']) ? json_decode($data['previous_policy_data'],true) : $data['previous_policy_data'];
        $length = sizeof($policy) - 1;
        $policy =  $policy[$length];
        $upgrade = array();
        unset($data['increased_hullValue'],$data['increased_deductible'],$data['increased_hullPremium'],$data['decreased_hullValue'],$data['decreased_hullPremium'],$data['increased_dinghyValue'],$data['increased_dinghyPremium'],$data['decreased_dinghyValue'],$data['decreased_dinghyPremium'],$data['increased_trailerValue'],$data['increased_trailerPremium'],$data['decreased_trailerValue'],$data['decreased_trailerPremium'],$data['increased_totalLiabilityLimitValue'],$data['decreased_totalLiabilityLimitValue'],$data['increased_passengers'],$data['decreased_passengers'],$data['increased_crewInBoat'],$data['decreased_crewInBoat'],$data['increased_crewInWater'],$data['decreased_crewInWater']);

        $data['update_date'] = $policy['update_date'];
        
        if(($policy['previous_address1'] != $data['address1']  || $policy['previous_address2'] != $data['address2'] || $policy['previous_country'] != $data['country'] || $policy['previous_city'] != $data['city'] || $policy['previous_state'] != $data['state'] || $policy['previous_zip'] != $data['zip']) && ($data['basameAsMailingAddress'] == true || $data['basameAsMailingAddress'] == 'true')){
            $data['generatePersonalInfo'] = true;
        }


        if(isset($data['hull_market_value'])){
	        $hull_value = (float)$data['hull_market_value'] - (float)$policy['previous_hull_market_value'];
	        if($hull_value > 0){
	        	$data['increased_hullValue'] =  $hull_value;
	        	$data['increased_deductible'] = $data['hull_deductible'];
	        	$data['increased_hullPremium'] = (float)$data['HullPremium'] - (float)$policy['previous_HullPremium'];
	        }else if($hull_value < 0){
	        	$data['decreased_hullValue'] = (float)$policy['previous_hull_market_value'] - (float)$data['hull_market_value'];
	        	$data['decreased_hullPremium'] = (float)$policy['previous_HullPremium'] - (float)$data['HullPremium'];
	        }
    	}
        
    	if(isset($data['dingy_value'])){
	        $dinghy_value = (float)$data['dingy_value'] - (float)$policy['previous_dingy_value'];
	        if($dinghy_value > 0){
	        	$data['increased_dinghyValue'] =  $dinghy_value;
	        	$data['increased_dinghyPremium'] = (float)$data['DingyTenderPremium'] - (float)$policy['previous_DingyTenderPremium'];
	        }else if($dinghy_value < 0){
	        	$data['decreased_dinghyValue'] = (float)$policy['previous_dingy_value'] - (float)$data['dingy_value'];
	        	$data['decreased_dinghyPremium'] = (float)$policy['previous_DingyTenderPremium'] - (float)$data['DingyTenderPremium'];
	        }
    	}

    	if(isset($data['trailer_value'])){
	        $trailer_value = (float)$data['trailer_value'] - (float)$policy['previous_trailer_value'];
	        if($trailer_value > 0){
	        	$data['increased_trailerValue'] =  $trailer_value;
	        	$data['increased_trailerPremium'] = $data['TrailerPremium'] - $policy['previous_TrailerPremium'];
	        }else if($trailer_value < 0){
	        	$data['decreased_trailerValue'] = (float)$policy['previous_trailer_value'] - (float)$data['trailer_value'];
	        	$data['decreased_trailerPremium'] = $policy['previous_TrailerPremium'] - $data['TrailerPremium'];
	        }
        }

        if(isset($data['totalLiabilityLimit'])){
            $totalLiabilityLimit = (float)$data['totalLiabilityLimit'] - (float)$policy['previous_totalLiabilityLimit'];
        	if($totalLiabilityLimit > 0){
	        	$data['increased_totalLiabilityLimitValue'] =  $totalLiabilityLimit;
	        }else if($totalLiabilityLimit < 0){
	        	$data['decreased_totalLiabilityLimitValue'] = (float)$policy['previous_totalLiabilityLimit'] - (float)$data['totalLiabilityLimit'];
	        }
        }

        if(isset($data['certified_for_max_number_of_passengers'])){
        	$passengers = (float)$data['certified_for_max_number_of_passengers'] - (float)$policy['previous_certified_for_max_number_of_passengers'];
        	if($passengers > 0){
        		$data['increased_passengers'] = $passengers;
        	}else if($passengers < 0){
        		$data['decreased_passengers'] = (float)$policy['previous_certified_for_max_number_of_passengers'] - (float)$data['certified_for_max_number_of_passengers'];
        	}
        }


        if(isset($data['CrewInBoatCount'])){
        	$crewInBoat = (float)$data['CrewInBoatCount'] - (float)$policy['previous_CrewInBoatCount'];
        	if($crewInBoat > 0){
        		$data['increased_crewInBoat'] = $crewInBoat;
        		$data['increased_crewInBoatPremium'] = (float)$data['CrewOnBoatPremium'] - (float)$policy['previous_CrewOnBoatPremium'];
        	}else if($crewInBoat < 0){
        		$data['decreased_crewInBoat'] = (float)$policy['previous_CrewInBoatCount'] - (float)$data['CrewInBoatCount'];
        		$data['decreased_crewInBoatPremium'] = (float)$policy['previous_CrewOnBoatPremium'] - (float)$data['CrewOnBoatPremium'];
        	}
        }

        if(isset($data['CrewInWaterCount'])){
        	$crewInWater = (float)$data['CrewInWaterCount'] - (float)$policy['previous_CrewInWaterCount'];
        	if($crewInWater > 0){
        		$data['increased_crewInWater'] = $crewInWater;
        		$data['increased_crewInWaterPremium'] = (float)$data['CrewMembersinWaterPremium'] - (float)$policy['previous_CrewMembersinWaterPremium'];
        	}else if($crewInWater < 0){
        		$data['decreased_crewInWater'] = (float)$policy['previous_CrewInWaterCount'] - (float)$data['CrewInWaterCount'];
        		$data['decreased_crewInWaterPremium'] = (float)$policy['previous_CrewMembersinWaterPremium'] - (float)$data['CrewMembersinWaterPremium'];
        	}
        }
        return $data;
    }
}
