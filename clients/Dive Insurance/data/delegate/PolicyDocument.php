<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\ArtifactUtils;

class PolicyDocument extends AbstractDocumentAppDelegate
{
    private $documentBuilder;
    protected $type;
    protected $template;
    const TEMPLATE = array(
        'Individual Professional Liability' 
            => array('template' => 'ProfessionalLiabilityCOI',
                     'header' => 'COIheader.html',
                     'footer' => 'COIfooter.html',
                     'slWording' => 'SL Wording.pdf',
                     'policy' => 'Individual_Professional_Liability_Policy.pdf',
                     'card' => 'PocketCard',
                     'aiTemplate' => 'Individual_PL_AI',
                     'blanketForm' => 'Individual_AI_Blanket_Endorsement.pdf',
                     'aiheader' => 'IPL_AI_header.html',
                     'lheader' => 'letter_header.html',
                     'lfooter' => 'letter_footer.html',
                     'ltemplate' => 'Individual_PL_Lapse_Letter'),
        'Dive Boat' 
            => array('template' => 'DiveBoatCOI',
                     'header' => 'DiveBoatHeader.html',
                     'footer' => 'DiveBoatFooter.html',
                     'slWording' => 'SL Wording.pdf',
                     'policy' => 'Dive_Boat_Policy.pdf',
                     'cover_letter' => 'Dive_Boat_Cover_Letter',
                     'lheader' => 'letter_header.html',
                     'lfooter' => 'letter_footer.html',
                     'instruct' => 'Instructions_To_Insured.pdf',
                     'aiTemplate' => 'DiveBoat_AI',
                     'aiheader' => 'DiveBoat_AI_header.html',
                     'aifooter' => 'DiveBoat_AI_footer.html',
                     'aniheader' => 'DiveBoat_ANI_header.html',
                     'lpTemplate' => 'DiveBoat_LP',
                     'lpheader' => 'DiveBoat_LP_header.html',
                     'lpfooter' => 'DiveBoat_LP_footer.html',
                     'quoteTemplate' => 'DiveBoat_Quote',
                     'qheader' => 'DB_Quote_header.html',
                     'qfooter' => 'DB_Quote_footer.html',
                     'qaiHeader' => 'DB_Quote_AI_header.html',
                     'qaniHeader' => 'DB_Quote_ANI_header.html'),
        'Dive Store'
            => array('template' => array('liability' => 'DiveStore_Liability_COI','property' => 'DiveStore_Property_COI'),
                     'header' => 'DiveStoreHeader.html',
                     'footer' => 'DiveStoreFooter.html',
                     'slWording' => 'SL Wording.pdf',
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
                     'lpfooter' => 'DiveStore_LP_footer.html'));

    public function __construct(){
        parent::__construct();
        $this->type = 'policy';
        $this->template = array(
        'Individual Professional Liability' 
            => array('template' => 'ProfessionalLiabilityCOI',
                     'header' => 'COIheader.html',
                     'footer' => 'COIfooter.html',
                     'slWording' => 'SL Wording.pdf',
                     'policy' => 'Individual_Professional_Liability_Policy.pdf',
                     'card' => 'PocketCard',
                     'aiTemplate' => 'Individual_PL_AI',
                     'blanketForm' => 'Individual_AI_Blanket_Endorsement.pdf',
                     'aiheader' => 'IPL_AI_header.html',
                     'aifooter' => null,
                     'iplScuba' => 'PL Scuba Fit Endorsement.pdf',
                     'iplCylinder' => 'PL Cylinder Endorsement.pdf',
                     'iplEquipment' => 'PL Equipment Liability Endorsement.pdf'),
        'Dive Boat' 
            => array('template' => 'DiveBoatCOI',
                     'header' => 'DiveBoatHeader.html',
                     'footer' => 'DiveBoatFooter.html',
                     'slWording' => 'SL Wording.pdf',
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
                     'efooter' => 'DB_Endorsement_footer.html') ,
        'Dive Store'
            => array('template' => array('liability' => 'DiveStore_Liability_COI','property' => 'DiveStore_Property_COI'),
                     'header' => 'DiveStoreHeader.html',
                     'footer' => 'DiveStoreFooter.html',
                     'slWording' => 'SL Wording.pdf',
                     'policy' => array('liability' => 'Dive_Store_Liability_Policy.pdf','property' => 'Dive_Store_Property_Policy.pdf'),
                     'cover_letter' => 'Dive_Store_Cover_Letter',
                     'lheader' => 'letter_header.html',
                     'lfooter' => 'letter_footer.html',
                     'aiTemplate' => 'DiveStore_AI',
                     'aiheader' => 'DiveStore_AI_header.html',
                     'aifooter' => 'DiveStore_AI_footer.html'),
        'Emergency First Response'
            => array('template' => 'Emergency_First_Response_COI',
                     'header' => 'EFR_header.html',
                     'footer' => 'EFR_footer.html',
                     'slWording' => 'SL Wording.pdf',
                     'aiTemplate' => 'EFR_AI',
                     'aiheader' => 'EFR_AI_header.html',
                     'aifooter' => 'EFR_AI_footer.html'),
        'Group Professional Liability'
            => array('template' => 'Group_PL_COI',
                     'header' => 'Group_header.html',
                     'footer' => 'Group_footer.html',
                     'nTemplate' => 'Group_PL_NI',
                     'nheader' => 'Group_NI_header.html',
                     'nfooter' => 'Group_NI_footer.html'));
       
    }

