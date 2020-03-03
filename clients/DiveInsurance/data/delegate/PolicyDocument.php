<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\ArtifactUtils;

class PolicyDocument extends AbstractDocumentAppDelegate
{
    protected $type;
    protected $template;
    
    public function __construct(){
        parent::__construct();
        $this->type = 'policy';
        $this->template = array(
            'Individual Professional Liability' 
                => array('template' => 'ProfessionalLiabilityCOI',
                'header' => 'COIheader.html',
                'footer' => 'COIfooter.html',
                'slWording' => 'SL_Wording.pdf',
                'policy' => 'Individual_Professional_Liability_Policy.pdf',
                'aiTemplate' => 'Individual_PL_AI',
                'blanketForm' => 'Individual_AI_Blanket_Endorsement.pdf',
                'aiheader' => 'IPL_AI_header.html',
                'aifooter' => null,
                'iplScuba' => 'PL_Scuba_Fit_Endorsement.pdf',
                'iplCylinder' => 'PL_Cylinder_Endorsement.pdf',
                'iplEquipment' => 'PL_Equipment_Liability_Endorsement.pdf'),
            'Dive Boat' 
                => array('template' => 'DiveBoatCOI',
                'header' => 'DiveBoatHeader.html',
                'footer' => 'DiveBoatFooter.html',
                'slWording' => 'SL_Wording.pdf',
                'policy' => 'Dive_Boat_Policy.pdf',
                'cover_letter' => 'Dive_Boat_Cover_Letter',
                'lheader' => 'letter_header.html',
                'lfooter' => 'letter_footer.html',
                'instruct' => 'Instructions_To_Insured.pdf',
                'aiTemplate' => 'DiveBoat_AI',
                'aiheader' => 'DiveBoat_AI_header.html',
                'aifooter' => 'DiveBoat_AI_footer.html',
                'eTemplate' => 'DiveBoat_Endorsement.tpl',
                'eheader' => 'DB_Endorsement_header.html',
                'efooter' => 'DB_Endorsement_footer.html',
                'lpTemplate' => 'DiveBoat_LP',
                'lpheader' => 'DiveBoat_LP_header.html',
                'lpfooter' => 'DiveBoat_LP_footer.html',
                'gtemplate' => 'Group_PL_COI',
                'gheader' => 'Group_header.html',
                'gfooter' => 'Group_footer.html',
                'nTemplate' => 'Group_PL_NI',
                'nheader' => 'Group_NI_header.html',
                'nfooter' => 'Group_NI_footer.html',
                'aniTemplate' => 'DiveBoat_ANI',
                'aniheader' => 'DB_Quote_ANI_header.html',
                'anifooter' => null,
                'waterEndorsement' => 'DB_In_Water_Crew_Endorsement.pdf'),
            'Dive Store'
                => array('template' => array('liability' => 'DiveStore_Liability_COI','property' => 'DiveStore_Property_COI'),
                        'header' => 'DiveStoreHeader.html',
                        'footer' => 'DiveStoreFooter.html',
                        'slWording' => 'SL_Wording.pdf',
                        'policy' => array('liability' => 'Dive_Store_Liability_Policy.pdf','property' => 'Dive_Store_Property_Policy.pdf'),
                        'cover_letter' => 'Dive_Store_Cover_Letter',
                        'lheader' => 'letter_header.html',
                        'lfooter' => 'letter_footer.html',
                        'aiTemplate' => 'DiveStore_AI',
                        'aiheader' => 'DiveStore_AI_header.html',
                        'aifooter' => 'DiveStore_AI_footer.html',
                        'lpTemplate' => 'DiveStore_LP',
                        'lpheader' => 'DiveStore_LP_header.html',
                        'lpfooter' => 'DiveStore_LP_footer.html',
                        'aniTemplate' => 'DiveStore_ANI',
                        'aniheader' => 'DS_Quote_ANI_header.html',
                        'anifooter' => null,
                        'gtemplate' => 'Group_PL_COI',
                        'gheader' => 'Group_header.html',
                        'gfooter' => 'Group_footer.html',),
            'Emergency First Response'
                => array('template' => 'Emergency_First_Response_COI',
                'header' => 'EFR_header.html',
                'footer' => 'EFR_footer.html',
                'slWording' => 'SL_Wording.pdf',
                'policy' => 'Policy.pdf',
                'aiTemplate' => 'EFR_AI',
                'aiheader' => 'EFR_AI_header.html',
                'aifooter' => 'EFR_AI_footer.html')
        );
            
        $this->jsonOptions = array('endorsement_options','additionalInsured','namedInsured','additionalNamedInsured','lossPayees','groupAdditionalInsured','layup_period','documents','stateTaxData', 'countrylist', 'start_date_range','quoteRequirement','endorsementCylinder','endorsementCoverage','upgradeExcessLiability','upgradeCareerCoverage','upgradecylinder','endorsementExcessLiability','previous_careerCoverage','dataGrid','attachmentsFieldnames','dsPropCentralFirePL','commands','dsglClaimAmountpaidanyamountsoutstanding','additionalLocations','dsPropCentralFireAL','groupPL','receipts','attachments', 'physical_state');
    }
        
