<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\UserContextTrait;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\AppDelegate\WorkflowTrait;
require_once __DIR__."/PolicyDocument.php";

class GenerateReport extends PolicyDocument {
    use WorkflowTrait;
    protected $type;
    protected $carrierTemplateTypeMapping = array(
        "individualProfessionalLiability" => array(
            "type" => "excel",
            "template" => "Phly_IL.xlsx"
        ),
        "groupProfessionalLiability" => array(
            "type" => "excel",
            "template" => "Phly_Group.xlsx"
        ),
        "diveStore" => array(
            "type" => "excel",
            "template" => "Phly_GL.xlsx"
        ),
        "diveStoreProperty" => array(
            "type" => "excel",
            "template" => "Phly_Prop.xlsx"
        )
    );

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService) 
    { 
        $this->logger->info("Executing Quaterly Report Generation with data- ".json_encode($data));
        $params = array();
        $filterParams = array();
        $finalData = array();

        $params['workflowStatus'] = 'Completed';
        if(isset($data['startDate'])){
            $params['gtCreatedDate'] =  $data['startDate'];
        }
        if(isset($data['endDate'])){
            $params['ltCreatedDate'] =  $data['endDate'];
        }
        if($data['productType'] == 'individualProfessionalLiability'){
            $params['entityName'] = 'Individual Professional Liability';
        }
        if($data['productType'] == 'groupProfessionalLiability'){
            $filter[] = array("field" => "groupProfessionalLiabilitySelect", "operator" => "eq", "value" => 'yes');
            $filterParams = array(array("filter" => array("logic" => "AND", "filters" => $filter)));
        }
        if($data['productType'] == 'diveStore'){
            $params['entityName'] = 'Dive Store';
        }
        if($data['productType'] == 'diveStoreProperty'){
            $params['entityName'] = 'Dive Store';
            $filter[] = array("field" => "propertyCoverageSelect", "operator" => "eq", "value" => 'yes');
            $filterParams = array(array("filter" => array("logic" => "AND", "filters" => $filter)));
        }
        $files = $this->getWorkflowCompletedData($params,$filterParams); 
        $this->logger->info("The data returned from getWorkflowCompletedData is  ".print_r($files,true));
        
        if(empty($files['data'])){
            $data['jobStatus'] = 'No Records Found';
            $this->saveFile($data,$data['uuid']);      
            return $data;
        }
        $result = $this->newDataArray($files,$data['productType']); 
        $this->logger->info("The data returned from newDataArray is  ".print_r($result,true));
        $excelData = $this->excelDataMassage($result['data']);
        $this->logger->info("Quarterly Report".print_r($excelData,true));
        $files['data'] = $result['data'];
        $files['total'] = sizeof($finalData);
        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
        $dest = ArtifactUtils::getDocumentFilePath($this->destination, $data['uuid'], array('orgUuid' => $orgUuid));
        $selectedTemplate = $this->carrierTemplateTypeMapping[$data['productType']];
        $docDest = $dest['absolutePath']. $selectedTemplate['template'];
        $this->documentBuilder->fillExcelTemplate(
            $selectedTemplate['template'],
            $excelData,
            $docDest
        );
        if(!empty($result['data'])){
            $data['documents']['GenerateReport'] = $dest['relativePath'].$selectedTemplate['template'];
            if(isset($data['jobStatus']) && ($data['jobStatus'] == 'In Force')){
                $data['jobStatus'] = 'Completed';
            }  
        }
        $this->saveFile($data,$data['uuid']);
        return $data;
    }
    
    private function formatDate($date){
        $formattedDate = date('Y-m-d',strtotime($date));
        return $formattedDate;
    }

    private function excelDataMassage($data){ 
        $result = array();
        foreach ($data as $key =>$List) {
            foreach ($List as $i => $response) {
                if(array_key_exists($i,$List)){
                    $result[$i][] = $List[$i];
                }
            }
        }
        return $result;
    }

    private function coverageFP($data){
        
        switch($data) {
            case "standardCoverageUpTo50000": 
                return "Standard Coverage (Up to $50,000)";
            break;
            case "standardCoverage50001To100000":
                return "Standard Coverage ($50,001 to $100,000)";
            break;
            case "standardCoverage100001To200000":
                return "Standard Coverage ($100,001 to $200,000)";
            break;
            case "standardCoverage200001To350000":
                return "Standard Coverage ($200,001 to $350,000)";
            break;
            case "standardCoverage350001To500000": 
                return "Standard Coverage ($350,001 to $500,000)";
            break;
            case "standardCoverage500001To1M": 
                return "Standard Coverage ($500,001 to $1M)";
            break;
            case "standardCoverage1MAndOver": 
                return "Standard Coverage ($1M and Over)";
            break;
            case "liabilityOnlyUpTo50000":
                return "Liability Only (Up to $50,000)";
            break;
            case "liabilityOnly50001To100000": 
                return "Liability Only ($50,001 to $100,000)";
            break;
            case "liabilityOnly100001To200000":
                return "Liability Only ($100,001 to $200,000)";
            break;
            case "liabilityOnly200001To350000":
                return "Liability Only ($200,001 to $350,000)";
            break;
            case "liabilityOnly350001To500000":
                return "Liability Only ($350,001 to $500,000)";
            break;
            case "liabilityOnly500001To1M":
                return "Liability Only ($500,001 to $1M)";
            break;
            case "liabilityOnly1MAndOver":
                return "Liability Only ($1M and Over)";
            break;
            case "discontinuedOperation":
                return "Discontinued Operation";
            break;
            case "noCoverageSelected":
                return "No Coverage Selected";
            break;
            default: return;
        }

    }
    private function groupCoverageLevel($data){

        if('groupCoverageMoreThan0'){
            return "0 to $25,000";
        }
        else if('groupCoverageMoreThan25000'){
            return "$25,001 to $50,000";
        }
        else if('groupCoverageMoreThan50000'){
            return "$50,001 to $100,000";
        }
        else if('groupCoverageMoreThan100000'){
            return "$100,001 to $150,000";
        }
        else if('groupCoverageMoreThan150000'){
            return "$150,001 to $200,000";
        }
        else if('groupCoverageMoreThan200000'){
            return "$200,001 to $250,000";
        }
        else if('groupCoverageMoreThan250000'){
            return "$250,001 to $350,000";
        }
        else if('groupCoverageMoreThan350000'){
            return "$350,001 to $500,000";
        }
        else if('groupCoverageMoreThan500000'){
            return "$500,001 and up";
        }
    }
    private function checkStatus($data){
        switch($data){
            case "instructor":
                return "Instructor";
            break;
            case "nonteachingSupervisoryInstructor":
                return "Nonteaching / Supervisory Instructor (4)";
            break;
            case "freediveInstructor":
                return "Free Diver Instructor";
            break;
            case "retiredInstructor":
                return "Retired Instructor (4)";
            break;
            case "internationalInstructor":
                return "International Instructor (3)";
            break;
            case "internationalNonteachingSupervisoryInstructor":
                return "International Nonteaching / Supervisory Instructor (3)(4)";
            break;
            case "assistantInstructor":
                return "Assistant Instructor";
            break;
            case "divemasterAssistantInstructorAssistingOnly":
                return "Divemaster / Assistant Instructor Assisting Only (2)";
            break;
            case "divemaster":
                return "Divemaster";
            break;
            case "emergencyFirstResponseInstructor":
                return "Emergency First Response Instructor";
            break;
            case "swimInstructor":
                return "Swim Instructor";
            break;
            case "snorklingInstructor":
                return "Snorkling Instructor";
            break;
            default: return "";

        }
    }
    private function newDataArray($data,$product){
      
        $this->logger->info('Generate report data to be formatted: '.print_r($data, true));
        $i = 0;
        foreach ($data['data'] as $key => $value) {
            $totalendorsements = 0;
            if(isset($value['previous_policy_data'])){
                $previous_policy_data = json_decode($value['previous_policy_data'],true);
                $totalendorsements = count(array_filter($previous_policy_data, 'array_filter'));
                
            }
            if(isset($value['product']) && $value['product'] == "Individual Professional Liability"){

                $response[$i]['certificate_no'] = isset($value['certificate_no']) ? $value['certificate_no'] : "";
                $response[$i]['PADI_No'] = $value['padi'];
                $response[$i]['firstname'] =$value['firstname'];
                $response[$i]['initial'] = $value['initial'];
                $response[$i]['lastname'] =$value['lastname'];
                $response[$i]['address1'] = $value['address1'];
                $response[$i]['address2'] = isset($value['address2']) ? $value['address2'] : '';
                $response[$i]['city'] = $value['city'];
                $response[$i]['state'] = $value['state'];
                $response[$i]['taxFiling'] = $value['state'];
                $response[$i]['zip'] = $value['zip'];
                $response[$i]['country'] = $value['country'];
                $response[$i]['certificate_type'] = (isset($totalendorsements) && $totalendorsements > 0) ? 'Endorsement' : 'Primary Coverage';
                $response[$i]['renewal_new'] = isset($value['isRenewalFlow']) ? $value['isRenewalFlow']  == "false"? "New" : "Renewal" : "";
                $response[$i]['program'] = isset($totalendorsements) && $totalendorsements > 0 ? $this->checkStatus($previous_policy_data[0]['previous_careerCoverage']) : $this->checkStatus($value['careerCoverage']);
                $response[$i]['start_date'] = $this->formatDate($value['start_date']);
                $response[$i]['end_date'] = $this->formatDate($value['end_date']);
                $response[$i]['premium'] = isset($value['careerCoveragePrice']) ? $value['careerCoveragePrice'] : "0";
                $response[$i]['equipment'] = isset($value['equipmentPrice']) && $value['equipmentPrice'] != "" || $value['equipmentPrice'] != null ? $value['equipmentPrice'] : "0";
                $response[$i]['excess'] = isset($totalendorsements) && $totalendorsements > 0 ? (isset($value['excessLiabilityPricePayable']) ?  $value['excessLiabilityPricePayable']: isset($value['excessLiabilityPrice']) ? $value['excessLiabilityPrice'] : "0" ) : "0";
                $response[$i]['scuba_fit'] = isset($value['scubaFitPrice']) ? $value['scubaFitPrice'] : "0";
                $response[$i]['upgrade'] = (((isset($totalendorsements)) && $totalendorsements > 0) && ($value['careerCoveragePrice'] != "" || $value['careerCoveragePrice'] != 0 || $value['careerCoveragePrice'] != null)) ? $this->checkStatus($value['careerCoverage']) : "";
                $response[$i]['cylinder'] = isset($value['cylinderPrice']) ? $value['cylinderPrice'] : "0" ;
                $response[$i]['total'] = (((float) $response[$i]['premium']) + ((float)$response[$i]['equipment']) + ((float) $response[$i]['excess']) + ((float)$response[$i]['scuba_fit']) + ((float)$response[$i]['cylinder']));
                $response[$i]['cancel_date'] = isset($value['cancelDate']) ? $this->formatDate($value['cancelDate']) : "" ;
                $response[$i]['cancelled'] = isset($value['cancellationStatus']) && $value['cancellationStatus'] == "approved"? "True" : "False";
                $response[$i]['auto_renewal'] = isset($totalendorsements) && $totalendorsements > 0 ? "No" : $value['automatic_renewal']? "Yes" : "No";
                $response[$i]['installment'] = isset($totalendorsements) && $totalendorsements > 0 ? "No" : ($value['premiumFinanceSelect'] == "no" ? "No" : "Yes");
                $response[$i]['downPayment'] = isset($value['downPayment']) ? $value['downPayment'] : "0" ;
                $response[$i]['totalPaid'] = isset($totalendorsements) && $totalendorsements > 0 ? $response[$i]['total'] : ($value['premiumFinanceSelect'] == "no"?  $response[$i]['total'] : $value['downPayment']);
                $responseData['data'] = $response;
                $i += 1; 
            }
            if($product == 'groupProfessionalLiability' && isset($value['groupPL']) && !empty($value['groupPL']) && $value['groupPL'] != "[]" &&  $value['groupProfessionalLiabilitySelect'] == 'yes'){

                $this->logger->info('group PL members need to be formatted to a new array');
                if(isset($value['groupPL'])){
                    $groupData = is_string($value['groupPL']) ? json_decode($value['groupPL'], true) : $value['groupPL'];
                    // $params['status'] = 'Completed';
                    // $params['entityName'] = 'Dive Store';
                    // $endDate = date("Y-m-d" ,strtotime('-1 year',strtotime($value['end_date'])));
                    // $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'eq','value'=>$endDate);
                    // $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'padi','operator'=>'eq','value'=>$value['business_padi']);
                    // $policyList = $this->getFileList($params,$filterParams);
                    // if(count($policyList['data']) > 0){
                    //     $fileData = json_decode($policyList['data'][0]['data'],true);
                    // }
                } else {
                    $groupData = array();
                }
                $this->logger->info('group data is: '.print_r($groupData, true));
                $this->logger->info('value data is: '.print_r($value, true));
                $total = count($groupData);
                $groupPL = array();
                if(isset($value['previous_policy_data'])){
                    if(isset($previous_policy_data[$totalendorsements - 1]['previous_groupPL'])){
                        $previous_groupPL = $previous_policy_data[$totalendorsements - 1]['previous_groupPL'];
                        $j =0;
                        foreach ($previous_groupPL as $key2 => $value2){
                            foreach ($groupData as $key1 => $value1) {
                                if($value2['padi'] == $value1['padi']) {
                                    if($value2['status'] != $value1['status']){
                                        $previous_careerCoverage[$j] = $value2;
                                        $groupPL[$j] = $value1;
                                        $j+= 1;
                                    }
                                }
                            }
                        }
                    }
                    
                }
                else {
                    $groupPL = $groupData;
                }
                foreach ($groupPL as $key2 => $value2) { 
                    // if(isset($fileData)){
                    //     $key = array_search($value2['padi'], array_column($fileData['groupPL'], 'padi'));
                    //     if(!is_null($key)){
                    //         $response[$i]['renewal'] = "Renewal";
                    //     }
                    // }
                    // else{
                    //     $response[$i]['renewal'] = "New";
                    // }
                    if(isset($previous_careerCoverage)){
                        $key = array_search($value2['padi'], array_column($fileData['groupPL'], 'padi'));
                    }
                    $group_certificate_no = ltrim($value['group_certificate_no'],'S');
                    $response[$i]['certificate_no'] = $group_certificate_no;
                    $response[$i]['padi'] = $value2['padi'];
                    $response[$i]['business_padi'] = $value['business_padi'];
                    $response[$i]['program'] = isset($previous_careerCoverage[$key]) ? $this->checkStatus($previous_careerCoverage[$key]): $this->checkStatus($value2['status']); //change
                    $response[$i]['firstname'] = $value2['firstname'];
                    $response[$i]['lastname'] = $value2['lastname'];
                    $response[$i]['initial'] = $value2['initial'];
                    $response[$i]['start_date'] = $this->formatDate($value2['start_date']);
                    $response[$i]['end_date'] = $this->formatDate($value['end_date']);
                    $response[$i]['address1'] = $value['address1'];
                    $response[$i]['address2'] = isset($value['address2']) ? $value['address2'] : '';
                    $response[$i]['city'] = $value['city'];
                    $response[$i]['state'] = $value['state'];
                    $response[$i]['zip'] = $value['zip'];
                    $response[$i]['country'] = $value['country'];
                    $response[$i]['business_name'] = $value['business_name'];
                    $response[$i]['effectiveDate'] = $value2['effectiveDate'];
                    $response[$i]['group_cvrg_level'] = $this->groupCoverageLevel($value['groupCoverageSelect']);
                    $response[$i]['certificate_type'] = ((isset($totalendorsements) && $totalendorsements > 0)) ? 'Endorsement' : 'Primary Coverage';
                    $response[$i]['upgrade'] = $response[$i]['certificate_type'] == 'Endorsement' && isset($previous_careerCoverage[$key]) && $previous_careerCoverage[$key] != $value2['status'] ? $this->checkStatus($value2['status']) : ''; //change
                    $response[$i]['premium'] = $key2 == 0 ? isset($value['groupCoverage']) ? $value['groupCoverage'] : "0" : "0";
                    $response[$i]['excess_premium'] = $key2 == 0 ? isset($value['groupExcessLiability']) ? $value['groupExcessLiability'] : "0" : "0";
                    $response[$i]['cancel_date'] = isset($value['cancel_date']) ? $this->formatDate($value['cancel_date']) : "" ;
                    $response[$i]['total'] =$key2 == 0 ? ((int) $response[$i]['premium'])+ ((int)$response[$i]['excess_premium']) : "0";
                    $responseData['data'] = $response;
                    $i += 1; 
                }  
            
            }
            if(($product == "diveStoreProperty" || $product == "diveStore")){
                $this->logger->info('Additional Locations need to be formatted to a new array');
                if((isset($value['additionalLocationsSelect'])) && $value['additionalLocationsSelect'] == "yes"){
                    $additionalLocationData = is_string($value['additionalLocations']) ? json_decode($value['additionalLocations'], true) : $value['additionalLocations'];
                } else {
                    $additionalLocationData = array();
                }
                if(isset($previous_policy_data)){ 
                    $previous_policy =  $previous_policy_data[$totalendorsements - 1];
                    $previous_additionalLocation = isset($previous_policy['previous_additionalLocations'])? $previous_policy['previous_additionalLocations'] : array();
                }
                $total = count($additionalLocationData);
                $this->logger->info('Primary location');
                $responsePrimary[$i]['certificate_no'] = $value['certificate_no'];
                $responsePrimary[$i]['business_padi'] = $value['business_padi'];
                $responsePrimary[$i]['PADI_No_AL'] = 'Primary Location';
                $responsePrimary[$i]['business_name'] = $value['business_name'];
                $responsePrimary[$i]['storeLocation'] = $value['address1'];
                $responsePrimary[$i]['address1'] = $value['address1'];
                $responsePrimary[$i]['address2'] = isset($value['address2']) ? $value['address2'] : '';
                $responsePrimary[$i]['city'] = $value['city'];
                $responsePrimary[$i]['state'] = is_array($value['state']) ? " " :$value['state'] ;
                $responsePrimary[$i]['zip'] = $value['zip'];
                $responsePrimary[$i]['country'] = $value['country'];
                $responsePrimary[$i]['taxFiling'] = is_array($value['state']) ? " " :$value['state'];
                $responsePrimary[$i]['coverage'] = $this->coverageFP($value['liabilityCoverageOption']);
                $responsePrimary[$i]['start_date'] = $this->formatDate($value['start_date']);
                $responsePrimary[$i]['end_date'] = $this->formatDate($value['end_date']);
                $responsePrimary[$i]['certificate_type'] =   isset($totalendorsements) && $totalendorsements > 0 ? 'Endorsement' : 'Primary Coverage';
                if($product == "diveStoreProperty"){
                    $responsePrimary[$i]['propertyDeductables'] = $value['propertyDeductibles'] == "propertyDeductibles1000"? "$1,000" : $value['propertyDeductibles'] == "propertyDeductibles2500"? "$2,500" : $value['propertyDeductibles'] == "propertyDeductibles5000"? "$5,000" : "$1,000" ;
                    $responsePrimary[$i]['catSelection'] = $value['propertyCoverageOption'] == "cat"? "CAT" : "NON CAT";
                    $responsePrimary[$i]['Addl_Cnts_Limit'] = (isset($previous_policy['previous_dspropTotal']) ? ((float)$value['dspropTotal']) - (float)$previous_policy['previous_dspropTotal'] : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value['dspropTotal']) ? "0" : $value['dspropTotal'] ));
                    $responsePrimary[$i]['Addl_Contents_Premium'] = isset($previous_policy['previous_ContentsFP']) ? ((float)$value['ContentsFP'] - (float)$previous_policy['previous_ContentsFP']) :  (isset( $value['endoContentsFP']) ? $value['endoContentsFP'] : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value['ContentsFP']) ? "0" : $value['ContentsFP']));
                    $responsePrimary[$i]['Loss_of_Business_Income_Limit'] = isset($previous_policy['previous_lossOfBusIncome']) ? ((float)$value['lossOfBusIncome'] - (float)$previous_policy['previous_lossOfBusIncome']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value['lossOfBusIncome']) ? "0" : $value['lossOfBusIncome']);
                    $responsePrimary[$i]['Additional_Loss_of_Income_Premium'] = isset($previous_policy['previous_LossofBusIncomeFP']) ? ((float)$value['LossofBusIncomeFP'] - (float)$previous_policy['previous_LossofBusIncomeFP']) : (isset($value['endoLossofBusIncomeFP']) ?  $value['endoLossofBusIncomeFP'] : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value['LossofBusIncomeFP']) ? "0" : $value['LossofBusIncomeFP']));
                    $responsePrimary[$i]['buildingType'] = $value['dspropbuildingconstr'];
                    $responsePrimary[$i]['Building_Limit'] = isset($previous_policy['previous_dspropreplacementvalue']) ? ((float)$value['dspropreplacementvalue'] - (float)$previous_policy['previous_dspropreplacementvalue']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value['dspropreplacementvalue']) ? "0" : $value['dspropreplacementvalue']);
                    $responsePrimary[$i]['Building_Premium'] = isset($previous_policy['previous_BuildingLimitFP']) ? ((float)$value['BuildingLimitFP'] - (float)$previous_policy['previous_BuildingLimitFP']) : isset($value['endoBuildingLimitFP']) ? $value['endoBuildingLimitFP']: ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : (is_null($value['BuildingLimitFP']) ? "0" : $value['BuildingLimitFP']));
                    $responsePrimary[$i]['Total'] =  ((float)$responsePrimary[$i]['Building_Premium'] + (float)$responsePrimary[$i]['Additional_Loss_of_Income_Premium'] + (float)$responsePrimary[$i]['Addl_Contents_Premium']);
                }
                if($product == "diveStore"){ 
                    $responsePrimary[$i]['basepremium'] = isset($previous_policy['previous_CoverageFP']) ? ((float)$value['CoverageFP'] - (float)$previous_policy['previous_CoverageFP']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" :  (is_null($value['CoverageFP']) ? "0" : $value['CoverageFP']) );
                    $responsePrimary[$i]['nonDivingPool'] = isset($previous_policy['previous_nonDivingPoolAmount']) ? ((float)($value['nonDivingPoolAmount']) - (float)$previous_policy['previous_nonDivingPoolAmount']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value['nonDivingPoolAmount']) ? "0" : $value['nonDivingPoolAmount']);
                    $responsePrimary[$i]['ExcessLiability']  = isset($previous_policy['previous_ExcessLiabilityFP']) ? ((float)($value['ExcessLiabilityFP']) - (float)$previous_policy['previous_ExcessLiabilityFP']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value['ExcessLiabilityFP']) ? "0" : $value['ExcessLiabilityFP']);
                    $responsePrimary[$i]['TravelAgent'] = isset($previous_policy['previous_TravelAgentEOFP']) ? ((float)$value['TravelAgentEOFP'] - (float)$previous_policy['previous_TravelAgentEOFP']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value['TravelAgentEOFP']) ? "0" : $value['TravelAgentEOFP'] ); 
                    $responsePrimary[$i]['medicalExpense'] = isset($previous_policy['previous_MedicalExpenseFP']) ?  ((float)$value['MedicalExpenseFP'] - (float)$previous_policy['previous_MedicalExpenseFP']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value['MedicalExpenseFP']) ? "0" : $value['MedicalExpenseFP'] );
                    $responsePrimary[$i]['lakeQuarry'] = ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : (is_null($value['lakeQuarryPond']) ? "0" : $value['lakeQuarryPond']) );
                    $responsePrimary[$i]['nonOwnedAuto'] = isset($previous_policy['previous_Non-OwnedAutoFP']) ? ((float)$value['Non-OwnedAutoFP'] - (float)$previous_policy['previous_Non-OwnedAutoFP']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value['Non-OwnedAutoFP']) ? "0" : $value['Non-OwnedAutoFP']);
                }
                $responsePl = $responsePrimary;
                if((isset($value['additionalLocationsSelect'])) && $value['additionalLocationsSelect'] == "yes"){
                    foreach ($additionalLocationData as $key2 => $value2) { 
                        if(isset($previous_additionalLocation)){
                            $key = array_search($value2['name'], array_column($previous_additionalLocation, 'name'));
                        }
                        $response[$i]['certificate_no'] = $value['certificate_no'];
                        $response[$i]['business_padi'] = $value['business_padi'];
                        $response[$i]['PADI_No_AL'] = $value2['padiNumberAL'];
                        $response[$i]['business_name'] =$value['business_name'];
                        $response[$i]['storeLocation'] = $value2['address'];
                        $response[$i]['address1'] = $value2['address'];
                        $response[$i]['address2'] = isset($value2['address2']) ? $value2['address2'] : '';
                        $response[$i]['city'] = $value2['city'];
                        $response[$i]['state'] = is_array($value['state']) ? " " :$value['state'] ;
                        $response[$i]['zip'] = $value2['zip'];
                        $response[$i]['country'] = $value2['country'];
                        $response[$i]['taxFiling'] = is_array($value['state']) ? " " :$value['state'] ;
                        $response[$i]['coverage'] = $this->coverageFP($value['liabilityCoverageOption']);
                        $response[$i]['start_date'] = $this->formatDate($value['start_date']);
                        $response[$i]['end_date'] = $this->formatDate($value['end_date']);
                        $response[$i]['certificate_type'] =  isset($totalendorsements) && $totalendorsements > 0 ? 'Endorsement' : 'Primary Coverage';
                        if($product == "diveStoreProperty") { 
                            $response[$i]['propertyDeductables'] = $value['propertyDeductibles'] == "propertyDeductibles1000"? "$1,000" : $value['propertyDeductibles'] == "propertyDeductibles2500"? "$2,500" : $value['propertyDeductibles'] == "propertyDeductibles5000"? "$5,000" : "$1,000" ;
                            $response[$i]['catSelection'] = $value['propertyCoverageOption'] == "cat"? "CAT" : "NON CAT";
                            $response[$i]['Addl_Cnts_Limit'] = isset($previous_additionalLocation[$key]['previous_additionalLocationPropertyTotal']) ? ((float)$value2['additionalLocationPropertyTotal']) - (float)$previous_additionalLocation[$key]['previous_additionalLocationPropertyTotal'] : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['additionalLocationPropertyTotal']) ? "0" : $value2['additionalLocationPropertyTotal'] );
                            $response[$i]['Addl_Contents_Premium'] = isset($previous_additionalLocation[$key]['previous_ALContentsFP']) ? ((float)$value2['ALContentsFP'] - (float)$previous_additionalLocation[$key]['previous_ALContentsFP']) : (isset( $value2['endoALContentsFP']) ? $value2['endoALContentsFP'] :  ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['ALContentsFP']) ? "0" : $value2['ALContentsFP']));
                            $response[$i]['Loss_of_Business_Income_Limit'] = isset($previous_additionalLocation[$key]['previous_ALLossofBusIncome']) ? ((float)$value2['ALLossofBusIncome'] - (float)$previous_additionalLocation[$key]['previous_ALLossofBusIncome']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['ALLossofBusIncome']) ? "0" : $value2['ALLossofBusIncome']);
                            $response[$i]['Additional_Loss_of_Income_Premium'] = isset($previous_additionalLocation[$key]['previous_ALLossofBusIncomeFP']) ? ((float)$value2['ALLossofBusIncomeFP'] - (float)$previous_additionalLocation[$key]['previous_ALLossofBusIncomeFP']) : (isset($value2['endoALLossofBusIncomeFP']) ?  $value2['endoALLossofBusIncomeFP'] : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['ALLossofBusIncomeFP']) ? "0" : $value2['ALLossofBusIncomeFP']));
                            $response[$i]['buildingType'] = $value2['buildingConstruction'];
                            $response[$i]['Building_Limit'] = isset($previous_additionalLocation[$key]['previous_ALBuildingReplacementValue']) ? ((float)$value2['ALBuildingReplacementValue'] - (float)$previous_additionalLocation[$key]['previous_ALBuildingReplacementValue']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['ALBuildingReplacementValue']) ? "0" : $value2['ALBuildingReplacementValue']);
                            $response[$i]['Building_Premium'] = isset($previous_additionalLocation[$key]['previous_ALBuildingLimitFP']) ? ((float)$value2['ALBuildingLimitFP'] - (float)$previous_additionalLocation[$key]['previous_ALBuildingLimitFP']) : isset($value2['endoALBuildingLimitFP']) ? $value2['endoALBuildingLimitFP']: ((isset($totalendorsements) && $totalendorsements > 0) ? "0" :(is_null($value2['ALBuildingLimitFP']) ? "0" : $value2['ALBuildingLimitFP']));
                            $response[$i]['Total'] =  ((float)$response[$i]['Building_Premium'] + (float)$response[$i]['Additional_Loss_of_Income_Premium'] + (float)$response[$i]['Addl_Contents_Premium']);
                        }
                        if($product == "diveStore"){
                            $response[$i]['basepremium'] = isset($previous_additionalLocation[$key]['previous_ALCoverageFP']) ? ((float)$value2['ALCoverageFP'] - (float)$previous_additionalLocation[$key]['previous_ALCoverageFP']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['ALCoverageFP']) ? "0" : $value2['ALCoverageFP'] );
                            $response[$i]['nonDivingPool'] = isset($previous_additionalLocation[$key]['previous_ALnonDivingPoolAmount']) ? ((float)($value2['ALnonDivingPoolAmount']) - (float)$previous_additionalLocation[$key]['previous_ALnonDivingPoolAmount']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['ALnonDivingPoolAmount']) ? "0" : $value2['ALnonDivingPoolAmount']);
                            $response[$i]['ExcessLiability']  = isset($previous_additionalLocation[$key]['previous_ALExcessLiabilityFP']) ? ((float)($value2['ALExcessLiabilityFP']) - (float)$previous_additionalLocation[$key]['previous_ALExcessLiabilityFP']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['ALExcessLiabilityFP']) ? "0" : $value2['ALExcessLiabilityFP']);
                            $response[$i]['TravelAgent'] = isset($previous_additionalLocation[$key]['previous_ALTravelAgentEOFP']) ? ((float)$value2['ALTravelAgentEOFP'] - (float)$previous_additionalLocation[$key]['previous_ALTravelAgentEOFP']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['ALTravelAgentEOFP']) ? "0" : $value2['ALTravelAgentEOFP']) ; 
                            $response[$i]['medicalExpense'] = isset($previous_additionalLocation[$key]['previous_ALMedicalExpenseFP']) ?  ((float)$value2['ALMedicalExpenseFP'] - (float)$previous_additionalLocation[$key]['previous_ALMedicalExpenseFP']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : isset($value2['ALMedicalExpenseFP']) ? "0" : $value2['ALMedicalExpenseFP']) ;
                            $response[$i]['lakeQuarry'] = isset($previous_additionalLocation[$key]['previous_ALlakeQuarry']) ? ((float)$value2['ALlakeQuarry'] - (float)$previous_additionalLocation[$key]['previous_ALlakeQuarry']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['ALlakeQuarry'])? "0" : $value2['ALlakeQuarry'] );
                            $response[$i]['nonOwnedAuto'] = isset($previous_additionalLocation[$key]['previous_ALNonOwnedAutoFP']) ? ((float)$value2['ALNonOwnedAutoFP'] - (float)$previous_additionalLocation[$key]['previous_ALNonOwnedAutoFP']) : ((isset($totalendorsements) && $totalendorsements > 0) ? "0" : is_null($value2['ALNonOwnedAutoFP'])? "0" : $value2['ALNonOwnedAutoFP']);
                        }

                        $responseData['data'] = $response;
                        $i += 1; 
                    } 
                    $responseData['data'] = array_merge($responseData['data'],$responsePl);
                    $this->logger->info('the Dive store response data is : '.print_r($responseData, true)); 
                }

            }
            $responseData['total'] = $i;
        }
        if(empty($responseData['data'])){
            $responseData['total'] = -1;
            $responseData['data'] = '';
        }
        $this->logger->info('the response data is : '.print_r($responseData, true));
        return $responseData;
    }
}
?>