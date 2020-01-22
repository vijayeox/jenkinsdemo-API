<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class EndorsementRatecard extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }
    
    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Executing Endorsement Rate Card".json_encode($data));
        $endorsementCoverages = array();
        $endorsementExcessLiability = array();
        $endorsementCylinder = array();
        
        if(!isset($data['previous_scuba'])){
            $data['previous_scuba'] = isset($data['scubaFit'])? $data['scubaFit']: '';
        }
        if(!isset($data['previous_equipmentLiability'])){
            $data['previous_equipmentLiability'] = isset($data['equipment'])?$data['equipment'] :'' ;
        }
        
        if(isset($data['upgradeCareerCoverage']) && (!empty($data['upgradeCareerCoverage']))){
            if(!isset($data['previous_careerCoverage']) || !is_array($data['previous_careerCoverage'])){
                $data['previous_careerCoverage'] = array();
            }
            if(!is_array($data['upgradeCareerCoverage'])){
                $coverageOnCsrReview = json_decode($data['upgradeCareerCoverage'],true);
                $data['upgradeCareerCoverage'] = $coverageOnCsrReview;
            }
            if(!isset($data['approved'])){
                $data['previous_careerCoverage'][] = array($data['upgradeCareerCoverage']['label'] => $data['update_date']);
                foreach($data['previous_careerCoverage'] as $key => $value){
                        if($key != $data['upgradeCareerCoverage']['label'] ){
                            $data['previous_careerCoverage'] = $data['previous_careerCoverage'];
                        }
                }
            }
            $endorsementCoverages[$data['upgradeCareerCoverage']['value']] = $data['upgradeCareerCoverage']['label'];
            $premiumRateCardDetails[$data['upgradeCareerCoverage']['value']] = 0.00;
            $previousKey = $data['upgradeCareerCoverage']['value'];
        }else {
            $previousKey = $data['careerCoverage'];
            // $endorsementCoverages[$data['careerCoverage']] = $data['careerCoverageVal'];
            $premiumRateCardDetails[$data['careerCoverage']] = 0.00;
        }
        
        $data['previous_excessLiability'] = (!isset($data['upgradeExcessLiability']) || empty($data['upgradeExcessLiability'])) ?
        $data['excessLiability'] : $data['upgradeExcessLiability']['value'];
        $premiumRateCardDetails[$data['previous_excessLiability']] = 0.00;        
                
        $data['previous_cylinder'] = (!isset($data['upgradecylinder']) || empty($data['upgradecylinder'])) ? 
        $data['cylinder'] : $data['upgradecylinder']['value'];
        $premiumRateCardDetails[$data['previous_cylinder']] = 0.00;
        
        if(!isset($data['update_date'])){
            $data['update_date'] =  date("Y-m-d H:i:s");
        }
        
        $selectCylinder = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_cylinder']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
        $this->logger->info("Executing Endorsement Rate Card Cylinder Query ".$selectCylinder);
        $resultCylinder = $persistenceService->selectQuery($selectCylinder);
        while ($resultCylinder->next()) {
            $rate = $resultCylinder->current();
            if(isset($rate['key'])){
                if((!isset($data['upgradecylinder']) || empty($data['upgradecylinder']))&& $rate['key'] == $data['previous_cylinder']){
                    $data['upgradecylinder'] = array('value'=>$data['previous_cylinder'],'label'=>$rate['coverage']);
                }
                $endorsementCylinder[$rate['key']] = $rate['coverage'];
                if(isset($rate['total'])){
                    $premiumRateCardDetails[$rate['key']] = $rate['total'];
                } else {
                    $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                }
            }
            unset($rate);
        }
        
        $selectExcessLiability = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_excessLiability']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
        $this->logger->info("Executing Endorsement Rate Card ExcessLiability Query ".$selectExcessLiability);
        $resultExcessLiability = $persistenceService->selectQuery($selectExcessLiability);
        while ($resultExcessLiability->next()) {
            $rate = $resultExcessLiability->current();
            if(isset($rate['key'])){
                if((!isset($data['upgradeExcessLiability']) || empty($data['upgradeExcessLiability'])) 
                && $rate['key'] == $data['previous_excessLiability']){
                    $data['upgradeExcessLiability'] = array('value'=>$data['previous_excessLiability'],'label'=>$rate['coverage']);
                }
                $endorsementExcessLiability[$rate['key']] = $rate['coverage'];
                if(isset($rate['total'])){
                    $premiumRateCardDetails[$rate['key']] = $rate['total'];
                } else {
                    $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                }
            }
            unset($rate);
        }
        
        $select = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$previousKey."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
        $this->logger->info("Executing Endorsement Rate Card Query".$select);
        $result = $persistenceService->selectQuery($select);
        while ($result->next()) {
            $rate = $result->current();
            if(isset($rate['key'])){
                if((!isset($data['upgradeCareerCoverage']) || empty($data['upgradeCareerCoverage'])) 
                && $rate['key'] == $previousKey){
                    $data['upgradeCareerCoverage'] = array('value'=>$previousKey,'label'=>$rate['coverage']);
                }
                $endorsementCoverages[$rate['key']] = $rate['coverage'];
                if(isset($rate['total'])){
                    $premiumRateCardDetails[$rate['key']] = $rate['total'];
                } else {
                    $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                }
            }
            unset($rate);
        }
        
        $selectEquipment = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_equipmentLiability']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
        $this->logger->info("Executing Endorsement Rate Card Equipment Query".$selectEquipment);
        $resultEquipment= $persistenceService->selectQuery($selectEquipment);
        while ($resultEquipment->next()) {
            $rate = $resultEquipment->current();
            if(isset($rate['key'])){
                // $endorsementCoverages[$rate['key']] = $rate['coverage'];
                if(isset($rate['total'])){
                    $premiumRateCardDetails[$rate['key']] = $rate['total'];
                } else {
                    $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                }
            }
            unset($rate);
        }
        
        $selectScubafit = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['previous_scuba']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
        $this->logger->info("Executing Endorsement Rate Card Scuba fit Query".$selectScubafit);      
        $resultScubafit = $persistenceService->selectQuery($selectScubafit);
        while ($resultScubafit->next()) {
            $rate = $resultScubafit->current();
            if(isset($rate['key'])){
                // $endorsementCoverages[$rate['key']] = $rate['coverage'];
                if(isset($rate['total'])){
                    $premiumRateCardDetails[$rate['key']] = $rate['total'];
                } else {
                    $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                }
            }
            unset($rate);
        }
        foreach ($data as $key => $value) {
            if(is_string($value))
            {
                $result = json_decode($value);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$key] = $result;
                }
            }
        }
        if(isset($premiumRateCardDetails)){
            $data['endorsementCoverage'] = $endorsementCoverages;
            $data['endorsementExcessLiability'] = $endorsementExcessLiability;
            $data['endorsementCylinder'] = $endorsementCylinder;
            $this->logger->info("Data before merge".print_r($data,true)); 
            $this->logger->info("Premium rate card details".print_r($premiumRateCardDetails,true)); 
            $returnArray = array_merge($data,$premiumRateCardDetails);
            $this->logger->info("Data after merge".print_r($returnArray,true)); 
            return $returnArray;
        } else {
            return $data;
        }
    }
}