        public function execute(array $data,Persistence $persistenceService) 
        {     
            $documents = array();
            $options = array();
            $this->logger->info("Template Data Source - ".print_r($data, true));
            $date = ''; 
            $this->logger->info("Executing Policy Document");
            $length = 0;
            $startDate = $data['start_date'];
            $endDate = $data['end_date'];
            if(isset($data['previous_policy_data'])){
                $previous_data = array();
                $previous_data = json_decode($data['previous_policy_data'],true);
                $length = sizeof($previous_data);
            }
            $this->setPolicyInfo($data,$persistenceService,$length);
            
            $dest = $data['dest'];
            if($this->type == 'quote' || $this->type == 'endorsementQuote'){
                $dest['relativePath'] = $dest['relativePath'].'Quote/';
                $dest['absolutePath'] = $dest['absolutePath'].'Quote/';
            }
            $state = "";
            if(isset($data['state'])){
              $selectQuery = "Select state_in_short FROM state_license WHERE state ='".$data['state']."'";
              $state = $data['state'];
            } 

            if(isset($data['business_state'])){
                 $selectQuery = "Select state_in_short FROM state_license WHERE state ='".$data['business_state']."'";
                 $state = $data['business_state'];
            }
            $resultSet = $persistenceService->selectQuery($selectQuery);
            $stateDetails = array();
            if($resultSet->count() == 0){
                $data['state_in_short'] = $data['state']; 
            }
            while ($resultSet->next()) {
                $stateDetails[] = $resultSet->current();
            }       
            if(isset($stateDetails) && count($stateDetails)>0){                
                    $data['state_in_short'] = $stateDetails[0]['state_in_short'];                
            }else{
                $data['state_in_short'] = isset($state) ? $state : "";
            }
            $this->logger->info("Data------------------ ".print_r($data,true));
            unset($data['dest']);

            $temp = $data;
            foreach ($this->jsonOptions as $val){
                if(array_key_exists($val, $temp) && is_array($temp[$val])){
                    $temp[$val] = json_encode($data[$val]);
                }
            }

            if($data['product'] == "Individual Professional Liability" || $data['product'] == "Emergency First Response"){
                if(isset($data['careerCoverage']) || isset($data['scubaFit']) || isset($data['cylinder']) || isset($data['equipment'])){
                    $this->logger->info("DOCUMENT careerCoverage || scubaFit || cylinder || equipment");
                    $coverageList = array();
                    array_push($coverageList,$data['careerCoverage']);
                  if($data['product'] == "Individual Professional Liability"){   
                        if(isset($data['scubaFit']) && $data['scubaFit'] == "scubaFitInstructor"){
                            $documents['scuba_fit_document'] = $this->copyDocuments($data,$dest['relativePath'],'iplScuba');
                            array_push($coverageList,$data['scubaFit']);
                        }
                        if(isset($data['cylinder']) && ($data['cylinder'] == "cylinderInspector" || $data['cylinder'] == "cylinderInstructor" || $data['cylinder'] == "cylinderInspectorAndInstructor")){
                            $documents['cylinder_document'] = $this->copyDocuments($data,$dest['relativePath'],'iplCylinder');
                            array_push($coverageList,$data['cylinder']);
                        }
                        
                        if(isset($data['equipment']) && $data['equipment'] == "equipmentLiabilityCoverage"){
                            $documents['equipment_liability_document'] = $this->copyDocuments($data,$dest['relativePath'],'iplEquipment');
                        }

                        if(isset($data['upgradeCareerCoverage'])){
                            if(!is_array($data['upgradeCareerCoverage'])){  
                                $coverageOnCsrReview = json_decode($data['upgradeCareerCoverage'],true);    
                                $data['upgradeCareerCoverage'] = $coverageOnCsrReview;  
                            }
                            $temp['upgradeCareerCoverageVal'] = $data['upgradeCareerCoverage']['label'];    
                            array_push($coverageList,$temp['upgradeCareerCoverageVal']);
                        }
                        if(isset($data['upgradecylinder']) && !is_array($data['upgradecylinder'])){    
                            $cylinderOnCsrReview = json_decode($data['upgradecylinder'],true);  
                            $data['upgradecylinder'] = $cylinderOnCsrReview;
                            $data['cylinder'] = $data['upgradecylinder']['value'];  
                        }   
                        if(isset($data['upgradeExcessLiability']) && !is_array($data['upgradeExcessLiability'])){ 
                            $excessLiabilityOnCsrReview = json_decode($data['upgradeExcessLiability'],true);    
                            $data['upgradeExcessLiability'] = $excessLiabilityOnCsrReview;
                            $data['excessLiability'] = $data['upgradeExcessLiability']['value'];
                        }
                    }
                    $result = $this->getCoverageName($coverageList,$data['product'],$persistenceService);
                    $result = json_decode($result,true);
                    if($data['product'] == "Individual Professional Liability"){
                        if(isset($result[$data['scubaFit']])){
                            $temp['scubaFitVal'] = $result[$data['scubaFit']];
                        }
                        if(isset($result[$data['cylinder']])){
                            $temp['cylinderPriceVal'] = $result[$data['cylinder']];
                        }
                    }
                    
                    if( isset($temp['upgradeCareerCoverageVal']) && isset($result[$temp['upgradeCareerCoverageVal']])){
                        $data['upgradeCareerCoverageVal'] = $result[$temp['upgradeCareerCoverageVal']];
                    }
                    $temp['careerCoverageVal'] = $result[$data['careerCoverage']];
                }

                if(isset($temp['AdditionalInsuredOption']) && $temp['AdditionalInsuredOption'] == 'newListOfAdditionalInsureds'){
                    $this->logger->info("DOCUMENT AdditionalInsuredOption");
                    $documents['additionalInsured_document'] = $this->generateDocuments($temp,$dest,$options,'aiTemplate','aiheader','aifooter');
                }

                if(isset($this->template[$temp['product']]['blanketForm'])){
                    $this->logger->info("DOCUMENT blanketForm");
                    $documents['blanket_document'] = $this->copyDocuments($temp,$dest['relativePath'],'blanketForm');
                }
            }
            else if($data['product'] == "Dive Boat"){
                if(isset($this->template[$data['product']]['instruct'])){
                    $this->logger->info("DOCUMENT instruct");
                    $documents['instruct'] = $this->copyDocuments($data,$dest['relativePath'],'instruct');
                }

                if(isset($temp['additionalInsured']) && $temp['additional_insured_select'] == 'newListOfAdditionalInsureds'){
                    $this->logger->info("DOCUMENT additionalInsured");
                    $documents['additionalInsured_document'] = $this->generateDocuments($temp,$dest,$options,'aiTemplate','aiheader','aifooter');
                }


                if(isset($temp['groupPL']) && $temp['groupProfessionalLiability'] == 'yes'){
                    $this->logger->info("DOCUMENT groupPL");
                    $document['group_coi_document'] = $this->generateDocuments($temp,$dest,$options,'gtemplate','gheader','gfooter');


                    if(isset($temp['additionalNamedInsured']) && $temp['additional_named_insureds_option'] == 'yes'){
                    $this->logger->info("DOCUMENT additionalNamedInsured");
                    $documents['additionalNamedInsured_document'] = $this->generateDocuments($temp,$dest,$options,'aniTemplate','aniheader','anifooter');
                    }

                    if(isset($temp['namedInsureds']) && $temp['named_insureds'] == 'yes'){
                    $this->logger->info("DOCUMENT namedInsured"); 
                    $documents['named_insured_document'] = $this->generateDocuments($temp,$dest,$options,'nTemplate','nheader','nfooter');
                    }
                }

                if(isset($temp['loss_payees']) && $temp['loss_payees'] == 'yes'){
                    $documents['loss_payee_document'] = $this->generateDocuments($temp,$dest,$options,'lpTemplate','lpheader','lpfooter');
                }

                if(isset($this->template[$temp['product']]['cover_letter'])){
                    $this->logger->info("DOCUMENT cover_letter");
                    $documents['cover_letter'] = $this->generateDocuments($temp,$dest,$options,'cover_letter','lheader','lfooter');
                }

                if(isset($temp['CrewInWaterCount'])){
                    $documents['water_endorsement'] = $this->copyDocuments($temp,$dest['relativePath'],'waterEndorsement');
                }
            }
            else if($data['product'] == "Dive Store"){
                if(isset($temp['additionalInsured']) && (isset($temp['additionalInsuredSelect']) && $temp['additionalInsuredSelect']=="yes")){
                    $this->logger->info("DOCUMENT additionalInsured");
                    $documents['additionalInsured_document'] = $this->generateDocuments($temp,$dest,$options,'aiTemplate','aiheader','aifooter');
                }

                if(isset($temp['lossPayees']) && $temp['lossPayeesSelect']=="yes"){
                    $this->logger->info("DOCUMENT lossPayees");
                    $documents['loss_payee_document'] = $this->generateDocuments($temp,$dest,$options,'lpTemplate','lpheader','lpfooter');
                }
                
                if(isset($temp['additionalLocations']) && $temp['additionalLocations']=="yes"){
                    $this->logger->info("DOCUMENT additionalLocations (additional named insuredes");
                    $documents['additionalLocations_document'] = $this->generateDocuments($temp,$dest,$options,'aniTemplate','aniheader','anifooter');
                }

                if(isset($data['groupPL']) && $temp['groupProfessionalLiabilitySelect']=='yes'){
                    $this->logger->info("DOCUMENT groupPL");
                    $document['group_coi_document'] = $this->generateDocuments($temp,$dest,$options,'gtemplate','gheader','gfooter');
                }

                if(isset($this->template[$temp['product']]['cover_letter'])){
                    $this->logger->info("DOCUMENT cover_letter");
                    $documents['cover_letter'] = $this->generateDocuments($temp,$dest,$options,'cover_letter','lheader','lfooter');
                }
            }

            if($this->type != 'quote'){
                if(isset($temp['liability'])){
                    $this->logger->info("DOCUMENT liability_coi_document");
                    $documents['liability_coi_document'] = $this->generateDocuments($temp,$dest,$options,'template','header','footer','liability');
                    $this->logger->info("DOCUMENT liability_policy_document");
                    $documents['liability_policy_document'] = $this->copyDocuments($temp,$dest['relativePath'],'policy','liability');
                }
                
                if(isset($temp['property'])){
                    $this->logger->info("DOCUMENT property_coi_document");
                    $documents['property_coi_document']  = $this->generateDocuments($temp,$dest,$options,'template','header','footer','property');
                    $this->logger->info("DOCUMENT property_policy_document");
                    $documents['property_policy_document'] = $this->copyDocuments($temp,$dest['relativePath'],'policy','property');
                }
            }

            if(!isset($documents['property_coi_document']) && !isset($documents['liability_coi_document'])){
                $this->logger->info("DOCUMENT coi_document");
                $this->logger->info("Policy Documnet Generation");
                if($this->type == 'endorsement' || $this->type == 'endorsementQuote'){
                    $endorsementFileName = 'Endorsement - '.$length;
                    $documents[$endorsementFileName] = $this->generateDocuments($temp,$dest,$options,'template','header','footer',$length);
                }else{
                    $documents['coi_document']  = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
                }
                if($this->type != 'quote' || $this->type != 'endorsementQuote')
                {
                    $documents['policy_document'] = $this->copyDocuments($temp,$dest['relativePath'],'policy');
                }
            }
            
            if($this->type == 'lapse'){
                $this->logger->info("DOCUMENT lapse");
                return $this->generateDocuments($data,$dest,$options,'ltemplate','lheader','lfooter');
            }
            
            // if($this->type == 'endorsement'){
            //     $this->logger->info("DOCUMENT endorsement");
            //     $data['documents']['endorsement'] = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
            //     return $data;
            // }

            if(isset($this->template[$temp['product']]['slWording'])){        
                if($temp['product'] == 'Dive Boat' || $temp['product'] == 'Dive Store'){
                    if($temp['business_state'] == 'California'){
                            $documents['slWording'] = $this->copyDocuments($temp,$dest['relativePath'],'slWording');
                        }
                    }
                else{
                    if($temp['state'] == 'California'){
                            $documents['slWording'] = $this->copyDocuments($temp,$dest['relativePath'],'slWording');
                    }
                }
            }
            $this->logger->info("temp".print_r($data,true));
            if($this->type == 'endorsement' || $this->type == 'endorsementQuote'){
                $data['documents'] = json_decode($data['documents'],true);
                $data['documents'] = array_merge($data['documents'],$documents);
            }else{
                $data['documents'] = $documents;
            }
            $data['endorsement_options']['modify_personalInformation'] = false;
            $data['endorsement_options']['modify_coverage'] = false;
            $data['endorsement_options']['modify_additionalInsured'] = false;
            $data['policyStatus'] = "In Force";
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            $this->logger->info("Policy Documnet Generation",print_r($data,true));
            return $data;
        }
                 