    public function setDocumentBuilder($builder){
        
        $this->documentBuilder = $builder;
    }

    public function setTemplatePath($destination)
    {
        $this->destination = $destination;
    }
    public function execute(array $data,Persistence $persistenceService)
    {     
        $this->logger->info("Template Data Source - ".print_r($data, true));
        $date = ''; 
        $this->logger->info("Executing Policy Document");
        if($this->type != "quote" || $this->type != "lapse"){
            $coi_number = $this->generateCOINumber($data,$persistenceService);
            $data['certificate_no'] = $coi_number;
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
        $dest = ArtifactUtils::getDocumentFilePath($this->destination,$data['uuid'],array('orgUuid' => $orgUuid));
        $data['orgUuid'] = $orgUuid;

        if($this->type == 'lapse'){
            $lapseTemplate = self::TEMPLATE[$data['product']]['ltemplate'];
            $lapseDest = $dest['absolutePath'].$coi_number.'_Lapse_Letter'.'.pdf';
            $options['header'] = self::TEMPLATE[$data['product']]['lheader'];
            $options['footer'] = self::TEMPLATE[$data['product']]['lfooter'];
            $this->documentBuilder->generateDocument($lapseTemplate,$data,$lapseDest,$options);
            $data['lapse_document'] = $dest['relativePath'].$coi_number.'_LapseLetter'.'.pdf';
            return $data;
        }


        $license_number = $this->getLicenseNumber($data,$persistenceService);
        $policyDetails = $this->getPolicyDetails($data,$persistenceService);
        $data['license_number'] = $license_number;

        if($policyDetails){
            $data['policy_id'] = $policyDetails['policy_number'];
            $data['carrier'] = $policyDetails['carrier'];
        } 
        
    
        $options = array();
        if(isset($this->template[$data['product']]['header'])){
            $options['header'] = $this->template[$data['product']]['header'];
        }
        if(isset($this->template[$data['product']]['footer'])){
            $options['footer'] = $this->template[$data['product']]['footer'];
        }

        
        if(isset($this->template[$data['product']]['instruct'])){
            $instruct = $this->template[$data['product']]['instruct'];
            $this->documentBuilder->copyTemplateToDestination($instruct,$dest['relativePath']);
            $data['instruction_document'] = $dest['relativePath'].$instruct;
        }
        
        if(isset($data['liability'])){
            if(isset($this->template[$data['product']]['template']['liability'])){
                $template = $this->template[$data['product']]['template']['liability'];
            }
            if(isset($this->template[$data['product']]['policy'])){
                $policy = $this->template[$data['product']]['policy']['liability'];
                $data['policy_document'] = $dest['relativePath'].$policy;
            }
            $data['liability'] = json_encode($data['liability']);
        }


        if(isset($data['property'])){
            if(isset($this->template[$data['product']]['template']['property'])){
                $template = $this->template[$data['product']]['template']['property'];
            }
            if(isset($this->template[$data['product']]['policy'])){
                $policy = $this->template[$data['product']]['policy']['property'];
                $data['policy_document'] = $dest['relativePath'].$policy;
            }
            $data['property'] = json_encode($data['property']);
        }

        if(!isset($template)){
            $template = $this->template[$data['product']]['template'];
                if(isset($this->template[$data['product']]['policy'])){
                $policy = $this->template[$data['product']]['policy'];
                $data['policy_document'] = $dest['relativePath'].$policy;
            }
        }
      

        if(isset($data['careerCoverage']) || isset($data['scubaFit']) || isset($data['cylinder']) || isset($data['equipment'])){
            $coverageList = array();
            array_push($coverageList,$data['careerCoverage']);
            if(isset($data['scubaFit']) && $data['scubaFit'] == "scubaFitInstructor"){
                 $scubaFit =  $this->template[$data['product']]['iplScuba'];
                 $this->documentBuilder->copyTemplateToDestination($scubaFit,$dest['relativePath']);
                 $data['scuba_fit_document'] = $dest['relativePath'].$scubaFit;
                 array_push($coverageList,$data['scubaFit']);
            }
            // else{
            //     $data['scubaFitVal'] = null;
            // }
            if(isset($data['cylinder']) && ($data['cylinder'] == "cylinderInspector" || $data['cylinder'] == "cylinderInstructor" || $data['cylinder'] == "cylinderInspectorAndInstructor")){
                $cylinder = $this->template[$data['product']]['iplCylinder'];
                $this->documentBuilder->copyTemplateToDestination($cylinder,$dest['relativePath']);
                $data['cylinder_document'] = $dest['relativePath'].$cylinder;
                array_push($coverageList,$data['cylinder']);
            }

            // else{
            //     $data['cylinder'] = null;
            // }
            if(isset($data['equipment']) && $data['equipment'] == "equipmentLiabilityCoverage"){
                $equipment =  $this->template[$data['product']]['iplEquipment'];
                $this->documentBuilder->copyTemplateToDestination($equipment,$dest['relativePath']);
                $data['equipment_liability_document'] = $dest['relativePath'].$equipment;
                $data['equipmentVal'] = 'Included';
           }

           // else{
           //      $data['equipmentVal'] = 'Not Included';
           // }
           $result = $this->getCoverageName($coverageList,$data['product'],$persistenceService);
           $result = json_decode($result,true);
         
           if(isset($result[$data['scubaFit']])){
                $data['scubaFitVal'] = $result[$data['scubaFit']];
           }
           if(isset($result[$data['cylinder']])){
                $data['cylinderVal'] = $result[$data['cylinder']];
           }
           $data['careerCoverageVal'] = $result[$data['careerCoverage']];
        }    


   	  	$temp =$data;
        if(isset($data['endorsement_options'])){
            $temp['endorsement_options'] = json_encode($data['endorsement_options']);
        }

        if(isset($data['lossPayees'])){
            $temp['lossPayees'] = json_encode(array('name' => $data['lossPayees']));
        }

        if(isset($data['additionalInsured'])){
            $temp['additionalInsured'] = json_encode($data['additionalInsured']);
        }

        if(isset($data['additionalNamedInsured'])){
            $temp['additionalNamedInsured'] = json_encode(array('name' => $data['additionalNamedInsured']));
        }

        if(isset($data['groupAdditionalInsured'])){
            $temp['groupAdditionalInsured'] = json_encode(array('name' => $data['groupAdditionalInsured']));
        }

        if(isset($data['namedInsured'])){
            $temp['namedInsured'] = json_encode($data['namedInsured']);
        }

        $destAbsolute = $dest['absolutePath'].$template.'.pdf';
        if($template == 'Group_PL_COI'){
            $options['generateOptions'] = array('disable_smart_shrinking' => 1);
        }
        $this->documentBuilder->generateDocument($template, $temp, $destAbsolute, $options);

        $data['coi_document'] = $dest['relativePath'].$template.'.pdf';

        if($data['state'] == 'California'){
            if(isset($this->template[$data['product']]['slWording'])){
                $slWording = $this->template[$data['product']]['slWording'];
                $this->documentBuilder->copyTemplateToDestination($slWording,$dest['relativePath']);
                $data['slWording'] = $dest['relativePath'].$slWording;
            }
            // $options['append'] = self::TEMPLATE[$data['product']]['append'];
        }
        if(isset($policy)){
            $this->documentBuilder->copyTemplateToDestination($policy,$dest['relativePath']);
        }

        if(isset($this->template[$data['product']]['card'])){
            $cardTemplate = $this->template[$data['product']]['card'];
            $cardDest = $dest['absolutePath'].$coi_number.'_Pocket_Card'.'.pdf';
            $this->documentBuilder->generateDocument($cardTemplate,$data,$cardDest);
            $data['card'] = $dest['relativePath'].$coi_number.'_Pocket_Card'.'.pdf';
        }

        if(isset($data['cover_letter']) && $data['cover_letter']){
            $coverTemplate = $this->template[$data['product']]['cover_letter'];
            $coverDest = $dest['absolutePath'].$coi_number.'_Cover_Letter'.'.pdf';
            $options['header'] = $this->template[$data['product']]['lheader'];
            $options['footer'] = $this->template[$data['product']]['lfooter'];
            $this->documentBuilder->generateDocument($coverTemplate,$data,$coverDest,$options);
            $data['cover_letter'] = $dest['relativePath'].$coi_number.'_Cover_Letter'.'.pdf';
        }

        if(isset($data['lossPayees'])){
            $lpTemplate = self::TEMPLATE[$data['product']]['lpTemplate'];
            $coverDest = $dest['absolutePath'].$coi_number.'_Loss_Payees'.'.pdf';
            $options['header'] = self::TEMPLATE[$data['product']]['lpheader'];
            $options['footer'] = self::TEMPLATE[$data['product']]['lpfooter'];
            $this->documentBuilder->generateDocument($lpTemplate,$data,$coverDest,$options);
            $data['loss_payee_document'] = $dest['relativePath'].$coi_number.'_Loss_Payees'.'.pdf';
        }

        if(isset($data['additionalInsured'])){
            $aiTemplate = $this->template[$data['product']]['aiTemplate'];
            $aiDest = $dest['absolutePath'].$data['product'].'_AI'.'.pdf';
            $options['header'] = $this->template[$data['product']]['aiheader'];
            $options['footer'] = $this->template[$data['product']]['aifooter'];
            $this->documentBuilder->generateDocument($aiTemplate,$data,$aiDest,$options);
            $data['ai_document'] = $dest['relativePath'].$data['product'].'_AI'.'.pdf';
        }


        if(isset(self::TEMPLATE[$data['product']]['blanketForm'])){
                $blanketform = self::TEMPLATE[$data['product']]['blanketForm'];
                $this->documentBuilder->copyTemplateToDestination($blanketform,$dest['relativePath']);
                $data['blanket_document'] = $dest['relativePath'].'Individual_AI_Blanket_Endorsement.pdf';
        }

        if(isset($data['additionalNamedInsured'])){
            $aniTemplate =  $this->template[$data['product']]['aniTemplate'];
            $aniDest = $dest['absolutePath'].$data['product'].'_ANI'.'.pdf';
            $options['header'] =  $this->template[$data['product']]['aniheader'];
            $options['footer'] =  $this->template[$data['product']]['anifooter'];
            $this->documentBuilder->generateDocument($aniTemplate,$data,$aniDest,$options);
            $data['ani_document'] = $dest['relativePath'].$data['product'].'_ANI'.'.pdf';
        }

        if(isset($data['namedInsured'])){
            $aniTemplate =  $this->template[$data['product']]['nTemplate'];
            $aniDest = $dest['absolutePath'].$data['product'].'_NI'.'.pdf';
            $options['header'] =  $this->template[$data['product']]['nheader'];
            $options['footer'] =  $this->template[$data['product']]['nfooter'];
            $this->documentBuilder->generateDocument($aniTemplate,$data,$aniDest,$options);
            $data['ani_document'] = $dest['relativePath'].$data['product'].'_NI'.'.pdf';
        }
        $this->logger->info("DATA".print_r($data,true));
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
}
