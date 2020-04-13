<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\UserContextTrait;

class EndorsementRatecard extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }
    
    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Executing Endorsement Rate Card initial data".json_encode($data));
        $privileges = $this->getPrivilege();
        $endorsementCoverages = array();
        $endorsementExcessLiability = array();
        $endorsementCylinder = array();
        if(!isset($data['update_date']) || empty($data['update_date']) ){
            $data['update_date'] =  date("Y-m-d");
        } 
        $date1 = date_create($data['start_date']);
        $date2 = date_create($data['update_date']);
        $diff = date_diff($date1,$date2);
        $dateDifference = $diff->invert;
        if($dateDifference == 1){
            $data['update_date'] = $data['start_date'];
        }
        if(isset($data['liabilityCoverageName'])){
            $data['careerCoverage'] = $data['liabilityCoverageName'];
        }
        if(isset($data['upgradeCareerCoverage']) && (!empty($data['upgradeCareerCoverage']))){
            $this->logger->info("STEP 1");
            if(!isset($data['previous_careerCoverage']) || !is_array($data['previous_careerCoverage'])){
                $this->logger->info("STEP 2");
                $data['previous_careerCoverage'] = array();
            }
            if(!is_array($data['upgradeCareerCoverage'])){
                $coverageOnCsrReview = json_decode($data['upgradeCareerCoverage'],true);
                $data['upgradeCareerCoverage'] = $coverageOnCsrReview;
            }
            $this->logger->info("Checking----------".json_encode($data));
            if($data['CSRReviewRequired'] == "" || $data['CSRReviewRequired'] == false || $data['CSRReviewRequired'] ==='false'){
                if(count($data['previous_careerCoverage'])>0){
                    foreach($data['previous_careerCoverage'] as $key => $value){
                        if($value['label'] != $data['upgradeCareerCoverage']['label']){
                            $data['previous_careerCoverage'][] = array('value' => $data['upgradeCareerCoverage']['value'],'label' =>$data['upgradeCareerCoverage']['label'],'update_date' => $data['update_date']);
                        }
                    }
                }
                $endorsementCoverages[$data['upgradeCareerCoverage']['value']] = $data['upgradeCareerCoverage']['label'];
                $premiumRateCardDetails[$data['upgradeCareerCoverage']['value']] = 0.00;
                $previousKey = $data['upgradeCareerCoverage']['value'];

            }else{
                $count = count($data['previous_careerCoverage']);
                $previousKey = $data['previous_careerCoverage'][$count - 1]['value'];            
            }
        } else {
            $previousKey = $data['careerCoverage'];
            $premiumRateCardDetails[$data['careerCoverage']] = 0.00;
        }
        
        if($data['product'] == 'Individual Professional Liability'){
            $data['previous_scuba'] = isset($data['scubaFit'])? $data['scubaFit']: '';
            $data['previous_equipmentLiability'] = isset($data['equipment'])?$data['equipment'] :'' ;
            if(isset($data['upgradeExcessLiability']) && (!empty($data['upgradeExcessLiability']))){
                $this->logger->info("STEP 1");
                if(!isset($data['previous_excessLiability']) || !is_array($data['previous_excessLiability'])){
                    $this->logger->info("STEP 2");
                    $data['previous_excessLiability'] = array();
                }
                if(!is_array($data['upgradeExcessLiability'])){
                    $coverageOnCsrReview = json_decode($data['upgradeExcessLiability'],true);
                    $data['upgradeExcessLiability'] = $coverageOnCsrReview;
                }
                $this->logger->info("Checking11----------".json_encode($data));
                if(($data['CSRReviewRequired'] == "" || $data['CSRReviewRequired'] == false || $data['CSRReviewRequired'] ==='false') && (!isset($privileges['MANAGE_POLICY_APPROVAL_WRITE']))){
                    if(count($data['previous_excessLiability'])>0){
                        foreach($data['previous_excessLiability'] as $key => $value){
                            if($value['label'] != $data['upgradeExcessLiability']['label']){
                                $data['previous_excessLiability'][] = array('value' => $data['upgradeExcessLiability']['value'],'label' =>$data['upgradeExcessLiability']['label'],'update_date' => $data['update_date']);
                            }
                        }
                    }
                    $endorsementExcessLiability[$data['upgradeExcessLiability']['value']] = $data['upgradeExcessLiability']['label'];
                    $premiumRateCardDetails[$data['upgradeExcessLiability']['value']] = 0.00;
                    $previousKeyExcessLiability = $data['upgradeExcessLiability']['value'];
                }
                // else{
                //     // 2nd endor from csr side
                //     // $previousKeyExcessLiability = 'excessLiabilityCoverageDeclined';
                //     $count = count($data['previous_careerCoverage']);
                //     $previousKey = $data['previous_careerCoverage'][$count - 1]['value'];  
                // }
            }else {
                $previousKeyExcessLiability = $data['excessLiability'];
                $premiumRateCardDetails[$data['excessLiability']] = 0.00;
            }
            if(isset($data['upgradecylinder']) && (!empty($data['upgradecylinder']))){
                if(!isset($data['previous_cylinder']) || !is_array($data['previous_cylinder'])){
                    $this->logger->info("STEP 2");
                    $data['previous_cylinder'] = array();
                }
                if(!is_array($data['upgradecylinder'])){
                    $coverageOnCsrReview = json_decode($data['upgradecylinder'],true);
                    $data['upgradecylinder'] = $coverageOnCsrReview;
                }
                $this->logger->info("Checking----------".json_encode($data));
                if($data['CSRReviewRequired'] == "" || $data['CSRReviewRequired'] == false || $data['CSRReviewRequired'] ==='false'){
                    if(count($data['previous_cylinder'])>0){
                        foreach($data['previous_cylinder'] as $key => $value){
                            if($value['label'] !=  $data['upgradecylinder']['label']){
                                $data['previous_cylinder'][] = array('value' => $data['upgradecylinder']['value'],'label' =>$data['upgradecylinder']['label'],'update_date' => $data['update_date']);
                            }
                        }
                    }
                    $premiumRateCardDetails[$data['upgradecylinder']['value']] = 0.00;
                    $previousKeyCylinder = $data['upgradecylinder']['value'];

                }else{
                    $count = count($data['previous_cylinder']);
                    if($count > 0){
                        $previousKeyCylinder = $data['previous_cylinder'][$count - 1]['value'];     
                    }     else{
                        $previousKeyCylinder = $data['cylinder'];
                    }    
                    
                        $premiumRateCardDetails[$previousKeyCylinder] = 0.00;  
                }
            }else {
                $previousKeyCylinder = $data['cylinder'];
                $premiumRateCardDetails[$data['cylinder']] = 0.00;
            }
            $selectCylinder = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$previousKeyCylinder."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
            $this->logger->info("Executing Endorsement Rate Card Cylinder Query ".$selectCylinder);
            $resultCylinder = $persistenceService->selectQuery($selectCylinder);
            while ($resultCylinder->next()) {
                $rate = $resultCylinder->current();
                if(isset($rate['key'])){
                    if((!isset($data['upgradecylinder']) || empty($data['upgradecylinder']))&& $rate['key'] == $previousKeyCylinder){
                        $data['upgradecylinder'] = array('value'=>$previousKeyCylinder,'label'=>$rate['coverage']);
                        if(isset($data['previous_cylinder'])){
                            if(is_string($data['previous_cylinder'])){
                                $data['previous_cylinder'] = array();
                                $data['previous_cylinder'][] = array('value' => $rate['key'] , 'label' => $rate['coverage'], 'update_date'=>$data['update_date']);
                            }
                        }
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
            $fromClause = "";
            $phWhereClause = "";
            if(isset($privileges['MANAGE_MY_POLICY_READ']) && $privileges['MANAGE_MY_POLICY_READ'] == true && isset($previousKeyExcessLiability)){
                $fromClause = ",(select distinct previous_key from premium_rate_card where `key` =  '".$previousKeyExcessLiability."' and is_upgrade=0  and product = '".$data['product']."' ) pkc";
                $phWhereClause = " and CAST(rc.previous_key as UNSIGNED)>= CAST(pkc.previous_key as UNSIGNED)";
            }
            $selectExcessLiability = "select rc.* from premium_rate_card rc $fromClause  WHERE product = '".$data['product']."' and is_upgrade = 0 and coverage_category='EXCESS_LIABILITY' and start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."' $phWhereClause order by CAST(rc.previous_key as UNSIGNED) DESC";
            $this->logger->info("Executing Endorsement Rate Card ExcessLiability Query ".$selectExcessLiability);
            $resultExcessLiability = $persistenceService->selectQuery($selectExcessLiability);
            while ($resultExcessLiability->next()) {
                $rate = $resultExcessLiability->current();
                if(isset($rate['key'])){
                    if((!isset($data['upgradeExcessLiability']) || empty($data['upgradeExcessLiability'])) 
                        && $rate['key'] == $previousKeyExcessLiability){
                        $data['upgradeExcessLiability'] = array('value'=>$previousKeyExcessLiability,'label'=>$rate['coverage']);
                    if(isset($data['previous_excessLiability'])){
                        if(is_string($data['previous_excessLiability'])){
                            $data['previous_excessLiability'] = array();
                            $data['previous_excessLiability'][] = array('value' => $rate['key'] , 'label' => $rate['coverage'], 'update_date'=>$data['update_date']);
                        }
                    }
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
            $selectEquipment = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['equipment']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
            $this->logger->info("Executing Endorsement Rate Card Equipment Query".$selectEquipment);
            $resultEquipment= $persistenceService->selectQuery($selectEquipment);
            if($resultEquipment->count() == 0){
                $premiumRateCardDetails[$data['equipment']] = 0;
            }
            while ($resultEquipment->next()) {
                $rate = $resultEquipment->current();
                if(isset($rate['key'])){
                    if(isset($rate['total'])){
                        $premiumRateCardDetails[$rate['key']] = $rate['total'];
                    } else {
                        $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                    }
                }
                unset($rate);
            }
            $selectScubafit = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['scubaFit']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
            $this->logger->info("Executing Endorsement Rate Card Scuba fit Query".$selectScubafit);
            $resultScubafit = $persistenceService->selectQuery($selectScubafit);
            if($resultScubafit->count() == 0){
                $premiumRateCardDetails[$data['scubaFit']] = 0;
            }
            while ($resultScubafit->next()) {
                $rate = $resultScubafit->current();
                if(isset($rate['key'])){
                    if(isset($rate['total'])){
                        $premiumRateCardDetails[$rate['key']] = $rate['total'];
                    } else {
                        $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                    }
                }
                unset($rate);
            }
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
                if(isset($data['previous_careerCoverage'])){
                    if(is_string($data['previous_careerCoverage'])){
                        $data['previous_careerCoverage'] = array();
                        $data['previous_careerCoverage'][] = array('value' => $rate['key'] , 'label' => $rate['coverage'], 'update_date'=>$data['update_date']);
                        }
                    }
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