        protected function setPolicyInfo(&$data,$persistenceService,$length)
        {
                if($this->type != "quote" || $this->type != "lapse" || $this->type != 'endorsementQuote'){
                    $coi_number = $this->generateCOINumber($data,$persistenceService);
                    if($this->type == 'endorsement'){
                        $data['certificate_no'] = $data['certificate_no'].' - '.$length;
                    }else{
                        $data['certificate_no'] = $coi_number;
                    }
                    
                }
                
                $date=date_create($data['start_date']);
                $data['start_date'] = date_format($date,"m/d/Y");
                $date=date_create($data['end_date']);
                $data['end_date'] = date_format($date,"m/d/Y");
                
                if(isset($data['fileId'])){
                    $data['uuid'] = $data['fileId'];
                }
                if(!isset($data['uuid'])){
                    $data['uuid'] = UuidUtil::uuid();
                }
                
                $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] :AuthContext::get(AuthConstants::ORG_UUID));        
                $data['orgUuid'] = $orgUuid;
                
                if($this->type != "lapse"){
                    $license_number = $this->getLicenseNumber($data,$persistenceService);
                    $policyDetails = $this->getPolicyDetails($data,$persistenceService);
                    $data['license_number'] = $license_number;
                    
                    if($policyDetails){
                        $data['policy_id'] = $policyDetails['policy_number'];
                        $data['carrier'] = $policyDetails['carrier'];
                    } 
                }
                
                $data['dest'] = ArtifactUtils::getDocumentFilePath($this->destination,$data['fileId'],array('orgUuid' => $orgUuid));
                
                return $data;
        }
            
        private function generateCOINumber($data,$persistenceService)
        {  
                $sequence = 0;
                $year = date('Y', strtotime($data['end_date']));
                $persistenceService->beginTransaction();
                try{ 
                    $select1 = "Select * FROM certificate_of_insurance_number WHERE product ='".$data['product']."' AND year = $year FOR UPDATE";
                    $this->logger->info("QUERY POLICY - ".print_r($select1,true));
                    $result1 = $persistenceService->selectQuery($select1); 
                    while ($result1->next()) {
                        $details[] = $result1->current();
                    }
                    if($result1->count() == 0){
                        $sequence ++;
                        $select2 = "INSERT INTO certificate_of_insurance_number (`product`,`year`,`sequence`) VALUES ('".$data['product']."', $year, $sequence)";
                        $result2 = $persistenceService->insertQuery($select2);
                    }else{ 
                        $sequence = $details[0]['sequence'];
                        $sequence ++;
                        $select3 = "UPDATE `certificate_of_insurance_number` SET `sequence` = $sequence WHERE product ='".$data['product']."' AND year = $year";
                        $result3 = $persistenceService->updateQuery($select3);
                    }
                    $persistenceService->commit();
                }catch(Exception $e){
                    print_r($e->getMessage());
                    $persistenceService->rollback();
                    throw $e;
                }
                $coi_number = $year. str_pad($sequence,5,'0',STR_PAD_LEFT);
                return $coi_number;
        }
            
        private function getCoverageName($data,$product,$persistenceService){
            $selectQuery = "SELECT group_concat(distinct concat('\"',`key`,'\":\"',coverage,'\"')) as name FROM premium_rate_card WHERE `key` in ('".implode("','", $data) . "')  AND product = '".$product."'";
            $resultQuery = $persistenceService->selectQuery($selectQuery);
            while ($resultQuery->next()) {
                $coverageName[] = $resultQuery->current();
            }
            if($resultQuery->count()!=0){
                return '{'.$coverageName[0]['name'].'}';
            }
            
        }
        private function getLicenseNumber($data,$persistenceService)
        {
            $selectQuery = "Select * FROM state_license WHERE state = '".$data['state']."'";
            $resultQuery = $persistenceService->selectQuery($selectQuery);
            while ($resultQuery->next()) {
                $stateLicenseDetails[] = $resultQuery->current();
            }
            if($resultQuery->count()!=0){
                return $stateLicenseDetails[0]['license_number'];
            }
            return "";
        }
        
        private function getPolicyDetails($data,$persistenceService)
        {  
            $selectQuery = "Select carrier,policy_number FROM carrier_policy WHERE product ='".$data['product']."' AND now() BETWEEN start_date AND end_date;";
            $resultQuery = $persistenceService->selectQuery($selectQuery); 
            while ($resultQuery->next()) {
                $policyDetails[] = $resultQuery->current();
            }
            if($resultQuery->count()!=0){
                return $policyDetails[0];
            }
            return NULL;
        }
        
        protected function generateDocuments(&$data,$dest,$options,$templateKey,$headerKey = null,$footerKey = null,$indexKey = null,$length = 0){
            $this->logger->info("Generate documents parameters templatekey is : ".print_r($templateKey, true));
            $this->logger->info("policy document destination is : ".print_r($dest, true));
            $this->logger->info("policy document options is : ".print_r($options, true));
            $this->logger->info("policy document data is : ".print_r($data, true));
            $this->logger->info("Product : ".print_r($this->template[$data['product']], true));
            $this->logger->info("TEMPLATE KEY ARRAY : ".print_r($this->template[$data['product']][$templateKey],true));
            $this->logger->info("index key : ".print_r($indexKey,true));
            
            if(isset($indexKey)){
                $this->logger->info("template with indexKey");
                $template =  $this->template[$data['product']][$templateKey][$indexKey];
            }else{
                $this->logger->info("template without indexKey");
                $template =  $this->template[$data['product']][$templateKey];
            }
            $this->logger->info("template slected: ".print_r($template, true));
            $docDest = $dest['absolutePath'].$template.'.pdf';
            
            if($template == 'Group_PL_COI'){
                $options['generateOptions'] = array('disable_smart_shrinking' => 1);
            }
            
            if(isset($headerKey) && $headerKey !=null){ 
                $options['header'] =  $this->template[$data['product']][$headerKey];
            }
            if(isset($headerKey) && $footerKey !=null){ 
                $options['footer'] =  $this->template[$data['product']][$footerKey];
            }
            if(!file_exists($docDest)){
                $this->documentBuilder->generateDocument($template,$data,$docDest,$options);
            }
            if($this->type == 'lapse'){
                $data['documents']['lapse_document'] = $dest['relativePath'].$template.'.pdf'; 
                return $data;
            }
            if($this->type == 'endorsement' || $this->type == 'endorsementQuote'){
                return $dest['relativePath'].$template.'-'.$length.'.pdf';
            }else{
                return $dest['relativePath'].$template.'.pdf';
            }
            
        }
        
        private function copyDocuments(&$data,$dest,$fileKey,$indexKey =null){
            if(isset($indexKey)){
                $file =  $this->template[$data['product']][$fileKey][$indexKey];
            }else{
                $file =  $this->template[$data['product']][$fileKey];
            }
            if(!file_exists($dest)){
                $this->documentBuilder->copyTemplateToDestination($file,$dest);
            }
            return $dest.$file;
        }
    }
        