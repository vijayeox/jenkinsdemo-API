<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Utils\FileUtils;
use Oxzion\PDF\PDF_Watermarker;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\CommentTrait;

class PolicyDocument extends AbstractDocumentAppDelegate
{
    use FileTrait;
    use CommentTrait;
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
                'card' => 'PocketCard',
                'slWording' => 'SL_Wording.pdf',
                'policy' => 'Individual_Professional_Liability_Policy.pdf',
                'aiTemplate' => 'Individual_PL_AI',
                'blanketForm' => 'Individual_AI_Blanket_Endorsement.pdf',
                'aiheader' => 'IPL_AI_header.html',
                'aifooter' => 'IPL_AI_footer.html',
                'iplScuba' => 'PL_Scuba_Fit_Endorsement.pdf',
                'iplCylinder' => 'PL_Cylinder_Endorsement.pdf',
                'iplEquipment' => 'PL_Equipment_Liability_Endorsement.pdf'),
            'Dive Boat'
                => array('template' => 'DiveBoatCOI',
                'header' => 'DiveBoatHeader.html',
                'footer' => 'DiveBoatFooter.html',
                'card' => 'PocketCard',
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
                'aniheader' => 'DiveBoat_ANI_header.html',
                'anifooter' => null,
                'waterEndorsement' => 'DB_In_Water_Crew_Endorsement.pdf',
                'blanketForm' => 'DB_AI_Blanket_Endorsement.pdf',
                'groupExclusions' => 'Group_Exclusions.pdf'),
            'Dive Store'
                => array('template' => array('liability' => 'DiveStore_Liability_COI','property' => 'DiveStore_Property_COI'),
                        'header' => 'DiveStoreHeader.html',
                        'footer' => 'DiveStoreFooter.html',
                        'propertyHeader' => 'DiveStorePropertyHeader.html',
                        'propertyFooter' => 'DiveStorePropertyFooter.html',
                        'psTemplate' => 'DiveStore_DCPS',
                        'psHeader' => 'DiveStore_DCPS_header.html',
                        'psFooter' => 'DiveStore_DCPS_footer.html',
                        'card' => 'PocketCard',
                        'slWording' => 'SL_Wording.pdf',
                        'policy' => array('liability' => 'Dive_Store_Liability_Policy.pdf','property' => 'Dive_Store_Property_Policy.pdf'),
                        'cover_letter' => 'Dive_Store_Cover_Letter',
                        'lheader' => 'letter_header.html',
                        'lfooter' => 'letter_footer.html',
                        'instruct' => 'Instructions_To_Insured.pdf',
                        'aiTemplate' => 'DiveStore_AI',
                        'aiheader' => 'DiveStore_AI_header.html',
                        'aifooter' => 'DiveStore_AI_footer.html',
                        'lpTemplate' => 'DiveStore_LP',
                        'lpheader' => 'DiveStore_LP_header.html',
                        'lpfooter' => 'DiveStore_LP_footer.html',
                        'nTemplate' => 'Group_PL_NI',
                        'nheader' => 'Group_DS_NI_header.html',
                        'nfooter' => 'Group_NI_footer.html',
                        'aniTemplate' => 'DiveStore_ANI',
                        'aniheader' => 'DS_Quote_ANI_header.html',
                        'anifooter' => null,
                        'gtemplate' => 'Group_PL_COI_DS',
                        'gheader' => 'Group_header_DS.html',
                        'gfooter' => 'Group_footer.html',
                        'ganiTemplate' => 'Group_ANI',
                        'ganiheader' => 'Group_DS_ANI_header.html',
                        'ganifooter' => 'Group_ANI_footer.html',
                        'gaitemplate' => 'Group_AI',
                        'gaiheader' => 'Group_AI_header.html',
                        'gaifooter' => 'Group_AI_footer.html',
                        'alheader' => 'DiveStore_AL_header.html',
                        'alfooter' => 'DiveStore_AL_footer.html',
                        'alTemplate' => 'DiveStore_AdditionalLocations',
                        'GLblanketForm' => 'DS_GROUP_AI_Blanket_Endorsement.pdf',
                        'blanketForm' => 'GL_AI_Blanket.pdf',
                        'travelAgentEO' => 'Travel_Agents_PL_Endorsement.pdf',
                        'groupExclusions' => 'Group_Exclusions.pdf',
                        'AutoLiability'=>'DS_NonOwned_Auto_Liability.pdf',
                        'roster' => 'Roster_Certificate',
                        'rosterHeader' => 'Roster_header_DS.html',
                        'rosterFooter' => 'Roster_footer.html',
                        'rosterPdf' => 'Roster.pdf'),
            'Group Professional Liability'
                => array('template' => array('liability' => 'DiveStore_Liability_COI','property' => 'DiveStore_Property_COI'),
                        'header' => 'DiveStoreHeader.html',
                        'footer' => 'DiveStoreFooter.html',
                        'propertyHeader' => 'DiveStorePropertyHeader.html',
                        'propertyFooter' => 'DiveStorePropertyFooter.html',
                        'psTemplate' => 'Group_DCPS',
                        'psHeader' => 'DiveStore_DCPS_header.html',
                        'psFooter' => 'DiveStore_DCPS_footer.html',
                        'card' => 'PocketCard',
                        'slWording' => 'SL_Wording.pdf',
                        'policy' => array('liability' => 'Dive_Store_Liability_Policy.pdf','property' => 'Dive_Store_Property_Policy.pdf'),
                        'lheader' => 'letter_header.html',
                        'lfooter' => 'letter_footer.html',
                        'aiTemplate' => 'DiveStore_AI',
                        'aiheader' => 'DiveStore_AI_header.html',
                        'aifooter' => 'DiveStore_AI_footer.html',
                        'lpTemplate' => 'DiveStore_LP',
                        'lpheader' => 'DiveStore_LP_header.html',
                        'lpfooter' => 'DiveStore_LP_footer.html',
                        'nTemplate' => 'Group_PL_NI',
                        'nheader' => 'Group_DS_NI_header.html',
                        'nfooter' => 'Group_NI_footer.html',
                        'aniTemplate' => 'DiveStore_ANI',
                        'aniheader' => 'DS_Quote_ANI_header.html',
                        'anifooter' => null,
                        'gtemplate' => 'Group_PL_COI_DS',
                        'gheader' => 'Group_header_DS.html',
                        'gfooter' => 'Group_footer.html',
                        'ganiTemplate' => 'Group_ANI',
                        'ganiheader' => 'Group_DS_ANI_header.html',
                        'ganifooter' => 'Group_ANI_footer.html',
                        'gaitemplate' => 'Group_AI',
                        'gaiheader' => 'Group_AI_header.html',
                        'gaifooter' => 'Group_AI_footer.html',
                        'alheader' => 'DiveStore_AL_header.html',
                        'alfooter' => 'DiveStore_AL_footer.html',
                        'alTemplate' => 'DiveStore_AdditionalLocations',
                        'GLblanketForm' => 'DS_GROUP_AI_Blanket_Endorsement.pdf',
                        'blanketForm' => 'GL_AI_Blanket.pdf',
                        'travelAgentEO' => 'Travel_Agents_PL_Endorsement.pdf',
                        'groupExclusions' => 'Group_Exclusions.pdf',
                        'AutoLiability'=>'DS_NonOwned_Auto_Liability.pdf',
                        'roster' => 'Roster_Certificate',
                        'rosterHeader' => 'Roster_header_DS.html',
                        'rosterFooter' => 'Roster_footer.html',
                        'rosterPdf' => 'Roster.pdf'),
            'Emergency First Response'
                => array('template' => 'Emergency_First_Response_COI',
                'header' => 'EFR_header.html',
                'footer' => 'EFR_footer.html',
                'card' => 'PocketCard',
                'slWording' => 'SL_Wording.pdf',
                'policy' => 'Policy.pdf',
                'aiTemplate' => 'EFR_AI',
                'blanketForm' => 'EFR_AI_Blanket_Endorsement.pdf',
                'aiheader' => 'EFR_AI_header.html',
                'aifooter' => 'EFR_AI_footer.html')
        );

        $this->endorsementOptions = array('modify_personalInformation','modify_coverage','modify_additionalInsured','modify_businessAndPolicyInformation','modify_boatUsageCaptainCrewSchedule','modify_boatDeatails','modify_additionalInsured','modify_lossPayees','modify_groupProfessionalLiability');
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

            $this->processSurplusYear($data);

            if(isset($data['update_date'])){
                $updateDate = $data['update_date'];
            }
            if(isset($data['previous_policy_data'])){
                $previous_data = array();
                $previous_data = is_string($data['previous_policy_data']) ? json_decode($data['previous_policy_data'],true) : $data['previous_policy_data'];
                $length = sizeof($previous_data);
            }else{
                $previous_data = array();
            }
            if(isset($data['endorsement_options'])){
                $endorsementOptions = is_array($data['endorsement_options']) ?  $data['endorsement_options'] : json_decode($data['endorsement_options'],true);
            }else{
                $endorsementOptions = null;
            }

            $this->setPolicyInfo($data,$persistenceService,$endorsementOptions);
            $dest = $data['dest'];
            if($this->type == 'quote' || $this->type == 'endorsementQuote'){
                FileUtils::deleteDirectoryContents($dest['absolutePath'].'Quote/');
                $dest['relativePath'] = $dest['relativePath'].'Quote/';
                $dest['absolutePath'] = $dest['absolutePath'].'Quote/';
            }

            if (isset($data['regeneratePolicy'])){
                if(!is_null($data['regeneratePolicy']) && !empty($data['regeneratePolicy']) && !isset($data['endorsement_options']) ) {
                    $coiDocument = $dest['absolutePath'].'ProfessionalLiabilityCOI.pdf';
                    if (file_exists($coiDocument)) {
                        FileUtils::deleteFile('ProfessionalLiabilityCOI.pdf',$dest['absolutePath']);
                    }
                }else{
                        if(is_null($endorsementOptions)){
                            $workflowInstUuid = $this->getWorkflowInstanceByFileId($data['fileId'],'In Progress');
                            if( count($workflowInstUuid) > 0 && (isset($workflowInstUuid[0]['process_instance_id']))){
                                $dest['absolutePath'] .= $workflowInstUuid[0]['process_instance_id']."/";
                                $dest['relativePath'] .= $workflowInstUuid[0]['process_instance_id']."/";
                                FileUtils::deleteFile('ProfessionalLiabilityCOI.pdf',$dest['absolutePath']);
                            }
                        }
                }
            }

            if(isset($data['state'])){
                $data['state_in_short'] = $this->getStateInShort($data['state'],$persistenceService);
            }

            if(isset($data['business_state']) && $data['business_state'] !=""){
                $data['state_in_short'] = $this->getStateInShort($data['business_state'],$persistenceService);
            }
            $this->logger->info("Data------------------ ".print_r($data,true));
            unset($data['dest']);


            $temp = $data;
            $this->processData($temp);
            if($data['product'] == 'Dive Store'){
                $this->getDSLiabilityPolicyDetails($data,$temp,$persistenceService);
            }

            if($data['product'] == "Individual Professional Liability" || $data['product'] == "Emergency First Response"){
                if(isset($data['careerCoverage']) || isset($data['scubaFit']) || isset($data['cylinder']) || isset($data['equipment'])|| isset($data['excessLiability'])){
                    $this->logger->info("DOCUMENT careerCoverage || scubaFit || cylinder || equipment");
                    $coverageList = array();
                    if($data['product'] == "Individual Professional Liability"){
                        array_push($coverageList,$data['careerCoverage']);
                        if(isset($data['scubaFit']) && ($data['scubaFit'] == "scubaFitInstructor" || $data['scubaFit'] == "scubaFitInstructorDeclined")){
                            if($data['scubaFit'] == "scubaFitInstructor"){
                                $documents['scuba_fit_document'] = $this->copyDocuments($data,$dest['relativePath'],'iplScuba');
                            }
                            array_push($coverageList,$data['scubaFit']);
                        }
                        if(isset($data['cylinder']) && ($data['cylinder'] == "cylinderInspector" || $data['cylinder'] == "cylinderInspectionInstructor" || $data['cylinder'] == "cylinderInspectorAndInstructor")){
                            $documents['cylinder_document'] = $this->copyDocuments($data,$dest['relativePath'],'iplCylinder');
                            array_push($coverageList,$data['cylinder']);
                        }
                        if(isset($data['equipment']) && $data['equipment'] == "equipmentLiabilityCoverage"){
                            $documents['equipment_liability_document'] = $this->copyDocuments($data,$dest['relativePath'],'iplEquipment');
                        }

                        if(isset($data['excessLiability'])){
                            array_push($coverageList,$data['excessLiability']);
                        }
                        if(isset($data['tecRecEndorsment'])){
                            array_push($coverageList,$data['tecRecEndorsment']);
                        }
                        if(isset($data['equipment'])){
                            array_push($coverageList,$data['equipment']);
                        }
                    }
                    if($data['product'] == "Emergency First Response"){
                        if(isset($data['excessLiability'])){
                            array_push($coverageList,$data['excessLiability']);
                        }
                    }
                    $result = $this->getCoverageName($coverageList,$data['product'],$persistenceService);
                    $result = json_decode($result,true);
                    if($data['product'] == "Individual Professional Liability"){
                        if(isset($result[$data['scubaFit']])){
                            $temp['scubaFitVal'] = $result[$data['scubaFit']];
                        }
                        if(isset($result[$data['tecRecEndorsment']])){
                            $temp['tecRecVal'] = $result[$data['tecRecEndorsment']];
                        }
                        if(isset($result[$data['cylinder']]) && !isset($temp['cylinderPriceVal'])){
                            $temp['cylinderPriceVal'] = $result[$data['cylinder']];
                        }
                        if(isset($result[$data['excessLiability']])){
                            $temp['excessLiabilityVal'] = $result[$data['excessLiability']];
                        }
                        if(isset($result[$data['tecRecEndorsment']])){
                            $temp['tecRecVal'] = $result[$data['tecRecEndorsment']];
                        }
                        if(isset($result[$data['equipment']])){
                            $temp['equipmentVal'] = $result[$data['equipment']];
                        }
                        $temp['careerCoverageVal'] = $result[$data['careerCoverage']];
                    }
                    if($data['product'] == "Emergency First Response"){
                        if(isset($result[$data['excessLiability']])){
                            $temp['excessLiabilityVal'] = $result[$data['excessLiability']];
                        }
                    }

                    if(!empty($previous_data)) {
                        $policy =array();
                        $policy =  $previous_data[0];
                        if(is_string($data['previous_policy_data'])){
                                $data['previous_policy_data'] = json_decode($data['previous_policy_data'],true);
                        }

                        if($data['product'] == "Individual Professional Liability"){
                            $this->processUpgradeCoverages($data,$policy,$result,'previous_careerCoverage','careerCoverageName','careerCoverage',array());

                            $this->processUpgradeCoverages($data,$policy,$result,'previous_scubaFit','scubaCoverageName','scubaFit',array());

                            $this->processUpgradeCoverages($data,$policy,$result,'previous_cylinder','cylinderCoverageName','cylinder',array());

                            $this->processUpgradeCoverages($data,$policy,$result,'previous_equipment','equipmentCoverageName','equipment',array());

                            $this->processUpgradeCoverages($data,$policy,$result,'previous_tecRecEndorsment','tecRecCoverageName','tecRecEndorsment',array());
                        }

                        //  Common for both IPL and EFR
                        if($policy['prevSingleLimit'] != $data['single_limit']){
                                $upgrade = array("upgraded_single_limit" => $data['single_limit'],"upgraded_annual_aggregate" => $data['annual_aggregate']);
                                $data['previous_policy_data'][0] = array_merge($data['previous_policy_data'][0],$upgrade);
                        }

                        $temp['previous_policy_data'] = json_encode($data['previous_policy_data']);
                    }
                }


                if(isset($temp['AdditionalInsuredOption']) && ($temp['AdditionalInsuredOption'] == 'addAdditionalInsureds')){
                    $this->logger->info("DOCUMENT AdditionalInsuredOption");
                    $documents['additionalInsured_document'] = array($this->generateDocuments($temp,$dest,$options,'aiTemplate','aiheader','aifooter'));
                }

                 if(isset($this->template[$temp['product']]['blanketForm'])){
                    if (!isset($data['regeneratePolicy']) || (isset($data['regeneratePolicy']) && empty($data['regeneratePolicy']))) {
                        $this->logger->info("DOCUMENT blanketForm");
                        $documents['blanket_document'] = $this->copyDocuments($temp,$dest['relativePath'],'blanketForm');
                    }else{
                        $existingBlanketForm = $dest['relativePath'].$this->template[$temp['product']]['blanketForm'];
                        $this->logger->info("D::::".print_r($existingBlanketForm,true));
                        $this->logger->info("DOCUMENTS::::".print_r(file_exists($existingBlanketForm),true));
                        $documents['blanket_document'] = $existingBlanketForm;
                    }
                }
                $this->logger->info("DOCUMENT blanketForm".print_r($documents,true));
                if(isset($this->template[$temp['product']]['card'])){
                    if (!isset($data['regeneratePolicy']) || (isset($data['regeneratePolicy']) && empty($data['regeneratePolicy']))) {
                        $this->logger->info("generate pocket card");
                        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
                        $template = $this->template[$temp['product']]['card'];
                        $options = array();
                        $docDest = $dest['absolutePath'].$template.'.pdf';
                        $NewData = array();
                        $NewData[0]['email'] = $data['email'];
                        $NewData[0]['padi'] = $data['padi'];
                        $NewData[0]['certificate_no'] = $data['certificate_no'];
                        $NewData[0]['start_date'] = $data['start_date'];
                        $NewData[0]['end_date'] = $data['end_date'];
                        $NewData[0]['firstname'] = $data['firstname'];
                        $NewData[0]['lastname'] = $data['lastname'];
                        $NewData[0]['address1'] = $data['address1'];
                        $NewData[0]['address2'] = isset($data['address2']) ? $data['address2'] : '';
                        $NewData[0]['city'] = $data['city'];
                        $NewData[0]['state'] = $data['state'];
                        $NewData[0]['zip'] = $data['zip'];
                        $NewData[0]['country'] = $data['country'];
                        $NewData[0]['product'] = $data['product'];
                        $NewData[0]['product_email_id'] = $data['product_email_id'];
                        $NewData[0]['entity_name'] = 'Pocket Card Job';
                        $newData = json_encode($NewData);
                        $docdata = array('data' => $newData);
                        unset($NewData);
                        unset($newData);
                        $this->logger->info("Data is: ".print_r($docdata, true));
                        $this->documentBuilder->generateDocument($template, $docdata, $docDest, $options);
                        $documents['PocketCard'] = $dest['relativePath'].$template.'.pdf';
                    }else{
                        $existingPocketCard = $dest['relativePath'].$this->template[$temp['product']]['card'].'.pdf';
                        $this->logger->info("PocketCard::::".print_r(file_exists($existingPocketCard),true));
                        $documents['PocketCard'] = $existingPocketCard;                        
                    }
                }
            }
            else if($data['product'] == "Dive Boat"){
                if(isset($this->template[$data['product']]['instruct'])){
                    $this->logger->info("DOCUMENT instruct");
                    $documents['instruct'] = $this->copyDocuments($data,$dest['relativePath'],'instruct');
                }

                if($this->type != 'endorsementQuote' && $this->type != 'endorsement'){
                    if(isset($temp['additionalInsured']) && $temp['additional_insured_select'] == 'addAdditionalInsureds'){
                    $this->logger->info("DOCUMENT additionalInsured");
                    $temp['additionalInsured'] = json_decode($temp['additionalInsured'],true);
                    for($i = 0;$i< sizeof($temp['additionalInsured']);$i++){
                        $temp['additionalInsured'][$i]['state_in_short'] = $this->getStateInShort($temp['additionalInsured'][$i]['state'],$persistenceService);
                    }
                    $temp['additionalInsured'] = json_encode($temp['additionalInsured']);
                    $documents['additionalInsured_document'] = $this->generateDocuments($temp,$dest,$options,'aiTemplate','aiheader','aifooter');
                    }
                }
                if(isset($temp['groupPL']) && $temp['groupProfessionalLiability'] == 'yes'){
                    $this->generateGroupDocuments($data,$temp,$documents,$previous_data,$endorsementOptions,$dest,$options,$length);
                }

               if(isset($temp['additionalNamedInsured']) && $temp['additional_named_insureds_option'] == 'yes'){
                    if($this->type != 'endorsementQuote' && $this->type != 'endorsement'){
                        $documents['ani_document'] = $this->generateDocuments($temp,$dest,$options,'aniTemplate','aniheader','anifooter');
                    }
                }
                if(isset($temp['loss_payees']) && $temp['loss_payees'] == 'yes'){
                    if($this->type != 'endorsementQuote' && $this->type != 'endorsement'){
                         $documents['loss_payee_document'] = $this->generateDocuments($temp,$dest,$options,'lpTemplate','lpheader','lpfooter');
                    }
                }

                if(isset($this->template[$temp['product']]['cover_letter'])){
                    $this->logger->info("DOCUMENT cover_letter");
                    $documents['cover_letter'] = $this->generateDocuments($temp,$dest,$options,'cover_letter','lheader','lfooter');
                }

                if($this->type == 'quote' || $this->type == 'endorsementQuote'){
                    if(!isset($temp['CrewInBoatCount']) || $temp['CrewInBoatCount'] == ''){
                        $documents['boat_acknowledgement'] = $this->copyDocuments($temp,$dest['relativePath'],'boatAcknowledgement');
                    }
                    if(!isset($temp['CrewInWaterCount']) || $temp['CrewInWaterCount'] == ''){
                         $documents['water_acknowledgement'] = $this->copyDocuments($temp,$dest['relativePath'],'waterAcknowledgement');
                    }

                    if(isset($data['quoteInfo'])){
                        if(is_string($data['quoteInfo'])){
                            $quoteInfo = json_decode($data['quoteInfo'],true);
                        } else{
                            $quoteInfo = $data['quoteInfo'];
                        }
                        for($i = 0;$i < sizeof($quoteInfo);$i++){
                            if($quoteInfo['Hurricane Questionnaire.']){
                                $documents['hurricane_questionnaire'] = $this->copyDocuments($temp,$dest['relativePath'],'hurricaneQuestionnaire');
                            }
                        }
                    }
                }

                if(isset($temp['CrewInWaterCount']) && $temp['CrewInWaterCount'] != ''){
                    $documents['water_endorsement_certificate'] = $this->copyDocuments($temp,$dest['relativePath'],'waterEndorsement');
                }

                if(isset($this->template[$temp['product']]['blanketForm'])){
                    $this->logger->info("DOCUMENT blanketForm");
                    $documents['blanket_document'] = $this->copyDocuments($temp,$dest['relativePath'],'blanketForm');
                }

                if(isset($temp['groupPL']) && !empty($temp['groupPL'])){
                    if (isset($this->template[$temp['product']]['card'])) {
                        $this->logger->info('inside dive boat pocket card');
                        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
                        //$dest = ArtifactUtils::getDocumentFilePath($this->destination, $data['uuid'], array('orgUuid' => $orgUuid));
                        $template = $this->template[$temp['product']]['card'];
                        $options = array();
                        $docDest = $dest['absolutePath'].$template.'.pdf';
                        $result = $this->newDataArray($temp);
                        if(!isset($result) || empty($result)){
                            $this->logger->warn('no pocket card generated');
                        }
                        else{
                            $newData = json_encode($result);
                            $docdata = array('data' => $newData);
                            unset($NewData);
                            unset($newData);
                            $this->logger->info("Data is: ".print_r($docdata, true));
                            $this->documentBuilder->generateDocument($template, $docdata, $docDest, $options);
                            $documents['PocketCard'] = $dest['relativePath'].$template.'.pdf';
                        }
                    }
                }
            }
            else if($data['product'] == "Dive Store" || $data['product'] == 'Group Professional Liability'){
               if($this->type != 'endorsementQuote' && $this->type != "quote"){
                    $addLocations = $temp['additionalLocations'];   
                    unset($temp['additionalLocations']);
                    if(isset($this->template[$temp['product']]['cover_letter'])){
                        $this->logger->info("DOCUMENT cover_letter");
                        $documents['cover_letter'] = $this->generateDocuments($temp,$dest,$options,'cover_letter','lheader','lfooter');
                    }
                    if(isset($this->template[$data['product']]['instruct'])){
                        $this->logger->info("DOCUMENT instruct");
                        $documents['instruct'] = $this->copyDocuments($data,$dest['relativePath'],'instruct');
                    }
                    if(isset($this->template[$temp['product']]['businessIncomeWorksheet']))   {
                        $documents['businessIncomeWorksheet'] = $this->copyDocuments($temp,$dest['relativePath'],'businessIncomeWorksheet');
                    }

                    if(isset($temp['groupPL']) && $temp['groupProfessionalLiabilitySelect'] == 'yes'){
                        $this->generateGroupDocuments($data,$temp,$documents,$previous_data,$endorsementOptions,$dest,$options,$length);
                    }
                    if(isset($temp['additionalInsured']) && (isset($temp['additional_insured_select']) && ($temp['additional_insured_select']=="addAdditionalInsureds" || $temp['additional_insured_select']=="updateAdditionalInsureds"))){
                        $this->logger->info("DOCUMENT additionalInsured");
                        $documents['additionalInsured_document'] = $this->generateDocuments($temp,$dest,$options,'aiTemplate','aiheader','aifooter');
                    }
                    if(isset($this->template[$temp['product']]['blanketForm'])){
                        $this->logger->info("DOCUMENT blanketForm");
                        $documents['blanket_document'] = $this->copyDocuments($temp,$dest['relativePath'],'blanketForm');
                    }

                    if(isset($temp['additionalNamedInsured']) && $temp['additional_named_insureds_option'] == 'yes'){
                        if($this->type != 'endorsementQuote' && $this->type != 'endorsement'){
                            $documents['ani_document'] = $this->generateDocuments($temp,$dest,$options,'aniTemplate','aniheader','anifooter');
                        }
                    }

                    if(isset($temp['lossPayees']) && $temp['lossPayeesSelect']=="yes"){
                        $this->logger->info("DOCUMENT lossPayees");
                        $documents['loss_payee_document'] = $this->generateDocuments($temp,$dest,$options,'lpTemplate','lpheader','lpfooter');
                    }

                    if(isset($temp['additionalLocations']) && $temp['additionalLocationsSelect']=="yes"){
                        if(is_string($addLocations)){
                            $additionalLocations = json_decode($addLocations,true);
                        } else {
                            $additionalLocations = $addLocations;
                        }
                        for($i=0; $i<sizeof($additionalLocations);$i++){
                            $this->logger->info("DOCUMENT additionalLocations (additional named insuredes");
                            $temp["additionalLocationData"] = json_encode($additionalLocations[$i]);
                            $documents['additionalLocations_document_'.$i] = $this->generateDocuments($temp,$dest,$options,'alTemplate','alheader','alfooter',$i,0,true);
                            unset($temp["additionalLocationData"]);
                        }
                    }

                    if($this->type == 'policy'){
                        $this->generateDiveStorePremiumSummary($temp,$documents,$dest,$options);
                    }
                }
                if($data['groupProfessionalLiabilitySelect'] == 'yes'){
                    if(isset($temp['groupPL']) && !empty($temp['groupPL'])){
                        if (isset($this->template[$temp['product']]['card'])) {
                            $this->logger->info('inside dive boat pocket card');
                            $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
                            // $dest = ArtifactUtils::getDocumentFilePath($this->destination, $data['uuid'], array('orgUuid' => $orgUuid));
                            $template = $this->template[$temp['product']]['card'];
                            $options = array();
                            $docDest = $dest['absolutePath'].$template.'.pdf';
                            $result = $this->newDataArray($temp);
                            if(!isset($result) || empty($result)){
                                $this->logger->warn('no pocket card generated');
                            }
                            else{
                                $newData = json_encode($result);
                                $docdata = array('data' => $newData);
                                unset($NewData);
                                unset($newData);
                                $this->logger->info("Data is: ".print_r($docdata, true));
                                $this->documentBuilder->generateDocument($template, $docdata, $docDest, $options);
                                $documents['PocketCard'] = $dest['relativePath'].$template.'.pdf';
                            }
                        }
                    }
                }
            }

            if(($data['product'] == 'Dive Store' || $data['product'] == 'Group Professional Liability') && $this->type == 'quote'){
                $addLocations = $temp['additionalLocations'];
                unset($temp['additionalLocations']);
                $this->diveStoreQuoteDocuments($data,$documents,$temp,$dest,$options,$previous_data,$endorsementOptions,$length);
                if(isset($addLocations) && $temp['additionalLocationsSelect']=="yes"){
                    if(is_string($addLocations)){
                        $additionalLocations = json_decode($addLocations,true);
                    } else {
                        $additionalLocations = $addLocations;
                    }
                    for($i=0; $i<sizeof($additionalLocations);$i++){
                        $this->logger->info("DOCUMENT additionalLocations (additional named insuredes");
                        $temp["additionalLocationData"] = json_encode($additionalLocations[$i]);
                        $documents['additionalLocations_document_'.$i] = $this->generateDocuments($temp,$dest,$options,'alTemplate','alheader','alfooter',$i,0,true);
                        unset($temp["additionalLocationData"]);
                    }
                    $data['quoteDocuments'] = $documents;
                }
                if(is_string($data['csrApprovalAttachments'])){
                    $data['csrApprovalAttachments'] = json_decode($data['csrApprovalAttachments'],true);
                }
            }else if($this->type == 'policy' && $data['product'] == 'Dive Store'){
                $documents['liability_coi_document'] = $this->generateDocuments($temp,$dest,$options,'template','header','footer','liability');
                if($temp['propertyCoverageSelect'] == 'yes'){
                    $this->logger->info("DOCUMENT property_coi_document");
                    $documents['property_coi_document']  = $this->generateDocuments($temp,$dest,$options,'template','propertyHeader','propertyFooter','property');
                    // $this->logger->info("DOCUMENT property_policy_document");
                    // $documents['property_policy_document'] = $this->copyDocuments($temp,$dest['relativePath'],'policy','property');
                }
            }else if($data['product'] == 'Dive Store' && $this->type == 'endorsementQuote'){
                $this->diveStoreEndorsement($data,$temp);
                $this->diveStoreEnorsementQuoteDocuments($data,$documents,$temp,$dest,$options,$previous_data,$endorsementOptions,$length);
            }else if($data['product'] == 'Dive Store' && $this->type == 'endorsement'){
                $this->diveStoreEndorsement($data,$temp);
                $documents['endorsement_coi_document'] = isset($documents['endorsement_coi_document']) ? $documents['endorsement_coi_document'] : array();
                $endorsementDoc = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
                array_push($documents['endorsement_coi_document'], $endorsementDoc);
            }else if($data['product'] == 'Dive Boat' && ($this->type == 'endorsement' || $this->type == 'endorsementQuote')){
                if($this->type == 'endorsement'){
                    if((isset($endorsementOptions['modify_businessAndPolicyInformation']) && $endorsementOptions['modify_businessAndPolicyInformation'] == true) || (isset($endorsementOptions['modify_boatUsageCaptainCrewSchedule']) && $endorsementOptions['modify_boatUsageCaptainCrewSchedule'] == true) || (isset($endorsementOptions['modify_boatDeatails']) && $endorsementOptions['modify_boatDeatails'] == true) || (isset($endorsementOptions['modify_additionalInsured']) && $endorsementOptions['modify_additionalInsured']  == true)|| (isset($endorsementOptions['modify_lossPayees']) && $endorsementOptions['modify_lossPayees'] == true) || (isset($data['generatePersonalInfo']) || ($data['generatePersonalInfo'] == true || $data['generatePersonalInfo'] == 'true'))){
                        $documents['endorsement_coi_document'] = isset($documents['endorsement_coi_document']) ? $documents['endorsement_coi_document'] : array();
                        $endorsementDoc = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
                        array_push($documents['endorsement_coi_document'], $endorsementDoc);
                    }
                }else if($this->type == 'endorsementQuote'){
                    if((isset($endorsementOptions['modify_businessAndPolicyInformation']) && $endorsementOptions['modify_businessAndPolicyInformation'] == true) || (isset($endorsementOptions['modify_boatUsageCaptainCrewSchedule']) && $endorsementOptions['modify_boatUsageCaptainCrewSchedule'] == true) || (isset($endorsementOptions['modify_boatDeatails']) && $endorsementOptions['modify_boatDeatails'] == true) || (isset($endorsementOptions['modify_additionalInsured']) && $endorsementOptions['modify_additionalInsured']  == true)|| (isset($endorsementOptions['modify_lossPayees']) && $endorsementOptions['modify_lossPayees'] == true) || (isset($data['generatePersonalInfo']) || (isset($data['generatePersonalInfo']) && ($data['generatePersonalInfo'] == true || $data['generatePersonalInfo'] == 'true')))){
                        $documents['endorsement_quote_coi_document'] = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
                    }
				}
            }else{
                if ($temp['product'] == 'Individual Professional Liability') {
                 $check = $this->endorsementOptionsFlag($temp);
             }

             if (!isset($check) || $check['pACCheck'] == 1 || $check['endorsement'] == 0 ) {
                $policyDocuments = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
                if(is_array($policyDocuments)){
                    foreach ($policyDocuments as $key => $value) {
                        $documents[$key] = $value;
                    }
                }else if($temp['product'] == 'Individual Professional Liability' || $temp['product'] == 'Emergency First Response'){
                    $documents['coi_document']  = array($policyDocuments);
                }else if($temp['product'] == 'Dive Store'){
                    $documents['liability_coi_document']  = $policyDocuments;
                }else{
                    $documents['coi_document']  = $policyDocuments;
                }
            }
        }
                // if($this->type != 'quote' && $this->type != 'endorsementQuote')
                // {
                //     $policyDocuments = $this->copyDocuments($temp,$dest['relativePath'],'policy');
                //     if(is_array($policyDocuments)){
                //         foreach ($policyDocuments as $key => $value) {
                //             $documents[$key] = $value;
                //         }
                //     } else {
                //         $documents['policy_document'] = $policyDocuments;
                //     }
                // }
            if($this->type == 'lapse'){
                $this->logger->info("DOCUMENT lapse");
                return $this->generateDocuments($data,$dest,$options,'ltemplate','lheader','lfooter');
            }
            if(isset($this->template[$temp['product']]['slWording'])){
                if($temp['state'] == 'California'){
                        $documents['slWording'] = $this->copyDocuments($temp,$dest['relativePath'],'slWording');
                }
            }

            $this->logger->info("temp".print_r($data,true));
            $this->logger->info("Documents :".print_r($documents,true));
            if($temp['product'] == 'Individual Professional Liability' || $temp['product'] == 'Emergency First Response'){
                $docs = array();

                if (!isset($data['regeneratePolicy']) || (isset($data['regeneratePolicy']) && empty($data['regeneratePolicy']))) {

                    if(isset($data['documents'])){
                        if(is_string($data['documents'])) {
                            $docs = json_decode($data['documents'],true);
                        } else {
                            $docs = $data['documents'];
                        }
                    } else {
                        $data['documents'] = array();
                        $docs = $data['documents'];
                    }
                }
                else{
                    if(isset($data['regeneratePolicy']) && !empty($data['regeneratePolicy']) && isset($data['endorsement_options']) && empty($data['endorsement_options'])) {
                    if(is_string($data['documents'])) {
                            $docs = json_decode($data['documents'],true);
                    }else{
                        $docs = $data['documents'];
                    }
                }else{
                    $docs = $data['documents'];
                }
                }
                $checkFlag = $this->endorsementOptionsFlag($temp);
                 if(!isset($documents['coi_document'])){
                    $documents['coi_document'] = array();
                }
                if(isset($docs['coi_document'])){
                    if($checkFlag['pACCheck'] == 1 && isset($documents['coi_document'][0])){
                        $destinationForWatermark = $dest['absolutePath'].'../../../'.$docs['coi_document'][0];
                        $this->addWaterMark($destinationForWatermark,"INVALID");
                    }
                    foreach ($docs['coi_document'] as $key => $value) {
                        if (!in_array($docs['coi_document'][$key], $documents['coi_document'],true)) {
                            array_push($documents['coi_document'],$docs['coi_document'][$key]);
                        }
                    }
                }
                if(!isset($documents['additionalInsured_document']) && isset($docs['additionalInsured_document'])){
                    $documents['additionalInsured_document'] = array();
                }
                if($checkFlag['aICheck'] == 1 && isset($docs['additionalInsured_document'][0])){
                    $destinationForWatermark = $dest['absolutePath'].'../../../'.$docs['additionalInsured_document'][0];
                    $this->addWaterMark($destinationForWatermark,"INVALID");
                    foreach ($docs['additionalInsured_document'] as $key => $value) {
                            array_push($documents['additionalInsured_document'],$docs['additionalInsured_document'][$key]);
                    }
                }
                $data['documents'] = $documents;
            }else if($this->type == 'endorsement' || $this->type == 'endorsementQuote'){
                $data['documents'] = is_string($data['documents']) ? json_decode($data['documents'],true) : $data['documents'];
                if($this->type == 'endorsement'){
                    if(isset($data['documents']['roster_certificate'])){
                        unset($data['documents']['roster_certificate']);
                    }
                    if(isset($data['documents']['roster_pdf'])){
                        unset($data['documents']['roster_pdf']);
                    }
                    if(isset($data['documents']['endorsement_quote_coi_document'])){
                        unset($data['documents']['endorsement_quote_coi_document']);
                    }
                }
                $data['documents'] = array_merge($data['documents'],$documents);

            }else{
                $data['documents'] = $documents;
            }

            if(isset($data['endorsement_options'])){
                if(isset($data['endorsementCoverage'])){
                    $data['endorsementCoverage'] = array();
                }
                if(isset($data['endorsementCylinder'])){
                    $data['endorsementCylinder'] = array();
                }
                if(isset($data['endorsementExcessLiability'])){
                    $data['endorsementExcessLiability'] = array();
                }
                if(isset($data['endorsementTecRec'])){
                    $data['endorsementTecRec'] = array();
                }
                if(isset($data['endorsementScubaFit'])){
                    $data['endorsementScubaFit'] = array();
                }
                if(isset($data['endorsementEquipment'])){
                    $data['endorsementEquipment'] = array();
                }

                if($this->type != 'endorsementQuote'){
                    if(is_string($data['endorsement_options'])){
                    $data['endorsement_options'] = json_decode($data['endorsement_options'],true);
                    }
                    if(is_array($data['endorsement_options'])){
                        foreach ($data['endorsement_options'] as $key => $val){
                            $data['endorsement_options'][$key] = false;
                        }
                    }
                }
            }
            $this->processAttachments($data);
            $data['CSRReviewRequired'] = "";
            if($this->type == 'quote' || $this->type == 'endorsementQuote'){
                $data['policyStatus'] = "Quote Approval Pending";
            } else if($this->type == 'lapse'){
                $data['policyStatus'] = "Lapsed";
            } else {
                $data['policyStatus'] = "In Force";
                if(isset($data['endorsement_options'])){
                    $data['endorsement_options'] = "";
                }
            }
            if(isset($data['initiatedByUser'])){
                $data['initiatedByUser'] = "";
            }
            if(isset($data['initiatedByCsr'])){
                $data['initiatedByCsr'] = "";
            }
            $data['start_date'] = $startDate;
            $data['end_date'] = $endDate;
            if(isset($data['update_date'])){
                $data['update_date'] = $updateDate;
            }
            if(isset($data['disableOptions'])){
                $data['disableOptions'] = "";
            }
            if(isset($data['documents1'])){
                $data['documents1'] = "";
            }
            if(isset($data['userApproved'])){
                $data['userApproved'] = "";
            }
            if(!$this->type == 'quote' || !$this->type == 'endorsementQuote'){
                $data['rejectionReason'] = "";
                if(isset($data['rejectionReason'])){
                    $data['rejectionReason'] = array();
                }
            }
            if(isset($data['additionalNotes']) && $data['additionalNotes'] != ""){
                $comments = array();
                $comments['text'] = $data['additionalNotes'];
                $this->createComment($comments,$data['fileId']);
            }
            if(isset($data['regeneratePolicy'])){
                $data['regeneratePolicy'] = "";
            }


            $data['isRenewalFlow'] = false;
            $this->logger->info("Policy Document Generation",print_r($data,true));
            return $data;
        }

        protected function setPolicyInfo(&$data,$persistenceService,$endorsementOptions = null)
        {
                if($this->type != "quote" && $this->type != "lapse" && $this->type != 'endorsementQuote'){
                  if(isset($data['certificate_no'])){
                    $coi_number = $data['certificate_no'];
                  } else {
                      $coi_number = $this->generateCOINumber($data,$persistenceService);
                  }
                    if($this->type == 'endorsement'){
                        if($data['product'] == 'Dive Store'){
                            if(isset($data['documents']['endorsement_coi_document'])){
                                $length = sizeof($data['documents']['endorsement_coi_document']) + 1;
                            }else{
                                $length = 1;
                            }
                            $data['certificate_no'] = $data['certificate_no'].' - '.$length;
                        }else if((isset($endorsementOptions['modify_businessAndPolicyInformation']) && $endorsementOptions['modify_businessAndPolicyInformation'] == true) || (isset($endorsementOptions['modify_boatUsageCaptainCrewSchedule']) && $endorsementOptions['modify_boatUsageCaptainCrewSchedule'] == true) || (isset($endorsementOptions['modify_boatDeatails']) && $endorsementOptions['modify_boatDeatails'] == true) || (isset($endorsementOptions['modify_additionalInsured']) && $endorsementOptions['modify_additionalInsured']  == true)|| (isset($endorsementOptions['modify_lossPayees']) && $endorsementOptions['modify_lossPayees'] == true)){
                            if(isset($data['documents']['endorsement_coi_document'])){
                                $length = sizeof($data['documents']['endorsement_coi_document']) + 1;
                            }else{
                                $length = 1;
                            }
                            $data['certificate_no'] = $data['certificate_no'].' - '.$length;
                        }

                        if($endorsementOptions['modify_groupProfessionalLiability'] == true){

                            if(isset($data['groupPL'])){
                             $groupVal = false;
                             if($data['product'] == 'Dive Boat'){
                                if($data['groupProfessionalLiability'] == 'yes'){
                                    $groupVal = true;
                                }
                             }else if($data['product'] == 'Dive Store' || $data['product'] == 'Group Professional Liability'){
                                if($data['groupProfessionalLiabilitySelect'] == 'yes'){
                                    $groupVal = true;
                                }
                             }
                             if($groupVal == true){
                               if(isset($data['documents']['endorsement_group_coi_document'])){
                                    $length = sizeof($data['documents']['endorsement_group_coi_document']) + 1;
                                }else{
                                    $length = 1;
                                }
                                if(isset($data['group_certificate_no'])){
                                    $data['group_certificate_no'] = $data['group_certificate_no'].' - '.$length;
                                }else{
                                    $data['group_certificate_no'] = 'S'.$data['certificate_no'];
                                }
                             }
                            }
                        }
                    }else{
                        $data['certificate_no'] = $coi_number;
                        if(isset($data['groupPL'])){
                         $groupVal = false;
                         if($data['product'] == 'Dive Boat'){
                            if($data['groupProfessionalLiability'] == 'yes'){
                                $groupVal = true;
                            }
                         }else if($data['product'] == 'Dive Store'){
                            if($data['groupProfessionalLiabilitySelect'] == 'yes'){
                                $groupVal = true;
                            }
                         }
                         else if($data['product'] == 'Group Professional Liability'){
                            if($data['groupProfessionalLiabilitySelect'] == 'yes'){
                                $groupVal = true;
                            }
                         }
                         if($groupVal == true){
                            $data['group_certificate_no'] = 'S'.$coi_number;
                         }
                        }
                    }
                }
                
                $orgUuid = $this->processDate($data);
                if($this->type != "lapse"){
                    $license_number = $this->getLicenseNumber($data,$persistenceService);
                    $policyDetails = $this->getPolicyDetails($data,$persistenceService);
                    $data['license_number'] = $license_number;
                    if($policyDetails){
                        $data['policy_id'] = $policyDetails['policy_number'];
                        $data['carrier'] = $policyDetails['carrier'];
                    }
                }

                if($data['product'] == 'Dive Store'){
                    $liabilityPolicyDetails = $this->getPolicyDetails($data,$persistenceService,$data['product'],'LIABILITY');
                    if($liabilityPolicyDetails){
                        $data['liability_policy_id'] = $liabilityPolicyDetails['policy_number'];
                        $data['liability_carrier'] = $liabilityPolicyDetails['carrier'];
                    }

                    $propertyPolicyDetails = $this->getPolicyDetails($data,$persistenceService,$data['product'],'PROPERTY');
                    if($propertyPolicyDetails){
                        $data['property_policy_id'] = $propertyPolicyDetails['policy_number'];
                        $data['property_carrier'] = $propertyPolicyDetails['carrier'];
                    }
                }


                if(isset($data['groupPL'])){
                    $groupVal = false;
                    if($data['product'] == 'Dive Boat'){
                        if($data['groupProfessionalLiability'] == 'yes'){
                            $groupVal = true;
                        }
                    }else if($data['product'] == 'Dive Store'){
                        if($data['groupProfessionalLiabilitySelect'] == 'yes'){
                            $groupVal = true;
                        }
                    }
                    else if($data['product'] == 'Group Professional Liability'){
                        if($data['groupProfessionalLiabilitySelect'] == 'yes'){
                            $groupVal = true;
                        }
                    }
                    if($groupVal == true){
                        $product = 'Group Professional Liability';
                        $policyDetails = $this->getPolicyDetails($data,$persistenceService,$product);
                        if($policyDetails){
                            $data['group_policy_id'] = $policyDetails['policy_number'];
                            $data['group_carrier'] = $policyDetails['carrier'];
                        }
                    }
                }


                $dest = ArtifactUtils::getDocumentFilePath($this->destination,$data['fileId'],array('orgUuid' => $orgUuid));

                if(!is_null($endorsementOptions)){
                    $workflowInstUuid = $this->getWorkflowInstanceByFileId($data['fileId'],'In Progress');
                    if( count($workflowInstUuid) > 0 && (isset($workflowInstUuid[0]['process_instance_id']))){
                        $dest['absolutePath'] .= $workflowInstUuid[0]['process_instance_id']."/";
                        $dest['relativePath'] .= $workflowInstUuid[0]['process_instance_id']."/";
                        FileUtils::createDirectory($dest['absolutePath']);
                    }
                }
                $data['dest'] = $dest;
                return $data;
        }
        private function generateCOINumber($data,$persistenceService)
        {
                $sequence = 0;
                $year = date('Y', strtotime($data['end_date']));
                $persistenceService->beginTransaction();
                try{
                    if($data['product'] == 'Individual Professional Liability' || $data['product'] == 'Emergency First Response'){
                        $product = 'Individual Professional Liability';
                    }else{
                        $product = $data['product'];
                    }
                    $select1 = "Select * FROM certificate_of_insurance_number WHERE product ='".$product."' AND year = $year FOR UPDATE";
                    $this->logger->info("QUERY POLICY - ".print_r($select1,true));
                    $result1 = $persistenceService->selectQuery($select1);
                    while ($result1->next()) {
                        $details[] = $result1->current();
                    }
                    if($result1->count() == 0){
                        $sequence ++;
                        $select2 = "INSERT INTO certificate_of_insurance_number (`product`,`year`,`sequence`) VALUES ('".$product."', $year, $sequence)";
                        $result2 = $persistenceService->insertQuery($select2);
                    }else{
                        $sequence = $details[0]['sequence'];
                        $sequence ++;
                        $select3 = "UPDATE `certificate_of_insurance_number` SET `sequence` = $sequence WHERE product ='".$product."' AND year = $year";
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
            }else{
                $selectQuery = "Select * FROM state_license WHERE state = 'California'";
                $resultQuery = $persistenceService->selectQuery($selectQuery);
                while ($resultQuery->next()) {
                    $stateLicenseDetails[] = $resultQuery->current();
                }
                return $stateLicenseDetails[0]['license_number'];
            }
        }
        protected function getPolicyDetails($data,$persistenceService,$product = null,$category = null)
        {
            if(!isset($product)){
                $product = $data['product'];
            }
            $endDate = date_format(date_create($data['end_date']),"Y-m-d");
            $selectQuery = "Select carrier,policy_number FROM carrier_policy WHERE product ='".$product."' AND state = '".$data['state']."' AND `year` = YEAR('".$endDate."') - 1;";
            $this->logger->info("Carrier Policy Query : $selectQuery");
            $resultQuery = $persistenceService->selectQuery($selectQuery);
            while ($resultQuery->next()) {
                $policyDetails[] = $resultQuery->current();
            }
            if($resultQuery->count()!=0){
                return $policyDetails[0];
            }else{
                $andClause = " AND category IS NULL AND state is NULL ";
                if(isset($category) && $product == 'Dive Store'){
                    $andClause = " AND category = '".$category."' ";
                }
                $selectQuery = "Select carrier,policy_number FROM carrier_policy WHERE product ='".$product."' ".$andClause." AND `year` = YEAR('".$endDate."') - 1;";
                $this->logger->info("Carrier Policy Query : $selectQuery");
                $resultQuery = $persistenceService->selectQuery($selectQuery);
                while ($resultQuery->next()) {
                    $policyDetails[] = $resultQuery->current();
                }
                if($resultQuery->count()!=0){
                    return $policyDetails[0];
                }
            }
            return NULL;
        }
        protected function generateDocuments(&$data,$dest,$options,$templateKey,$headerKey = null,$footerKey = null,$indexKey = null,$length = 0,$multiple=false){
            $this->logger->info("Generate documents parameters templatekey is : ".print_r($templateKey, true));
            $this->logger->info("policy document destination is : ".print_r($dest, true));
            $this->logger->info("policy document options is : ".print_r($options, true));
            $this->logger->info("policy document data is : ".print_r($data, true));
            $this->logger->info("Product : ".print_r($this->template[$data['product']], true));
            $this->logger->info("TEMPLATE KEY ARRAY : ".print_r($this->template[$data['product']][$templateKey],true));
            $this->logger->info("index key : ".print_r($indexKey,true));
            if(isset($indexKey) && !$multiple){
                $this->logger->info("template with indexKey");
                $template =  $this->template[$data['product']][$templateKey][$indexKey];
            }else{
                $this->logger->info("template without indexKey");
                $template =  $this->template[$data['product']][$templateKey];
            }
            $this->logger->info("template slected: ".print_r($template, true));
            if(is_array($template)){
                $docDest = array();
                foreach ($template as $key => $value) {
                    $docDest[$value] = $dest['absolutePath'].$value.'.pdf';
                }
            } else if($multiple == true){
                $docDest = $dest['absolutePath'].$template.$indexKey.'.pdf';
            } else {
                $docDest = $dest['absolutePath'].$template.'.pdf';
            }
            if($template == 'Group_PL_COI' || $template == 'Group_PL_COI_DS'){
                $options['generateOptions'] = array('disable_smart_shrinking' => 1);
            }
            if(isset($headerKey) && $headerKey !=null){
                $options['header'] =  $this->template[$data['product']][$headerKey];
            }
            if(isset($headerKey) && $footerKey !=null){
                $options['footer'] =  $this->template[$data['product']][$footerKey];
            }
            if(!is_array($docDest)){
				if(file_exists($docDest)){
					$docName = basename($docDest);
					FileUtils::deleteFile($docName,$dest['absolutePath']);
				}
				$generatedDocument = $this->documentBuilder->generateDocument($template,$data,$docDest,$options);
			} else {
				if(is_array($docDest)){
					$generatedDocuments = array();
					foreach($docDest as $key => $doc){
						if(file_exists($docDest)){
							$docName = basename($doc);
							FileUtils::deleteFile($docName,$dest['absolutePath']);
						}
						$generatedDocuments[] = $this->documentBuilder->generateDocument($key,$data,$doc,$options);
					}
				}
			}
            if($this->type == 'lapse'){
                $data['documents']['lapse_document'] = $dest['relativePath'].$template.'.pdf';
                return $data;
            }
            if(is_array($docDest)){
                $filesCreated = array();
                foreach($docDest as $key => $doc){
                    $filesCreated[$key] = $dest['relativePath'].$key.'.pdf';
                }
                return $filesCreated;
            } else {
                if($multiple){
                    return $dest['relativePath'].$template.$indexKey.'.pdf';
                }
                return $dest['relativePath'].$template.'.pdf';
            }
        }
        protected function copyDocuments(&$data,$dest,$fileKey,$indexKey =null){
            if(isset($indexKey)){
                $file =  $this->template[$data['product']][$fileKey][$indexKey];
            }else{
                $file =  $this->template[$data['product']][$fileKey];
            }
            if(!file_exists($dest)){
                if(is_array($file)){
                    $returnFiles = array();
                    foreach ($file as $k => $v) {
                        $this->documentBuilder->copyTemplateToDestination($v,$dest);
                        $returnFiles[$v] = $dest.$v;
                    }
                    return $returnFiles;
                } else {
                    $this->documentBuilder->copyTemplateToDestination($file,$dest);
                    return $dest.$file;
                }
            }
        }
        private function processAttachments(&$data){
            if(isset($data['csr_attachments']) && (!empty($data['csr_attachments']))){
                if(is_string($data['csr_attachments'])){
                    $data['csr_attachments'] = json_decode($data['csr_attachments'], true);
                }
                if(!isset($data['attachments'])){
                    $data['attachments'] = array();
                }else if(is_string($data['attachments'])){
                    $data['attachments'] = json_decode($data['attachments'], true);
                }
                foreach ($data['csr_attachments'] as $key => $value) {
                    $data['attachments'][] = $value;
                }
                $data['csr_attachments'] = "";
            }
        }

    private function addWaterMark($source,$text){
        $this->logger->info("Watermark source :",$source);
        $pdfwater = new PDF_Watermarker();
        $pdfwater->watermarkPDF($source,$text);
    }


    protected function getStateInShort($state,$persistenceService){
        $selectQuery = "Select state_in_short FROM state_license WHERE state ='".$state."'";
        $resultSet = $persistenceService->selectQuery($selectQuery);
        if($resultSet->count() == 0){
            return $state;
        }else{
            while ($resultSet->next()) {
                $stateDetails[] = $resultSet->current();
            }
            if(isset($stateDetails) && count($stateDetails)>0){
                 $state = $stateDetails[0]['state_in_short'];
            }
        }
        return $state;
    }

    private function newDataArray($data){
        $this->logger->info('pocket card - padi data to be formatted: '.print_r($data, true));
        $i = 0;
        if(isset($data['groupPL']) && !empty($data['groupPL']) && $data['groupPL'] != "[]"){
            $this->logger->info('group PL members need to be formatted to a new array');
            $groupData = json_decode($data['groupPL'], true);
            $this->logger->info('group data is: '.print_r($groupData, true));
            $total = count($groupData);
            foreach ($groupData as $key2 => $value2) {
                $response[$i]['padi'] = $value2['padi'];
                $response[$i]['firstname'] = $value2['firstname'];
                $response[$i]['lastname'] = $value2['lastname'];
                $response[$i]['start_date'] = $value2['start_date'];
                $response[$i]['product'] = $data['product'];
                $response[$i]['product_email_id'] = $data['product_email_id'];
                $response[$i]['email'] = $data['email'];
                $response[$i]['certificate_no'] = $data['certificate_no'];
                $response[$i]['end_date'] = $data['end_date'];
                $response[$i]['address1'] = $data['address1'];
                $response[$i]['address2'] = isset($data['address2']) ? $data['address2'] : '';
                $response[$i]['city'] = $data['city'];
                $response[$i]['state'] = $data['state'];
                $response[$i]['zip'] = $data['zip'];
                $response[$i]['country'] = $data['country'];
                $response[$i]['business_name'] = $data['business_name'];
                $i += 1;
            }
            $this->logger->info('the response data is : '.print_r($response, true));
            return $response;
        }
        else{
            $response = '';
            return $response;
        }
    }



    protected function generateGroupDocuments(&$data,&$temp,&$documents,$previous_data,$endorsementOptions,$dest,$options,$length){
        $groupData = json_decode($temp['groupPL'],true);
        asort($groupData);
        $temp['groupPL'] = json_encode($groupData);
        if($this->type == 'quote' || $this->type == 'endorsementQuote'){
            $documents['roster_certificate'] = $this->generateDocuments($temp,$dest,$options,'roster','rosterHeader','rosterFooter');
            $documents['roster_pdf'] = $this->copyDocuments($temp,$dest['relativePath'],'rosterPdf');
            if(isset($temp['groupAdditionalInsured']) && $temp['additional_insured'] == 'yes'){
                $documents['group_ai_certificate'] = $this->generateDocuments($temp,$dest,$options,'gaitemplate','gaiheader','gaifooter');
            }
        }
        else{
            $this->logger->info("DOCUMENT groupPL");

            if($this->type == 'endorsement'){
                if($endorsementOptions['modify_groupProfessionalLiability'] == true){
                    $policy = array();
                    $policy =  $previous_data[$length - 1];
                    $upgrade = array();


                    if(isset($data['upgradeGroupLiability'])){
                        $data['upgradeGroupLiability'] = is_array($data['upgradeGroupLiability']) ? $data['upgradeGroupLiability'] : json_decode($data['upgradeGroupLiability'],true);
                    }else{
                        $data['upgradeGroupLiability'] = array();
                    }
                    if($policy['previous_combinedSingleLimit'] != $data['combinedSingleLimit']){
                        $upgrade = array("update_date" => date_format(date_create($data['update_date']),"m/d/Y"),"combinedSingleLimit" => $data['combinedSingleLimit'],"annualAggregate" => $data['annualAggregate']);
                        array_push($data['upgradeGroupLiability'], $upgrade);
                    }
                    $temp['upgradeGroupLiability'] = json_encode($data['upgradeGroupLiability']);
                    $documents['roster_certificate'] = $this->generateDocuments($temp,$dest,$options,'roster','rosterHeader','rosterFooter');
                    $documents['roster_pdf'] = $this->copyDocuments($temp,$dest['relativePath'],'rosterPdf');
                    $documents['endorsement_group_coi_document'] = isset($documents['endorsement_group_coi_document']) ? $documents['endorsement_group_coi_document'] : array();
                    $endorsementDoc = $this->generateDocuments($temp,$dest,$options,'gtemplate','gheader','gfooter');
                    array_push($documents['endorsement_group_coi_document'], $endorsementDoc);

                    $documents['endorsement_group_ni_document'] = isset($documents['endorsement_group_ni_document']) ? $documents['endorsement_group_ni_document'] : array();
                    $endorsementNIDoc = $this->generateDocuments($temp,$dest,$options,'nTemplate','nheader','nfooter');
                    array_push($documents['endorsement_group_ni_document'], $endorsementNIDoc);

                    if(isset($temp['groupAdditionalNamedInsured']) && $temp['named_insureds'] == 'yes'){
                        $documents['endorsement_group_ani_document'] = isset($documents['endorsement_group_ani_document']) ? $documents['endorsement_group_ani_document'] : array();
                        $this->logger->info("DOCUMENT namedInsured");
                        $endorsementDoc = $this->generateDocuments($temp,$dest,$options,'ganiTemplate','ganiheader','ganifooter');
                        array_push($documents['endorsement_group_ani_document'], $endorsementDoc);
                    }

                    if(isset($temp['groupAdditionalInsured']) && $temp['additional_insured'] == 'yes'){
                        $documents['endorsement_group_ai_document'] = isset($documents['endorsement_group_ai_document']) ? $documents['endorsement_group_ai_document'] : array();
                        $this->logger->info("DOCUMENT namedInsured");
                        $endorsementDoc = $this->generateDocuments($temp,$dest,$options,'gaitemplate','gaiheader','gaifooter');
                        array_push($documents['endorsement_group_ai_document'], $endorsementDoc);
                    }

                    $documents['group_exclusions'] = $this->copyDocuments($temp,$dest['relativePath'],'groupExclusions');
                }
            }else{
                if ($data['groupExcessLiabilitySelect'] == "no"){
                    $temp['groupCombinedSingleLimit'] = "$1,000,000";
                    $temp['groupAnnualAggregate'] = "$2,000,000";
                } else if ($data['groupExcessLiabilitySelect'] == "groupExcessLiability1M"){
                    $temp['groupCombinedSingleLimit'] = "$2,000,000";
                    $temp['groupAnnualAggregate'] = "$3,000,000";
                } else if ($data['groupExcessLiabilitySelect'] == "groupExcessLiability2M"){
                    $temp['groupCombinedSingleLimit'] = "$3,000,000";
                    $temp['groupAnnualAggregate'] = "$4,000,000";
                } else if ($data['groupExcessLiabilitySelect'] == "groupExcessLiability3M"){
                    $temp['groupCombinedSingleLimit'] = "$4,000,000";
                    $temp['groupAnnualAggregate'] = "$5,000,000";
                } else if ($data['groupExcessLiabilitySelect'] == "groupExcessLiability4M"){
                    $temp['groupCombinedSingleLimit'] = "$5,000,000";
                    $temp['groupAnnualAggregate'] = "$6,000,000";
                } else if ($data['groupExcessLiabilitySelect'] == "groupExcessLiability9M"){
                    $temp['groupCombinedSingleLimit'] = "$10,000,000";
                    $temp['groupAnnualAggregate'] = "$11,000,000";
                } else {
                    $temp['groupCombinedSingleLimit'] = "$1,000,000";
                    $temp['groupAnnualAggregate'] = "$2,000,000";
                }
                $documents['group_coi_document'] = $this->generateDocuments($temp,$dest,$options,'gtemplate','gheader','gfooter');
                $documents['roster_certificate'] = $this->generateDocuments($temp,$dest,$options,'roster','rosterHeader','rosterFooter');
                $documents['roster_pdf'] = $this->copyDocuments($temp,$dest['relativePath'],'rosterPdf');
                $documents['group_named_insured_document'] = $this->generateDocuments($temp,$dest,$options,'nTemplate','nheader','nfooter');
                if(isset($temp['groupAdditionalNamedInsured']) && $temp['named_insureds'] == 'yes'){
                    $this->logger->info("DOCUMENT Group Additional Named Insured");
                    $documents['group_additional_named_insured_document'] = $this->generateDocuments($temp,$dest,$options,'ganiTemplate','ganiheader','ganifooter');
                }

                if(isset($temp['groupAdditionalInsured']) && $temp['additional_insured'] == 'yes'){
                    $this->logger->info("DOCUMENT Group Additional Insured");
                    $documents['group_additional_insured_document'] = $this->generateDocuments($temp,$dest,$options,'gaitemplate','gaiheader','gaifooter');
                }

                $documents['group_exclusions'] = $this->copyDocuments($temp,$dest['relativePath'],'groupExclusions');
            }
        }
    }

     private function processUpgradeCoverages(&$data,$policy,$coverages,$prevCoverage,$coverageNameLabel,$coverageName,$upgrade){
        if($policy[$prevCoverage] != $data[$coverageName]){
            $upgrade = array($coverageNameLabel => $coverages[$data[$coverageName]]);
            $data['previous_policy_data'][0] = array_merge($data['previous_policy_data'][0],$upgrade);
        }
     }

     private function endorsementOptionsFlag($data){
        $endorsement = 0;
        $endorsementOptionsAICheck = 0;
        $endorsementOptionsPACCheck = 0;
        if(isset($data['endorsement_options']) && $data['endorsement_options'] != ""){
            $endorsement = 1;
            if(is_array($data['endorsement_options'])){
                if($data['endorsement_options']['modify_additionalInsured'] == true)
                    $endorsementOptionsAICheck = 1;
                if($data['endorsement_options']['modify_personalInformation'] == true || $data['endorsement_options']['modify_coverage'] == true)
                    $endorsementOptionsPACCheck = 1;
                $this->logger->info("array endorsement_options check value =".print_r($data['endorsement_options'],true));
            }
            if(is_string($data['endorsement_options']))
            {
                $endorsementOptions = json_decode($data['endorsement_options'],true);
                if($endorsementOptions['modify_additionalInsured'] == true){
                    $endorsementOptionsAICheck = 1;
                }
                if($endorsementOptions['modify_personalInformation'] == true || $endorsementOptions['modify_coverage'] == true){
                    $endorsementOptionsPACCheck = 1;
                }
                $this->logger->info("string endorsement_options check value =".$data['endorsement_options']);
            }
            }
            return array('endorsement' => $endorsement,'aICheck' => $endorsementOptionsAICheck, 'pACCheck' => $endorsementOptionsPACCheck);

         }

        protected function diveStoreEnorsementQuoteDocuments(&$data,&$documents,&$temp,$dest,$options,$previous_data,$endorsementOptions,$length){
            $data['quoteDocuments'] = array();
            $documents = array();
            $documents['cover_letter'] = $this->generateDocuments($temp,$dest,$options,'cover_letter','lheader','lfooter');
            $documents['endorsement_quote_coi_document'] = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
            if(isset($temp['groupPL']) && $temp['groupProfessionalLiabilitySelect'] == 'yes'){
                $this->generateGroupDocuments($data,$temp,$documents,$previous_data,$endorsementOptions,$dest,$options,$length);
            }
            $data['quoteDocuments'] = $documents;
         }


         protected function diveStoreQuoteDocuments(&$data,&$documents,&$temp,$dest,$options,$previous_data,$endorsementOptions,$length){
            $data['quoteDocuments'] = array();
            $documents = array();
            if($data['product'] == 'Dive Store') {
                $documents['cover_letter'] = $this->generateDocuments($temp,$dest,$options,'cover_letter','lheader','lfooter');
                $documents['coi_document'] = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
            }
            if(isset($temp['additionalInsured']) && (isset($temp['additional_insured_select']) && ($temp['additional_insured_select']=="addAdditionalInsureds" || $temp['additional_insured_select']=="updateAdditionalInsureds"))){
                $this->logger->info("DOCUMENT additionalInsured");
                $documents['additionalInsured_document'] = $this->generateDocuments($temp,$dest,$options,'aiTemplate','aiheader','aifooter');
            }

            if(isset($temp['propertyCoverageSelect']) && $temp['propertyCoverageSelect'] == 'yes'){
                if(isset($this->template[$temp['product']]['businessIncomeWorksheet']))   {
                    $documents['businessIncomeWorksheet'] = $this->copyDocuments($temp,$dest['relativePath'],'businessIncomeWorksheet');
                }
            }


            if(isset($temp['lossPayees']) && $temp['lossPayeesSelect']=="yes"){
                $this->logger->info("DOCUMENT lossPayees");
                $documents['loss_payee_document'] = $this->generateDocuments($temp,$dest,$options,'lpTemplate','lpheader','lpfooter');
            }

            $documents['premium_summary_document'] = $this->generateDocuments($temp,$dest,$options,'psTemplate','header','footer');

            if(isset($temp['additionalNamedInsured']) && $temp['additional_named_insureds_option'] == 'yes'){
                if($this->type != 'endorsementQuote' && $this->type != 'endorsement'){
                    $documents['ani_document'] = $this->generateDocuments($temp,$dest,$options,'aniTemplate','aniheader','anifooter');
                }
            }

            if(isset($temp['additionalLocations']) && $temp['additionalLocationsSelect']=="yes"){
                $addLocations = $temp['additionalLocations'];
                unset($temp['additionalLocations']);
                if(is_string($addLocations)){
                    $additionalLocations = json_decode($addLocations,true);
                } else {
                    $additionalLocations = $addLocations;
                }
                for($i=0; $i<sizeof($additionalLocations);$i++){
                    $this->logger->info("DOCUMENT additionalLocations (additional named insuredes");
                    $temp["additionalLocationData"] = json_encode($additionalLocations[$i]);
                    $documents['additionalLocations_document_'.$i] = $this->generateDocuments($temp,$dest,$options,'alTemplate','alheader','alfooter',$i,0,true);
                    unset($temp["additionalLocationData"]);
                }
            }

            if(isset($temp['groupPL']) && $temp['groupProfessionalLiabilitySelect'] == 'yes'){
                $this->generateGroupDocuments($data,$temp,$documents,$previous_data,$endorsementOptions,$dest,$options,$length);
                if(isset($this->template[$temp['product']]['GLblanketForm'])){
                    $this->logger->info("DOCUMENT GLblanketForm");
                    $documents['blanket_document'] = $this->copyDocuments($temp,$dest['relativePath'],'GLblanketForm');
                }
            }

            $data['quoteDocuments'] = $documents;
         }
         protected function diveStoreEndorsement(&$data,&$temp){
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
                $temp['liabilityChanges'] = false;
                if(isset($data['excessLiabilityCoverage']) && isset($policy['previous_excessLiabilityCoverage'])){
                    if($policy['previous_excessLiabilityCoverage'] == $data['excessLiabilityCoverage']){
                        $data['increased_liability_limit'] = false;
                    } else {
                        $temp['liabilityChanges'] = true;
                        if($data['excessLiabilityCoverage']=='excessLiabilityCoverage1M'){
                            $temp['increased_liability_limit'] = "$1,000,000";
                        } else if($data['excessLiabilityCoverage']=='excessLiabilityCoverage2M'){
                            $temp['increased_liability_limit'] = "$2,000,000";
                        } else if ($data['excessLiabilityCoverage']=='excessLiabilityCoverage3M'){
                            $temp['increased_liability_limit'] = "$3,000,000";
                        } else if ($data['excessLiabilityCoverage']=='excessLiabilityCoverage4M'){
                            $temp['increased_liability_limit'] = "$4,000,000";
                        } else if ($data['excessLiabilityCoverage']=='excessLiabilityCoverage9M'){
                            $temp['increased_liability_limit'] = "$9,000,000";
                        }
                    }
                }
                if(isset($data['nonOwnedAutoLiabilityPL']) && isset($policy['previous_nonOwnedAutoLiabilityPL'])){
                    if($policy['previous_nonOwnedAutoLiabilityPL'] == $data['nonOwnedAutoLiabilityPL']){
                        $data['increased_non_owned_liability_limit'] = false;
                    } else {
                        $temp['liabilityChanges'] = true;
                        if($data['nonOwnedAutoLiabilityPL']=='nonOwnedAutoLiability1M'){
                            $temp['increased_non_owned_liability_limit'] = "$1,000,000";
                        } else if($data['nonOwnedAutoLiabilityPL']=='nonOwnedAutoLiability100K'){
                            $temp['increased_non_owned_liability_limit'] = "$100,000";
                        }
                    }
                }
                if(isset($data['travelAgentEoPL']) && isset($policy['previous_travelEnO'])){
                    if($policy['previous_travelEnO'] == $data['travelAgentEoPL']){
                        $data['increased_travelEnO'] = false;
                    } else {
                        $temp['liabilityChanges'] = true;
                        if($data['travelAgentEoPL']){
                            $temp['increased_travelEnO'] = "$1,000,000";
                        }
                    }
                }
         }

         private function regenerationIPL($data,$dest){
            if(is_string($data['documents'])) {
                $docs = json_decode($data['documents'],true);
            }else{
                $docs = $data['documents'];
            }
 
            if (isset($docs['coi_document']) && isset($docs['coi_document'][0])) {
                $fileName = substr($docs['coi_document'][0], strrpos($docs['coi_document'][0], '/') + 1);
                $destinationForCOIRegeneration = $dest['absolutePath'].$fileName;
                if (file_exists($destinationForCOIRegeneration)) {
                    // unlink($destinationForCOIRegeneration);
                    FileUtils::deleteFile($fileName,$dest['absolutePath']);
                }
            }
            if (isset($docs['PocketCard'])) {
                $fileName = substr($docs['PocketCard'], strrpos($docs['PocketCard'], '/') + 1);
                $destinationForPCRegeneration = $dest['absolutePath'].$fileName;
                if (file_exists($destinationForPCRegeneration)) {
                    // unlink($destinationForPCRegeneration);
                    FileUtils::deleteFile($fileName,$dest['absolutePath']);
                }
            }

            if (isset($docs['blanket_document'])) {
                $fileName = substr($docs['blanket_document'], strrpos($docs['blanket_document'], '/') + 1);
                $destinationForBlanketRegeneration = $dest['absolutePath'].$fileName;
                if (file_exists($destinationForBlanketRegeneration)) {
                    // unlink($destinationForBlanketRegeneration);
                    FileUtils::deleteFile($fileName,$dest['absolutePath']);
                }
            }

            if (isset($data['AdditionalInsuredOption']) && ($data['AdditionalInsuredOption'] == 'addAdditionalInsureds')) {
                if (isset($docs['additionalInsured_document']) && isset($docs['additionalInsured_document'][0])) {
                    $fileName = substr($docs['additionalInsured_document'][0], strrpos($docs['additionalInsured_document'][0], '/') + 1);
                    $destinationForAIRegeneration = $dest['absolutePath'].$fileName;
                    if (file_exists($destinationForAIRegeneration)) {
                        // unlink($destinationForAIRegeneration);
                        FileUtils::deleteFile($fileName,$dest['absolutePath']);
                    }
                }
            }
         }

         private function endorsedDocumentsLoc($data,$dest){            
            $workflowInstUuid = $this->getWorkflowInstanceByFileId($data['fileId'],'In Progress');
            $this->logger->info("workflowInstUuid----".print_r($workflowInstUuid,true));
            if( count($workflowInstUuid) > 0 && (isset($workflowInstUuid[0]['process_instance_id']))){
                $dest['absolutePath'] .= $workflowInstUuid[0]['process_instance_id']."/";
                $dest['relativePath'] .= $workflowInstUuid[0]['process_instance_id']."/";
                FileUtils::createDirectory($dest['absolutePath']);
            }
         return $dest;
         }

         protected function processDate(&$data){
            $date=date_create($data['start_date']);
            $data['start_date'] = date_format($date,"m/d/Y");
            $date=date_create($data['end_date']);
            $data['end_date'] = date_format($date,"m/d/Y");
            if(isset($data['update_date'])){
                $date=date_create($data['update_date']);
                $data['update_date'] = date_format($date,"m/d/Y");
            }
            if(isset($data['fileId'])){
                $data['uuid'] = $data['fileId'];
            }
            if(!isset($data['uuid'])){
                $data['uuid'] = UuidUtil::uuid();
            }
            $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] :AuthContext::get(AuthConstants::ORG_UUID));
            $data['orgUuid'] = $orgUuid;
            return $orgUuid;
         }

         protected function documentsLocation($endorsementOptions,&$data,$orgUuid){
            $dest = ArtifactUtils::getDocumentFilePath($this->destination,$data['fileId'],array('orgUuid' => $orgUuid));
            if(!is_null($endorsementOptions)){
                $dest = $this->endorsedDocumentsLoc($data,$dest);
            }
            $data['dest'] = $dest;
            return $dest;
         }

         protected function processSurplusYear(&$data){
            $month = date_format(date_create($data['end_date']),"m");
            $year = date_format(date_create($data['end_date']),"Y");


            if($data['product'] != 'Dive Boat'){
                if($month < 7){
                    $data['surplusLineYear'] = $year - 1;
                }else{
                    $data['surplusLineYear'] = $year;
                }
            }else{
                if($month < 8){
                    $data['surplusLineYear'] = $year - 1;
                }else{
                    $data['surplusLineYear'] = $year;
                }
            }
         }


         protected function generateDiveStoreLiabilityDocument(&$data,&$documents,$temp,$dest,$options,$persistenceService){
            $this->getDSLiabilityPolicyDetails($data,$temp,$persistenceService);
            $documents['liability_coi_document'] = $this->generateDocuments($temp,$dest,$options,'template','header','footer','liability');
        }

        private function getDSLiabilityPolicyDetails(&$data,&$temp,$persistenceService){
            $liabilityPolicyDetails = $this->getPolicyDetails($data,$persistenceService,$data['product'],'LIABILITY');
            if($liabilityPolicyDetails){
               $temp['liability_policy_id'] = $data['liability_policy_id'] = $liabilityPolicyDetails['policy_number'];
               $temp['liability_carrier'] = $data['liability_carrier'] = $liabilityPolicyDetails['carrier'];
            }
        }

        protected function processData(&$temp){
            foreach ($temp as $key => $value) {
                if(is_array($temp[$key])){
                    $temp[$key] = json_encode($value);
                }
            }
        }

        protected function generateDiveStorePremiumSummary($temp,$documents,$dest,$options){
            $documents['premium_summary_document'] = $this->generateDocuments($temp,$dest,$options,'psTemplate','psHeader','psFooter');
        }

}
