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
        if(isset($data['liabilityCoverage'])){
            $data['excessLiability'] = $data['liabilityCoverage'];
        }
        $previousKey = $data['careerCoverage'];
        $premiumRateCardDetails[$data['careerCoverage']] = 0.00;
        
        if($data['product'] == 'Individual Professional Liability'){
            $data['previous_scuba'] = isset($data['scubaFit'])? $data['scubaFit']: '';
            $data['previous_equipmentLiability'] = isset($data['equipment'])?$data['equipment'] :'' ;
            $previousKeyExcessLiability = $data['excessLiability'];
            $premiumRateCardDetails[$data['excessLiability']] = 0.00;
            $previousKeyCylinder = $data['cylinder'];
            $premiumRateCardDetails[$data['cylinder']] = 0.00;
            $selectCylinder = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$previousKeyCylinder."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
            $this->logger->info("Executing Endorsement Rate Card Cylinder Query ".$selectCylinder);
            $resultCylinder = $persistenceService->selectQuery($selectCylinder);
            while ($resultCylinder->next()) {
                $rate = $resultCylinder->current();
                if(isset($rate['key'])){
                    if((!isset($data['cylinder']) || empty($data['cylinder']))&& $rate['key'] == $previousKeyCylinder){
                        $data['cylinder'] = array('value'=>$previousKeyCylinder,'label'=>$rate['coverage']);
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
                    if((!isset($data['excessLiability']) || empty($data['excessLiability'])) 
                        && $rate['key'] == $previousKeyExcessLiability){
                        $data['excessLiability'] = array('value'=>$previousKeyExcessLiability,'label'=>$rate['coverage']);
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
                if((!isset($data['careerCoverage']) || empty($data['careerCoverage'])) 
                    && $rate['key'] == $previousKey){
                    $data['careerCoverage'] = array('value'=>$previousKey,'label'=>$rate['coverage']);
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
            if(isset($data['paymentOptions'])){
                $data['paymentOptions'] = "";
            }
            if(isset($data['chequeNumber'])){
                $data['chequeNumber'] = "";
            }
            if(isset($data['chequeConsentFile'])){
                $data['chequeConsentFile'] = "";
            }
            if(isset($data['orderId'])){
                $data['orderId'] = "";
            }
            if(isset($data['transactionId'])){
                $data['transactionId'] = "";
            }
            if(isset($data['approved'])){
                $data['approved'] = "";
            }
            if(isset($data['endorsement_options'])){
                $data['endorsement_options'] = "";
            }
            if(isset($data['disableOptions'])){
                $data['disableOptions'] = "";
            }
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
