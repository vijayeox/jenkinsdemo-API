<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\UserContextTrait;

class SetupEndorsement extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService) {
        $privileges = $this->getPrivilege();
        $this->logger->info("Executing Endorsement Setup".json_encode($data));
        $data['initiatedByUser'] = isset($data['initiatedByUser']) ? $data['initiatedByUser'] : false;
        if(!$data['initiatedByUser'] && $data['initiatedByUser'] != 'false'){
            $endorsementCoverage = array();
            $policy =  array();
            $update_date =  date("Y-m-d");
            $start_date = date($data['start_date']);
            if(isset($privileges['MANAGE_POLICY_APPROVAL_WRITE']) && 
                $privileges['MANAGE_POLICY_APPROVAL_WRITE'] == true){
                $data['initiatedByCsr'] = true;
            }else{
                $data['initiatedByCsr'] = false;
            }
            if($start_date  > $update_date){
                $policy['update_date'] = $data['update_date'] = $data['start_date'];
            }else{
                $policy['update_date'] = $data['update_date'] = $update_date;
            }
            if(isset($data['liabilityCoverage'])){
                $data['excessLiability'] = $data['liabilityCoverage'];
            }
            $data['previous_policy_data'] = isset($data['previous_policy_data']) ? $data['previous_policy_data'] : array();
            if(isset($data['single_limit'])){
                $policy['prevSingleLimit'] = $data['single_limit'];
            }
            if(isset($data['annual_aggregate'])){
                $policy['prevAnnualAggregate'] = $data['annual_aggregate'];
            }
            if(isset($data['careerCoverage'])){
                $policy['previous_careerCoverage'] = $data['careerCoverage'];
            }
            if(isset($data['tecRecEndorsment'])){
                $policy['previous_tecRecEndorsment'] = $data['tecRecEndorsment'];
            }
            if(isset($data['scubaFit'])){
                $policy['previous_scubaFit'] = $data['scubaFit'];
            }
            if(isset($data['cylinder'])){
                $policy['previous_cylinder'] = $data['cylinder'];
            }
            if(isset($data['equipment'])){
                $policy['previous_equipment'] = $data['equipment'];
            }
            if(isset($data['excessLiability'])){
                $policy['previous_excessLiability'] = $data['excessLiability'];
            }
            if(isset($data['excessLiabilityPrice'])){
                $policy['prevExcessLiabiltyPrice'] = $data['excessLiabilityPrice'];
            }
            if(isset($policy['previous_careerCoverage'])){
                $endorsementCoverages = array();
                $select = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['careerCoverage']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Query".$select);
                $result = $persistenceService->selectQuery($select);
                while ($result->next()) {
                    $rate = $result->current();
                    if(isset($rate['key'])){
                        if(isset($rate['total'])){
                            $premiumRateCardDetails[$rate['key']] = $rate['total'];
                        } else {
                            $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                        }
                        if($rate['key'] == $policy['previous_careerCoverage']){
                            $policy['previous_careerCoverageLabel'] = $rate['coverage'];
                            $endorsementCoverages[$rate['key']] = $rate['coverage'];
                            $premiumRateCardDetails[$rate['key']] = 0;
                            $data['careerCoveragePrice'] = 0;
                        } else {
                            $endorsementCoverages[$rate['key']] = $rate['coverage'];
                        }
                    }
                }
                $data['endorsementCoverage'] = $endorsementCoverages;
            }
            if(isset($policy['previous_cylinder'])){
                $endorsementCylinder = array();
                $selectCylinder = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$data['cylinder']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Cylinder Query ".$selectCylinder);
                $resultCylinder = $persistenceService->selectQuery($selectCylinder);
                while ($resultCylinder->next()) {
                    $rate = $resultCylinder->current();
                    if(isset($rate['key'])){
                        if(isset($rate['total'])){
                            $premiumRateCardDetails[$rate['key']] = $rate['total'];
                        } else {
                            $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                        }
                        if($rate['key'] == $policy['previous_cylinder']){
                            $policy['previous_cylinderLabel'] = $rate['coverage'];
                            $endorsementCylinder[$rate['key']] = $rate['coverage'];
                            $premiumRateCardDetails[$rate['key']] = 0;
                            $data['cylinderPrice'] = 0;
                        } else {
                            $endorsementCylinder[$rate['key']] = $rate['coverage'];
                        }
                    }
                    unset($rate);
                }
                $data['endorsementCylinder'] = $endorsementCylinder;
            }
            if(isset($policy['previous_excessLiability'])){
                $endorsementExcessLiability = array();
                $fromClause = "";
                $phWhereClause = "";
                $endorsementExcessLiability = array();
                if(isset($privileges['MANAGE_MY_POLICY_READ']) && $privileges['MANAGE_MY_POLICY_READ'] == true && isset($policy['previous_excessLiability'])){
                    $fromClause = ",(select distinct previous_key from premium_rate_card where `key` =  '".$policy['previous_excessLiability']."' and is_upgrade=0  and product = '".$data['product']."' ) pkc";
                    $phWhereClause = " and CAST(rc.previous_key as UNSIGNED)>= CAST(pkc.previous_key as UNSIGNED)";
                    $selectExcessLiability = "select rc.* from premium_rate_card rc $fromClause  WHERE product = '".$data['product']."' and is_upgrade = 0 and coverage_category='EXCESS_LIABILITY' and start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."' $phWhereClause order by CAST(rc.previous_key as UNSIGNED) DESC";
                } else {
                    $selectExcessLiability = "select rc.* from premium_rate_card rc premium_rate_card  WHERE product = '".$data['product']."' and is_upgrade = 0 and coverage_category='EXCESS_LIABILITY' and start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."' order by CAST(rc.previous_key as UNSIGNED) DESC";
                }
                $this->logger->info("Executing Endorsement Rate Card ExcessLiability Query ".$selectExcessLiability);
                $resultExcessLiability = $persistenceService->selectQuery($selectExcessLiability);
                while ($resultExcessLiability->next()) {
                    $rate = $resultExcessLiability->current();
                    if(isset($rate['key'])){
                        if(isset($rate['total'])){
                            $premiumRateCardDetails[$rate['key']] = $rate['total'];
                        } else {
                            $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                        }
                        if($rate['key'] == $policy['previous_excessLiability']){
                            $policy['previous_excessLiabilityLabel'] = $rate['coverage'];
                            $endorsementExcessLiability[$rate['key']] = $rate['coverage'];
                            // $premiumRateCardDetails[$rate['key']] = 0;
                            $data['excessLiabilityPrice'] = $rate['total'];
                        } else {
                            $endorsementExcessLiability[$rate['key']] = $rate['coverage'];
                        }
                    }
                    unset($rate);
                }
                $data['endorsementExcessLiability'] = $endorsementExcessLiability;
            }
            if(isset($policy['previous_equipment'])){
                $selectEquipment = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_equipment']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Equipment Query".$selectEquipment);
                $resultEquipment= $persistenceService->selectQuery($selectEquipment);
                if($resultEquipment->count() == 0){
                    $premiumRateCardDetails[$data['equipment']] = 0;
                    $data['equipmentPrice'] = 0;
                }
                while ($resultEquipment->next()) {
                    $rate = $resultEquipment->current();
                    if(isset($rate['key'])){
                        if(isset($rate['total'])){
                           
                            $premiumRateCardDetails[$rate['key']] = $rate['total'];
                            $data['equipmentPrice'] = 0;
                        } else {
                            
                            $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                        }
                    }
                    unset($rate);
                }
            }
            if(isset($policy['previous_tecRecEndorsment'])){
                $premiumRateCardDetails['withTecRecEndorsementForSelectionAbove'] = 0;
                $premiumRateCardDetails['tecRecDeclined'] = 0;
            }
            if(isset($policy['previous_scubaFit'])){
                $selectScubafit = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 1 AND previous_key = '".$policy['previous_scubaFit']."' AND start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."'";
                $this->logger->info("Executing Endorsement Rate Card Scuba fit Query".$selectScubafit);
                $resultScubafit = $persistenceService->selectQuery($selectScubafit);

                if($resultScubafit->count() == 0){
                    $premiumRateCardDetails[$data['scubaFit']] = 0;
                    $data['scubaFitPrice'] = 0;
                }
                while ($resultScubafit->next()) {
                    $rate = $resultScubafit->current();
                    if(isset($rate['key'])){
                        if(isset($rate['total'])){
                            $premiumRateCardDetails[$rate['key']] = $rate['total'];
                            $data['scubaFitPrice'] = 0; 
                        } else {
                            $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                        }
                    }
                    unset($rate);
                }
            }
            array_push($data['previous_policy_data'],$policy);
            $this->logger->info("Set UP Edorsement Dive Store - END",print_r($data,true));
            if(isset($data['paymentOptions'])){
                $data['paymentOptions'] = "";
            }
            if(isset($data['chequeNumber'])){
                $data['chequeNumber'] = "";
            }
            if(isset($data['chequeConsentFile'])){
                $data['chequeConsentFile'] = array();
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
            if(isset($data['endor_cylinderInstructor_attachments'])){
                $data['endor_cylinderInstructor_attachments']=array();
            }
            if(isset($data['endor_cylinderInspector_attachments'])){
                $data['endor_cylinderInspector_attachments']=array();
            }
            if(isset($data['endor_techRec_attachments'])){
                $data['endor_techRec_attachments']=array();
            }
            if(isset($data['endor_scubaFit_attachments'])){
                $data['endor_scubaFit_attachments']=array();
            }
            if(isset($data['endor_attachments'])){
                $data['endor_attachments']=array();
            }
            if(isset($premiumRateCardDetails)){
                $returnArray = array_merge($data,$premiumRateCardDetails);
            }
            $returnArray['initiatedByUser'] = true;
            return $returnArray;
        } else {
            $selectExcessLiability = "select rc.* from premium_rate_card rc WHERE product = '".$data['product']."' and is_upgrade = 0 and coverage_category='EXCESS_LIABILITY' and start_date <= '".$data['update_date']."' AND end_date >= '".$data['update_date']."' order by CAST(rc.previous_key as UNSIGNED) DESC";
            $this->logger->info("Executing Endorsement Rate Card ExcessLiability Query ".$selectExcessLiability);
            $resultExcessLiability = $persistenceService->selectQuery($selectExcessLiability);
            while ($resultExcessLiability->next()) {
                $rate = $resultExcessLiability->current();
                $endorsementExcessLiability[$rate['key']] = $rate['coverage'];
                unset($rate);
            }
            $data['endorsementExcessLiability'] = $endorsementExcessLiability;
        }
        unset($privileges);
        $this->logger->info("SETUP ENDOR".print_r($data,true));
        return $data;
    }
    protected function getRates($data,$persistenceService){
        $select = "Select * FROM premium_rate_card WHERE product ='".$data['product']."' AND start_date <= '".$data['start_date']."' AND is_upgrade = 0 AND end_date >= '".$data['start_date']."'";
        $selectTax = "Select state, coverage, percentage FROM state_tax WHERE product = '".$data['product']."' AND start_date <= '".$data['start_date']."' AND end_date >= '".$data['start_date']."'";
        $result = $persistenceService->selectQuery($select);
        $this->logger->info("Rate Card query -> $select");
        $stateTaxResult = $persistenceService->selectQuery($selectTax);
        while ($result->next()) {
            $rate = $result->current();
            if(isset($rate['key'])){
                if(isset($rate['total'])){
                    $premiumRateCardDetails[$rate['key']] = $rate['total'];
                } else {
                    if(isset($rate['tax'])){
                        $total = $rate['tax'] + $rate['premium'];
                        if(isset($rate['padi_fee'])){
                            $total = $rate['padi_fee'] + $total;
                        }
                        $premiumRateCardDetails[$rate['key']] = $total;
                    } else {
                        $premiumRateCardDetails[$rate['key']] = $rate['premium'];
                    }
                }
            }
            unset($rate);
        }
        $stateTaxData = [];
        while ($stateTaxResult->next()) {
            $rate = $stateTaxResult->current();
            array_push($stateTaxData, $rate);
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
        if(isset($stateTaxData)){
            $premiumRateCardDetails['stateTaxData'] = $stateTaxData;
        }
        if(isset($premiumRateCardDetails)){
            return $premiumRateCardDetails;
        } else {
            return $data;
        }
    }
}
