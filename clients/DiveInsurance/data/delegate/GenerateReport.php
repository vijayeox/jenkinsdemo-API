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
            if(isset($data['jobStatus']) && ($data['jobStatus'] == 'In Progress')){
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
                if(is_array($response)){
                    foreach ($response as $j => $innerResponse){
                        if(array_key_exists($j,$response)){
                            $result[$j][] = $response[$j];
                        }
                    }
                }
                else {
                    if(array_key_exists($i,$List)){
                        $result[$i][] = $List[$i];
                    }
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

    private function groupDataDiff(&$groupLength, $data, $previousData, $requiredParams)
    {
        $sortAndSerialize = function ($arr) 
        {
            ksort($arr); 
            return serialize($arr);
        };
        $previousVal = is_string($previousData) ? json_decode($previousData, true) : $previousData;
        $this->getRequiredParams($previousVal, $requiredParams);
        foreach ($previousVal as $key => $value) {
            $previousVal[$key]['padi'] = strval($value['padi']);
        }
        $val = is_string($data) ? json_decode($data, true) :  $data;
        $this->getRequiredParams($val, $requiredParams);
        foreach ($val as $key => $value) {
            $val[$key]['padi'] = strval($value['padi']);
        }
        $diff = array_diff(array_map($sortAndSerialize, $val),array_map($sortAndSerialize, $previousVal));
        $newValue = array_map('unserialize', $diff);
        return $newValue;
    }
    private function getRequiredParams(&$data, $requiredParams)
    {
        if (sizeof($requiredParams) > 0) {
            foreach ($data as $key => $val) {
                foreach ($val as $key1 => $val1) {
                    if (!in_array($key1, $requiredParams)) {
                        unset($data[$key][$key1]);
                    }
                }
            }
        }
    }
    private function newDataArray($data,$product){
      
        $this->logger->info('Generate report data to be formatted: '.print_r($data, true));
        $i = 0;
        foreach ($data['data'] as $key => $value) {
            $totalendorsements = 0;
            $previous_policy_data = array();
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
                $response[$i]['certificate_type'] = $value['workflow_name'] == "Individual Liability Endorsement" ? 'Endorsement' : 'Primary Coverage';
                $response[$i]['renewal_new'] = isset($value['isRenewalFlow']) && $value['isRenewalFlow']  == "false"? "New" : "Renewal" ;
                $response[$i]['program'] = ($value['workflow_name'] == "Individual Liability Endorsement") ? $this->checkStatus($previous_policy_data[0]['previous_careerCoverage']) : $this->checkStatus($value['careerCoverage']);
                $response[$i]['start_date'] = $this->formatDate($value['start_date']);
                $response[$i]['end_date'] = $this->formatDate($value['end_date']);
                $response[$i]['premium'] = ($value['workflow_name'] == "Individual Liability Reinstate Policy") ? "0" : (isset($value['careerCoveragePrice']) ? $value['careerCoveragePrice'] : "0");
                $response[$i]['equipment'] = ($value['workflow_name'] == "Individual Liability Reinstate Policy") ? "0" : (isset($value['equipmentPrice']) && $value['equipmentPrice'] != "" || $value['equipmentPrice'] != null ? $value['equipmentPrice'] : "0");
                $response[$i]['excess'] = (($value['workflow_name'] == "Individual Liability Reinstate Policy") ? "0" :($value['workflow_name'] == "Individual Liability Endorsement" ? ($previous_policy_data[0]['previous_excessLiability'] == $value['excessLiability']? "0" : $value['excessLiabilityPrice']): (is_null($value['excessLiabilityPrice']) ?  "0" : $value['excessLiabilityPrice'])));
                $response[$i]['scuba_fit'] = isset($value['scubaFitPrice']) ? $value['scubaFitPrice'] : "0";
                $response[$i]['upgrade'] = ($value['workflow_name'] == "Individual Liability Endorsement" && ($value['careerCoveragePrice'] != "" || $value['careerCoveragePrice'] != 0 || $value['careerCoveragePrice'] != null)) ? $this->checkStatus($value['careerCoverage']) : "";
                $response[$i]['cylinder'] = ($value['workflow_name'] == "Individual Liability Reinstate Policy") ? "0" : (isset($value['cylinderPrice']) ? $value['cylinderPrice'] : "0" );
                $response[$i]['total'] = (((float) $response[$i]['premium']) + ((float)$response[$i]['equipment']) + ((float) $response[$i]['excess']) + ((float)$response[$i]['scuba_fit']) + ((float)$response[$i]['cylinder']));
                $response[$i]['cancel_date'] = $value['workflow_name'] == "IPL Cancel Policy" ? $this->formatDate($value['modifiedDate']) : "";
                $response[$i]['cancelled'] =  $value['workflow_name'] == "IPL Cancel Policy" ? "True" : "False";
                $response[$i]['reinstated'] = $value['workflow_name'] == "Individual Liability Reinstate Policy" ? "True" : "False";
                $response[$i]['reinstated_date'] = $value['workflow_name'] == "Individual Liability Reinstate Policy" ? $this->formatDate($value['modifiedDate']) : "";
                $response[$i]['auto_renewal'] = $value['workflow_name'] == "Individual Liability Endorsement" ? "No" : ($value['automatic_renewal']? "Yes" : "No");
                $response[$i]['installment'] = $value['workflow_name'] == "Individual Liability Endorsement" ? "No" : ($value['premiumFinanceSelect'] == "no" ? "No" : "Yes");
                $response[$i]['downPayment'] = ($value['workflow_name'] == "Individual Liability Reinstate Policy" || $value['workflow_name'] == "Individual Liability Endorsement") ? "0" : (isset($value['downPayment']) ? $value['downPayment'] : "0");
                $response[$i]['totalPaid'] = $value['workflow_name'] == "Individual Liability Reinstate Policy" ? "0" : ($value['workflow_name'] == "Individual Liability Endorsement" ? $response[$i]['total'] : ($value['premiumFinanceSelect'] == "no"?  $response[$i]['total'] : $value['downPayment']));
                $responseData['data'] = $response;
                $i += 1; 
            }
            if($product == 'groupProfessionalLiability' && isset($value['groupPL'])){

                $this->logger->info('group PL members need to be formatted to a new array');
                $groupPLArray = array('padi', 'firstname', 'lastname', 'status','start_date');
                if(isset($value['groupPL'])){
                    $groupData = is_string($value['groupPL']) ? json_decode($value['groupPL'], true) : $value['groupPL'];
                } else {
                    $groupData = array();
                }
                $this->logger->info('group data is: '.print_r($groupData, true));
                $this->logger->info('value data is: '.print_r($value, true));
                $total = count($groupData);
                $groupPL = array();
                $groupLength = 0;

                $totalendorsement = 0;
                if(isset($value['previous_policy_data'])) {
                   $previous_policy_data = json_decode($value['previous_policy_data'],true);
                   $totalendorsement = sizeof($previous_policy_data);
                   $previous_policy =  $previous_policy_data[$totalendorsement - 1];
                    if(isset($previous_policy_data[$totalendorsement - 1]['previous_groupPL']) && !empty($previous_policy_data[$totalendorsement - 1]['previous_groupPL'])){
                        $previous_groupPL = $previous_policy_data[$totalendorsement - 1]['previous_groupPL'];
                        $groupPL = $this->groupDataDiff($groupLength, $value['groupPL'], $previous_groupPL, $groupPLArray);
                        $k=0;
                        foreach ($previous_groupPL as $key2 => $value2){
                            foreach ($groupData as $key1 => $value1) { 
                                if($value2['padi'] == $value1['padi']) {
                                    if($value2['status'] != $value1['status']){
                                        $previous_careerCoverage[$k] = $value2;
                                        $k+= 1;
                                    }
                                }
                            }
                        }
                    }

                }
                else {
                    $groupPL = $groupData;
                }
                $j=0;
                $key = -1;
                foreach ($groupPL as $key2 => $value2) { 
                    $padi = strval($value2['padi']);
                    if(isset($previous_careerCoverage)) {
                        print_r($value2['padi']);
                        $key = array_search($padi, array_column($previous_careerCoverage, 'padi'));

                    }
                    $group_certificate_no = ltrim($value['group_certificate_no'],'S');
                    $response[$i][$j]['certificate_no'] = $group_certificate_no;
                    $response[$i][$j]['padi'] = $value2['padi'];
                    $response[$i][$j]['business_padi'] = $value['business_padi'];
                    $response[$i][$j]['program'] = isset($previous_careerCoverage[$key]) && $previous_careerCoverage[$key]['status'] != "" && !is_null($previous_careerCoverage[$key]['status'])  ? $this->checkStatus($previous_careerCoverage[$key]['status']): $this->checkStatus($value2['status']); //change
                    $response[$i][$j]['firstname'] = $value2['firstname'];
                    $response[$i][$j]['lastname'] = $value2['lastname'];
                    $response[$i][$j]['initial'] = $value2['initial'];
                    $response[$i][$j]['start_date'] = $this->formatDate($value2['start_date']);
                    $response[$i][$j]['end_date'] = $this->formatDate($value['end_date']);
                    $response[$i][$j]['address1'] = $value['address1'];
                    $response[$i][$j]['address2'] = isset($value['address2']) ? $value['address2'] : '';
                    $response[$i][$j]['city'] = $value['city'];
                    $response[$i][$j]['state'] = $value['state'];
                    $response[$i][$j]['zip'] = $value['zip'];
                    $response[$i][$j]['country'] = $value['country'];
                    $response[$i][$j]['business_name'] = $value['business_name'];
                    $response[$i][$j]['effectiveDate'] = $this->formatDate($value2['start_date']);
                    $response[$i][$j]['group_cvrg_level'] = $this->groupCoverageLevel($value['groupCoverageSelect']);
                    $response[$i][$j]['certificate_type'] = $value['workflow_name'] == "Dive Store Endorsement" ? 'Endorsement' : 'Primary Coverage';
                    $response[$i][$j]['upgrade'] = $response[$i][$j]['certificate_type'] == 'Endorsement' && isset($previous_careerCoverage[$key]['status']) && $previous_careerCoverage[$key]['status'] != $value2['status'] && $previous_careerCoverage[$key]['status'] != "" && !is_null($previous_careerCoverage[$key]['status']) ? $this->checkStatus($value2['status']) : ''; //change
                    $response[$i][$j]['premium'] = $key2 == 0 ? ((isset($previous_policy['previous_groupCoverage']) &&  $value['workflow_name'] == "Dive Store Endorsement")? ((float)$value['groupCoverage'] - (float)($previous_policy['previous_groupCoverage'])) : (isset($value['groupCoverage'])? $value['groupCoverage']: "0")) : "0";
                    $response[$i][$j]['excess_premium'] = $key2 == 0 ? isset($value['groupExcessLiability']) ? $value['groupExcessLiability'] : "0" : "0";
                    $response[$i][$j]['cancel_date'] = isset($value['cancel_date']) ? $this->formatDate($value['cancel_date']) : "" ;
                    $response[$i][$j]['total'] =$key2 == 0 ? ((int) $response[$i][$j]['premium'])+ ((int)$response[$i][$j]['excess_premium']) : "0";
                    $responseData['data'] = $response;
                    $j+= 1;
                }  
                $i += 1; 
            
            }
            if(($product == "diveStoreProperty" || $product == "diveStore") && ($value['workflow_name'] !== "DS Cancel Policy")){
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
                $responsePrimary[$i]['certificate_type'] =   $value['workflow_name'] == "Dive Store Endorsement" ? 'Endorsement' : 'Primary Coverage';
                if($product == "diveStoreProperty"){
                    $responsePrimary[$i]['propertyDeductables'] = $value['propertyDeductibles'] == "propertyDeductibles1000"? "$1,000" : $value['propertyDeductibles'] == "propertyDeductibles2500"? "$2,500" : $value['propertyDeductibles'] == "propertyDeductibles5000"? "$5,000" : "$1,000" ;
                    $responsePrimary[$i]['catSelection'] = $value['propertyCoverageOption'] == "cat"? "CAT" : "NON CAT";
                    $responsePrimary[$i]['Addl_Cnts_Limit'] = (isset($previous_policy['previous_dspropTotal']) ? ((float)$value['dspropTotal']) - (float)$previous_policy['previous_dspropTotal'] : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value['dspropTotal']) ? "0" : $value['dspropTotal'] ));
                    $responsePrimary[$i]['Addl_Contents_Premium'] = isset($previous_policy['previous_ContentsFP']) ? ((float)$value['ContentsFP'] - (float)$previous_policy['previous_ContentsFP']) :  (isset( $value['endoContentsFP']) ? $value['endoContentsFP'] : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value['ContentsFP']) ? "0" : $value['ContentsFP']));
                    $responsePrimary[$i]['Loss_of_Business_Income_Limit'] = isset($previous_policy['previous_lossOfBusIncome']) ? ((float)$value['lossOfBusIncome'] - (float)$previous_policy['previous_lossOfBusIncome']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value['lossOfBusIncome']) ? "0" : $value['lossOfBusIncome']);
                    $responsePrimary[$i]['Additional_Loss_of_Income_Premium'] = isset($previous_policy['previous_LossofBusIncomeFP']) ? ((float)$value['LossofBusIncomeFP'] - (float)$previous_policy['previous_LossofBusIncomeFP']) : (isset($value['endoLossofBusIncomeFP']) ?  $value['endoLossofBusIncomeFP'] : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value['LossofBusIncomeFP']) ? "0" : $value['LossofBusIncomeFP']));
                    $responsePrimary[$i]['buildingType'] = $value['dspropbuildingconstr'];
                    $responsePrimary[$i]['Building_Limit'] = isset($previous_policy['previous_dspropreplacementvalue']) ? ((float)$value['dspropreplacementvalue'] - (float)$previous_policy['previous_dspropreplacementvalue']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value['dspropreplacementvalue']) ? "0" : $value['dspropreplacementvalue']);
                    $responsePrimary[$i]['Building_Premium'] = isset($previous_policy['previous_BuildingLimitFP']) ? ((float)$value['BuildingLimitFP'] - (float)$previous_policy['previous_BuildingLimitFP']) : isset($value['endoBuildingLimitFP']) ? $value['endoBuildingLimitFP']: (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : (is_null($value['BuildingLimitFP']) ? "0" : $value['BuildingLimitFP']));
                    $responsePrimary[$i]['Total'] =  ((float)$responsePrimary[$i]['Building_Premium'] + (float)$responsePrimary[$i]['Additional_Loss_of_Income_Premium'] + (float)$responsePrimary[$i]['Addl_Contents_Premium']);
                    $responsePrimary[$i]['ProRated_Contents_Premium'] = $value['workflow_name'] == "Dive Store Endorsement" ? ((isset($value['endoContentsFP'])) ? round((float)($value['endoContentsFP']) * (float)($value['proRataPercentage'])) : "0.00") : round((float)($value['ContentsFP']) * (float)($value['proRataPercentage']));
                    $responsePrimary[$i]['ProRated_Building_Premium'] = $value['workflow_name'] == "Dive Store Endorsement" ? ((isset($value['endoBuildingLimitFP'])) ? round((float)($value['endoBuildingLimitFP']) * (float)($value['proRataPercentage'])) : "0.00") : round((float)($value['BuildingLimitFP']) * (float)($value['proRataPercentage']));
                    $responsePrimary[$i]['ProRated_Loss_of_Income_Premium'] = $value['workflow_name'] == "Dive Store Endorsement" ? ((isset($value['endoLossofBusIncomeFP'])) ? round((float)($value['endoLossofBusIncomeFP']) * (float)($value['proRataPercentage'])) : "0.00") : round((float)($value['LossofBusIncomeFP']) * (float)($value['proRataPercentage']));
                    $responsePrimary[$i]['propertyProRataPremium']  = $value['propertyProRataPremium'];
                }
                if($product == "diveStore"){ 
                    $responsePrimary[$i]['basepremium'] = isset($previous_policy['previous_CoverageFP']) ? ((float)$value['CoverageFP'] - (float)$previous_policy['previous_CoverageFP']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" :  (is_null($value['CoverageFP']) ? "0" : $value['CoverageFP']) );
                    $responsePrimary[$i]['nonDivingPool'] = isset($previous_policy['previous_nonDivingPoolAmount']) ? ((float)($value['nonDivingPoolAmount']) - (float)$previous_policy['previous_nonDivingPoolAmount']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value['nonDivingPoolAmount']) ? "0" : $value['nonDivingPoolAmount']);
                    $responsePrimary[$i]['ExcessLiability']  = isset($previous_policy['previous_ExcessLiabilityFP']) ? ((float)($value['ExcessLiabilityFP']) - (float)$previous_policy['previous_ExcessLiabilityFP']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value['ExcessLiabilityFP']) ? "0" : $value['ExcessLiabilityFP']);
                    $responsePrimary[$i]['TravelAgent'] = isset($previous_policy['previous_TravelAgentEOFP']) ? ((float)$value['TravelAgentEOFP'] - (float)$previous_policy['previous_TravelAgentEOFP']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value['TravelAgentEOFP']) ? "0" : $value['TravelAgentEOFP'] ); 
                    $responsePrimary[$i]['medicalExpense'] = isset($previous_policy['previous_MedicalExpenseFP']) ?  ((float)$value['MedicalExpenseFP'] - (float)$previous_policy['previous_MedicalExpenseFP']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value['MedicalExpenseFP']) ? "0" : $value['MedicalExpenseFP'] );
                    $responsePrimary[$i]['lakeQuarry'] = "0";
                    $responsePrimary[$i]['nonOwnedAuto'] = isset($previous_policy['previous_Non-OwnedAutoFP']) ? ((float)$value['Non-OwnedAutoFP'] - (float)$previous_policy['previous_Non-OwnedAutoFP']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value['Non-OwnedAutoFP']) ? "0" : $value['Non-OwnedAutoFP']);
                    $responsePrimary[$i]['ProRata_basepremium'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value['endorsementLiabilityCoverage']) ? round((float)$value['endorsementLiabilityCoverage'] * (float)($value['proRataPercentage'])) : "0.00") : round((float)$value['CoverageFP'] * (float)($value['proRataPercentage']));
                    $responsePrimary[$i]['ProRata_nonDivingPool'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value['endononDivingPoolAmount']) ? round((float)$value['endononDivingPoolAmount'] * (float)($value['proRataPercentage'])) : "0.00") : round((float)$value['nonDivingPoolAmount'] * (float)($value['proRataPercentage']));
                    $responsePrimary[$i]['ProRata_ExcessLiability'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value['endoExcessLiabilityFP']) ? round((float)$value['endoExcessLiabilityFP'] * (float)($value['proRataPercentage'])) : "0.00") : round((float)$value['ExcessLiabilityFP'] * (float)($value['proRataPercentage']));
                    $responsePrimary[$i]['ProRata_TravelAgent'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value['endoTravelAgentEOFP']) ? round((float)$value['endoTravelAgentEOFP'] * (float)($value['proRataPercentage'])) : "0.00") : round((float)$value['TravelAgentEOFP'] * (float)($value['proRataPercentage']));
                    $responsePrimary[$i]['ProRata_medicalExpense'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value['endoMedicalExpenseFp']) ? round((float)$value['endoMedicalExpenseFp'] * (float)($value['proRataPercentage'])) : "0.00") : round((float)$value['MedicalExpenseFP'] * (float)($value['proRataPercentage']));
                    $responsePrimary[$i]['ProRata_nonOwnedAuto'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value['endoNon-OwnedAutoFP']) ? round((float)$value['endoNon-OwnedAutoFP'] * (float)($value['proRataPercentage'])) : "0.00") : round((float)$value['Non-OwnedAutoFP'] * (float)($value['proRataPercentage']));
                    $responsePrimary[$i]['liabilityProRataPremium'] = $value['liabilityProRataPremium'];
                
                }
                $responseData['data'] = $responsePrimary;
                if((isset($value['additionalLocationsSelect'])) && $value['additionalLocationsSelect'] == "yes"){
                    $j=0;
                    foreach ($additionalLocationData as $key2 => $value2) { 
                    if(isset($previous_additionalLocation)){
                            $key = array_search($value2['name'], array_column($previous_additionalLocation, 'name'));
                    }
                        $response[$i][$j]['certificate_no'] = $value['certificate_no'];
                        $response[$i][$j]['business_padi'] = $value['business_padi'];
                        $response[$i][$j]['PADI_No_AL'] = $value2['padiNumberAL'];
                        $response[$i][$j]['business_name'] =$value['business_name'];
                        $response[$i][$j]['storeLocation'] = $value2['address'];
                        $response[$i][$j]['address1'] = $value2['address'];
                        $response[$i][$j]['address2'] = isset($value2['address2']) ? $value2['address2'] : '';
                        $response[$i][$j]['city'] = $value2['city'];
                        $response[$i][$j]['state'] = is_array($value['state']) ? " " :$value['state'] ;
                        $response[$i][$j]['zip'] = $value2['zip'];
                        $response[$i][$j]['country'] = $value2['country'];
                        $response[$i][$j]['taxFiling'] = is_array($value['state']) ? " " :$value['state'] ;
                        $response[$i][$j]['coverage'] = $this->coverageFP($value['liabilityCoverageOption']);
                        $response[$i][$j]['start_date'] = $this->formatDate($value['start_date']);
                        $response[$i][$j]['end_date'] = $this->formatDate($value['end_date']);
                        $response[$i][$j]['certificate_type'] =  $value['workflow_name'] == "Dive Store Endorsement" ? 'Endorsement' : 'Primary Coverage';
                        if($product == "diveStoreProperty") { 
                            $response[$i][$j]['propertyDeductables'] = $value['propertyDeductibles'] == "propertyDeductibles1000"? "$1,000" : $value['propertyDeductibles'] == "propertyDeductibles2500"? "$2,500" : $value['propertyDeductibles'] == "propertyDeductibles5000"? "$5,000" : "$1,000" ;
                            $response[$i][$j]['catSelection'] = $value['propertyCoverageOption'] == "cat"? "CAT" : "NON CAT";
                            $response[$i][$j]['Addl_Cnts_Limit'] = isset($previous_additionalLocation[$key]['previous_additionalLocationPropertyTotal']) ? ((float)$value2['additionalLocationPropertyTotal']) - (float)$previous_additionalLocation[$key]['previous_additionalLocationPropertyTotal'] : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['additionalLocationPropertyTotal']) ? "0" : $value2['additionalLocationPropertyTotal'] );
                            $response[$i][$j]['Addl_Contents_Premium'] = isset($previous_additionalLocation[$key]['previous_ALContentsFP']) ? ((float)$value2['ALContentsFP'] - (float)$previous_additionalLocation[$key]['previous_ALContentsFP']) : (isset( $value2['endoALContentsFP']) ? $value2['endoALContentsFP'] :  (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['ALContentsFP']) ? "0" : $value2['ALContentsFP']));
                            $response[$i][$j]['Loss_of_Business_Income_Limit'] = isset($previous_additionalLocation[$key]['previous_ALLossofBusIncome']) ? ((float)$value2['ALLossofBusIncome'] - (float)$previous_additionalLocation[$key]['previous_ALLossofBusIncome']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['ALLossofBusIncome']) ? "0" : $value2['ALLossofBusIncome']);
                            $response[$i][$j]['Additional_Loss_of_Income_Premium'] = isset($previous_additionalLocation[$key]['previous_ALLossofBusIncomeFP']) ? ((float)$value2['ALLossofBusIncomeFP'] - (float)$previous_additionalLocation[$key]['previous_ALLossofBusIncomeFP']) : (isset($value2['endoALLossofBusIncomeFP']) ?  $value2['endoALLossofBusIncomeFP'] : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['ALLossofBusIncomeFP']) ? "0" : $value2['ALLossofBusIncomeFP']));
                            $response[$i][$j]['buildingType'] = $value2['buildingConstruction'];
                            $response[$i][$j]['Building_Limit'] = isset($previous_additionalLocation[$key]['previous_ALBuildingReplacementValue']) ? ((float)$value2['ALBuildingReplacementValue'] - (float)$previous_additionalLocation[$key]['previous_ALBuildingReplacementValue']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['ALBuildingReplacementValue']) ? "0" : $value2['ALBuildingReplacementValue']);
                            $response[$i][$j]['Building_Premium'] = isset($previous_additionalLocation[$key]['previous_ALBuildingLimitFP']) ? ((float)$value2['ALBuildingLimitFP'] - (float)$previous_additionalLocation[$key]['previous_ALBuildingLimitFP']) : isset($value2['endoALBuildingLimitFP']) ? $value2['endoALBuildingLimitFP']: (($value['workflow_name'] == "Dive Store Endorsement") ? "0" :(is_null($value2['ALBuildingLimitFP']) ? "0" : $value2['ALBuildingLimitFP']));
                            $response[$i][$j]['Total'] =  ((float)$response[$i][$j]['Building_Premium'] + (float)$response[$i][$j]['Additional_Loss_of_Income_Premium'] + (float)$response[$i][$j]['Addl_Contents_Premium']);
                            $response[$i][$j]['ProRated_Contents_Premium'] = $value['workflow_name'] == "Dive Store Endorsement" ? ((isset($value2['endoALContentsFP'])) ? round((float)($value2['endoALContentsFP']) * (float)($value2['ALProRataPremiumPercentage'])) : "0.00") : round((float)($value2['ALContentsFP']) * (float)($value2['ALProRataPremiumPercentage']));
                            $response[$i][$j]['ProRated_Building_Premium'] = $value['workflow_name'] == "Dive Store Endorsement" ? ((isset($value2['endoALBuildingLimitFP'])) ? round((float)($value2['endoALBuildingLimitFP']) * (float)($value2['ALProRataPremiumPercentage'])) : "0.00") : round((float)($value2['ALBuildingLimitFP']) * (float)($value2['ALProRataPremiumPercentage']));
                            $response[$i][$j]['ProRated_Loss_of_Income_Premium'] = $value['workflow_name'] == "Dive Store Endorsement" ? ((isset($value2['endoALLossofBusIncomeFP'])) ? round((float)($value2['endoALLossofBusIncomeFP']) * (float)($value2['ALProRataPremiumPercentage'])) : "0.00") : round((float)($value2['ALLossofBusIncomeFP']) * (float)($value2['ALProRataPremiumPercentage']));
                            $response[$i][$j]['propertyProRataPremium']  = (float)$response[$i][$j]['ProRated_Contents_Premium'] + (float)$response[$i][$j]['ProRated_Building_Premium'] + (float)$response[$i][$j]['ProRated_Loss_of_Income_Premium'];
                        
                        }
                        if($product == "diveStore"){
                            $response[$i][$j]['basepremium'] = isset($previous_additionalLocation[$key]['previous_ALCoverageFP']) ? ((float)$value2['ALCoverageFP'] - (float)$previous_additionalLocation[$key]['previous_ALCoverageFP']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['ALCoverageFP']) ? "0" : $value2['ALCoverageFP'] );
                            $response[$i][$j]['nonDivingPool'] = isset($previous_additionalLocation[$key]['previous_ALnonDivingPoolAmount']) ? ((float)($value2['ALnonDivingPoolAmount']) - (float)$previous_additionalLocation[$key]['previous_ALnonDivingPoolAmount']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['ALnonDivingPoolAmount']) ? "0" : $value2['ALnonDivingPoolAmount']);
                            $response[$i][$j]['ExcessLiability']  = isset($previous_additionalLocation[$key]['previous_ALExcessLiabilityFP']) ? ((float)($value2['ALExcessLiabilityFP']) - (float)$previous_additionalLocation[$key]['previous_ALExcessLiabilityFP']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['ALExcessLiabilityFP']) ? "0" : $value2['ALExcessLiabilityFP']);
                            $response[$i][$j]['TravelAgent'] = isset($previous_additionalLocation[$key]['previous_ALTravelAgentEOFP']) ? ((float)$value2['ALTravelAgentEOFP'] - (float)$previous_additionalLocation[$key]['previous_ALTravelAgentEOFP']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['ALTravelAgentEOFP']) ? "0" : $value2['ALTravelAgentEOFP']) ; 
                            $response[$i][$j]['medicalExpense'] = isset($previous_additionalLocation[$key]['previous_ALMedicalExpenseFP']) ?  ((float)$value2['ALMedicalExpenseFP'] - (float)$previous_additionalLocation[$key]['previous_ALMedicalExpenseFP']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : isset($value2['ALMedicalExpenseFP']) ? "0" : $value2['ALMedicalExpenseFP']) ;
                            $response[$i][$j]['lakeQuarry'] = isset($previous_additionalLocation[$key]['previous_ALlakeQuarry']) ? ((float)$value2['ALlakeQuarry'] - (float)$previous_additionalLocation[$key]['previous_ALlakeQuarry']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['ALlakeQuarry'])? "0" : $value2['ALlakeQuarry'] );
                            $response[$i][$j]['nonOwnedAuto'] = isset($previous_additionalLocation[$key]['previous_ALNonOwnedAutoFP']) ? ((float)$value2['ALNonOwnedAutoFP'] - (float)$previous_additionalLocation[$key]['previous_ALNonOwnedAutoFP']) : (($value['workflow_name'] == "Dive Store Endorsement") ? "0" : is_null($value2['ALNonOwnedAutoFP'])? "0" : $value2['ALNonOwnedAutoFP']);
                            $response[$i][$j]['ProRata_basepremium'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value2['ALCoverageFP']) ? round((float)$value2['ALCoverageFP'] * (float)($value2['ALProRataPremiumPercentage'])) : "0.00") : round((float)$value2['ALCoverageFP'] * (float)($value2['ALProRataPremiumPercentage']));
                            $response[$i][$j]['ProRata_nonDivingPool'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value2['ALnonDivingPoolAmount']) ? round((float)$value2['ALnonDivingPoolAmount'] * (float)($value2['ALProRataPremiumPercentage'])) : "0.00") : round((float)$value2['ALnonDivingPoolAmount'] * (float)($value2['ALProRataPremiumPercentage']));
                            $response[$i][$j]['ProRata_ExcessLiability'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value2['ALExcessLiabilityFP']) ? round((float)$value2['ALExcessLiabilityFP'] * (float)($value2['ALProRataPremiumPercentage'])) : "0.00") : round((float)$value2['ALExcessLiabilityFP'] * (float)($value2['ALProRataPremiumPercentage']));
                            $response[$i][$j]['ProRata_TravelAgent'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value2['ALTravelAgentEOFP']) ? round((float)$value2['ALTravelAgentEOFP'] * (float)($value2['ALProRataPremiumPercentage'])) : "0.00") : round((float)$value2['ALTravelAgentEOFP'] * (float)($value2['ALProRataPremiumPercentage']));
                            $response[$i][$j]['ProRata_medicalExpense'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value2['ALMedicalExpenseFP']) ? round((float)$value2['ALMedicalExpenseFP'] * (float)($value2['ALProRataPremiumPercentage'])) : "0.00") : round((float)$value2['ALMedicalExpenseFP'] * (float)($value2['ALProRataPremiumPercentage']));
                            $response[$i][$j]['ProRata_nonOwnedAuto'] = $value['workflow_name'] == "Dive Store Endorsement" ? (isset($value2['ALNonOwnedAutoFP']) ? round((float)$value2['ALNonOwnedAutoFP'] * (float)($value2['ALProRataPremiumPercentage'])) : "0.00") : round((float)$value2['Non-ALNonOwnedAutoFP'] * (float)($value2['ALProRataPremiumPercentage']));
                            $response[$i][$j]['liabilityProRataPremium'] = (float)$response[$i][$j]['ProRata_basepremium'] + (float)$response[$i][$j]['ProRata_nonDivingPool'] + (float)$response[$i][$j]['ProRata_ExcessLiability'] + (float)$response[$i][$j]['ProRata_TravelAgent'] + (float)$response[$i][$j]['ProRata_medicalExpense'] + (float)$response[$i][$j]['ProRata_nonOwnedAuto'];
                        }
                        $responseAl['data'] = $response;
                        $j+= 1;
                    }
                }
                $i += 1; 
            }
            if(isset($responseAl['data'])){
                $responseData['data'] = array_merge($responseData['data'],$responseAl['data']);
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