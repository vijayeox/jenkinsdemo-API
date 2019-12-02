<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\ArtifactUtils;

class PolicyDocument extends AbstractDocumentAppDelegate
{
    protected $documentBuilder;
    protected $type;
    protected $template;

 // Dont Delete Templates to checked
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
                     'efooter' => 'DB_Endorsement_footer.html',
                     'lpTemplate' => 'DiveBoat_LP',
                     'lpheader' => 'DiveBoat_LP_header.html',
                     'lpfooter' => 'DiveBoat_LP_footer.html') ,
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
                     'aifooter' => 'DiveStore_AI_footer.html',
                     'lpTemplate' => 'DiveStore_LP',
                     'lpheader' => 'DiveStore_LP_header.html',
                     'lpfooter' => 'DiveStore_LP_footer.html'),
        'Emergency First Response'
            => array('template' => 'Emergency_First_Response_COI',
                     'header' => 'EFR_header.html',
                     'footer' => 'EFR_footer.html',
                     'slWording' => 'SL Wording.pdf',
                     'policy' => 'Individual_Professional_Liability_Policy.pdf',
                     'aiTemplate' => 'EFR_AI',
                     'aiheader' => 'EFR_AI_header.html',
                     'aifooter' => 'EFR_AI_footer.html'),
        'Group Professional Liability'
            => array('template' => 'Group_PL_COI',
                     'header' => 'Group_header.html',
                     'footer' => 'Group_footer.html',
                     'nTemplate' => 'Group_PL_NI',
                     'nheader' => 'Group_NI_header.html',
                     'nfooter' => 'Group_NI_footer.html',
                     'policy' => 'Individual_Professional_Liability_Policy.pdf'));

        $this->jsonOptions = array('endorsement_options','additionalInsured','namedInsured','additionalNamedInsured','lossPayees','groupAdditionalInsured');
       
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
        $documents = array();
        $options = array();
        $this->logger->info("Template Data Source - ".print_r($data, true));
        $date = ''; 
        $this->logger->info("Executing Policy Document");
        
        $this->setPolicyInfo($data,$persistenceService);

        $dest = $data['dest'];
        unset($data['dest']);

        if(isset($data['careerCoverage']) || isset($data['scubaFit']) || isset($data['cylinder']) || isset($data['equipment'])){
            $coverageList = array();
            array_push($coverageList,$data['careerCoverage']);
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
                $data['equipmentVal'] = 'Included';
           }
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

        if(isset($this->template[$data['product']]['instruct'])){
            $documents['instruct'] = $this->copyDocuments($data,$dest['relativePath'],'instruct');
        }

        if($this->type == 'lapse'){
            return $this->generateDocuments($data,$dest,$options,'ltemplate','lheader','lfooter');
        }
    
        $temp = $data;
        foreach ($this->jsonOptions as $val){
            if(array_key_exists($val, $temp)){
                 $temp[$val] = json_encode($data[$val]);
            }
        }
        

        if(isset($temp['liability'])){
            $temp['liability'] = json_encode($temp['liability']);
            $documents['liability_coi_document'] = $this->generateDocuments($temp,$dest,$options,'template','header','footer','liability');
            if($this->type != 'quote')
            {
                $documents['liability_policy_document'] = $this->copyDocuments($temp,$dest['relativePath'],'policy','liability');
            }
        }


        if(isset($temp['property'])){
            $temp['property'] = json_encode($temp['property']);
            $documents['property_coi_document']  = $this->generateDocuments($temp,$dest,$options,'template','header','footer','property');
            if($this->type != 'quote')
            {
                $documents['property_policy_document'] = $this->copyDocuments($temp,$dest['relativePath'],'policy','property');
            }
        }

        if(!isset($documents['property_coi_document']) && !isset($documents['liability_coi_document'])){
            $documents['coi_document']  = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
            if($this->type != 'quote')
            {
                $documents['policy_document'] = $this->copyDocuments($temp,$dest['relativePath'],'policy');
            }
        }
        
        if($temp['state'] == 'California'){
            if(isset($this->template[$temp['product']]['slWording'])){
                $documents['slWording'] = $this->copyDocuments($temp,$dest['relativePath'],'slWording');
            }
        }

        if(isset($this->template[$temp['product']]['card'])){
            $documents['pocket_card'] = $this->generateDocuments($temp,$dest,$options,'card');
        }

        if(isset($this->template[$temp['product']]['cover_letter'])){
            $documents['cover_letter'] = $this->generateDocuments($temp,$dest,$options,'cover_letter','lheader','lfooter');
        }

        if(isset($temp['lossPayees'])){
            $documents['loss_payee_document'] = $this->generateDocuments($temp,$dest,$options,'lpTemplate','lpheader','lpfooter');
        }

        if(isset($temp['additionalInsured'])){
            $documents['additionalInsured_document'] = $this->generateDocuments($temp,$dest,$options,'aiTemplate','aiheader','aifooter');
        }


        if(isset($this->template[$temp['product']]['blanketForm'])){
            $documents['blanket_document'] = $this->copyDocuments($temp,$dest['relativePath'],'blanketForm');
        }

        if(isset($temp['additionalNamedInsured'])){
            $documents['additionalNamedInsured_document'] = $this->generateDocuments($temp,$dest,$options,'aniTemplate','aniheader','anifooter');
        }

        if(isset($temp['namedInsured'])){
            $documents['named_insured_document'] = $this->generateDocuments($temp,$dest,$options,'nTemplate','nheader','nfooter');
        }
        $this->logger->info("temp".print_r($data,true));
        $data['documents'] = $documents;                              
        return $data;
    }



    protected function setPolicyInfo(&$data,$persistenceService)
    {
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

        $data['dest'] = ArtifactUtils::getDocumentFilePath($this->destination,$data['uuid'],array('orgUuid' => $orgUuid));

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


    private function generateDocuments(&$data,$dest,$options,$templateKey,$headerKey = null,$footerKey = null,$indexKey = null){
        if(isset($indexKey)){
            $template =  $this->template[$data['product']][$templateKey][$indexKey];
        }else{
            $template =  $this->template[$data['product']][$templateKey];
        }
        
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
        $this->documentBuilder->generateDocument($template,$data,$docDest,$options);
        if($this->type == 'lapse'){
            $data['documents']['lapse_document'] = $data['dest']['relativePath'].$template.'.pdf'; 
            return $data;
        }
        return $dest['relativePath'].$template.'.pdf';
    }

    private function copyDocuments(&$data,$dest,$fileKey,$indexKey =null){
        if(isset($indexKey)){
            $file =  $this->template[$data['product']][$fileKey][$indexKey];
        }else{
            $file =  $this->template[$data['product']][$fileKey];
        }
        $this->documentBuilder->copyTemplateToDestination($file,$dest);
        return $dest.$file;
    }
}
