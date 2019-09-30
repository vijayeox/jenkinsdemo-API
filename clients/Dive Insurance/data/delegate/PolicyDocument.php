<?php

use Oxzion\AppDelegate\DocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Encryption\Crypto;

class PolicyDocument implements DocumentAppDelegate
{
    private $logger;
    private $documentBuilder;
    protected $type;
    protected $template;
    // 'append' => array(__DIR__.'/../template/SL Wording.pdf'),
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
                     'aifooter' => null),
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
                     'aifooter' => 'DiveBoat_AI_footer.html'),
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
                     'aifooter' => 'DiveStore_AI_footer.html'));
    }

    public function setLogger($logger){
        $this->logger = $logger;
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
        $date = ''; 
        $this->logger->info("Executing Policy Document");
        $coi_number = $this->generateCOINumber($data,$persistenceService);
        $license_number = $this->getLicenseNumber($data,$persistenceService);
        $policyDetails = $this->getPolicyDetails($data,$persistenceService);
        $data['certificate_no'] = $coi_number;
        $data['license_number'] = $license_number;
        $date=date_create($data['start_date']);
        $data['start_date'] = date_format($date,"m/d/Y");
        $date=date_create($data['end_date']);
        $data['end_date'] = date_format($date,"m/d/Y");

        if($policyDetails){
            $data['policy_id'] = $policyDetails['policy_number'];
            $data['carrier'] = $policyDetails['carrier'];
        } 
        
    
        $options = array();
        $options['header'] = $this->template[$data['product']]['header'];
        $options['footer'] = $this->template[$data['product']]['footer'];

        if(!isset($data['uuid'])){
            $data['uuid'] = UuidUtil::uuid();
        }
       
        $dest = ArtifactUtils::getDocumentFilePath($this->destination,$data['uuid']);

        if(isset($this->template[$data['product']]['instruct'])){
            $instruct = $this->template[$data['product']]['instruct'];
            $this->documentBuilder->copyTemplateToDestination($instruct,$dest['relativePath']);
            $data['instruction_document'] = $dest['relativePath'].$instruct;
        }
        
        if($data['product'] == 'Dive Store'){
            if(isset($data['liability'])){
                $template = $this->template[$data['product']]['template']['liability'];
                if(isset($this->template[$data['product']]['policy'])){
                    $policy = $this->template[$data['product']]['policy']['liability'];
                    $data['policy_document'] = $dest['relativePath'].$policy;
                }
            }else if(isset($data['property'])){
                $template = $this->template[$data['product']]['template']['property'];
                if(isset($this->template[$data['product']]['policy'])){
                    $policy = $this->template[$data['product']]['policy']['property'];
                    $data['policy_document'] = $dest['relativePath'].$policy;
                }
            }
        }else{
            $template = $this->template[$data['product']]['template'];
            if(isset($this->template[$data['product']]['policy'])){
                $policy = $this->template[$data['product']]['policy'];
                $data['policy_document'] = $dest['relativePath'].$policy;
            }
        }
        $destAbsolute = $dest['absolutePath'].$template.'.pdf';

        $this->documentBuilder->generateDocument($template, $data, $destAbsolute, $options);
        
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

        // if(isset($data['additionalInsured'])){
        //     $aiTemplate = self::TEMPLATE[$data['product']]['aiTemplate'];
        //     $aiDest = $dest['absolutePath'].$coi_number.'_AI'.'.pdf';
        //     $options['header'] = self::TEMPLATE[$data['product']]['aiheader'];
        //     if($data['product'] == 'Dive Store' || $data['product'] == 'Dive Boat'){
        //         $options['footer'] = self::TEMPLATE[$data['product']]['aifooter'];
        //     }
        //     $this->documentBuilder->generateDocument($aiTemplate,$data,$aiDest,$options);
        //     $data['ai_document'] = $dest['relativePath'].$coi_number.'_AI'.'.pdf';
        //     if(isset(self::TEMPLATE[$data['product']]['blanketForm'])){
        //         $blanketform = self::TEMPLATE[$data['product']]['blanketForm'];
        //         $this->documentBuilder->copyTemplateToDestination($blanketform,$dest['relativePath']);
        //         $data['blanket_document'] = $dest['relativePath'].$coi_number.'Individual_AI_Blanket_Endorsement.pdf';
        //     }
        // }

        // if(isset($data['lapseletter'])){
        //     $lapseTemplate = self::TEMPLATE[$data['product']]['ltemplate'];
        //     $lapseDest = $dest['absolutePath'].$coi_number.'_Lapse_Letter'.'.pdf';
        //     $options['header'] = self::TEMPLATE[$data['product']]['lheader'];
        //     $options['footer'] = self::TEMPLATE[$data['product']]['lfooter'];
        //     $this->documentBuilder->generateDocument($lapseTemplate,$data,$lapseDest,$options);
        //     $data['lapse_document'] = $dest['relativePath'].$coi_number.'_LapseLetter'.'.pdf';
        // }

        if(isset($data['cover_letter']) && $data['cover_letter']){
            $coverTemplate = $this->template[$data['product']]['cover_letter'];
            $coverDest = $dest['absolutePath'].$coi_number.'_Cover_Letter'.'.pdf';
            $options['header'] = $this->template[$data['product']]['lheader'];
            $options['footer'] = $this->template[$data['product']]['lfooter'];
            $this->documentBuilder->generateDocument($coverTemplate,$data,$coverDest,$options);
            $data['cover_letter'] = $dest['relativePath'].$coi_number.'_Cover_Letter'.'.pdf';
        }

        // if(isset($data['lossPayees'])){
        //     $lpTemplate = self::TEMPLATE[$data['product']]['lpTemplate'];
        //     $coverDest = $dest['absolutePath'].$coi_number.'_Loss_Payees'.'.pdf';
        //     $options['header'] = self::TEMPLATE[$data['product']]['lpheader'];
        //     $options['footer'] = self::TEMPLATE[$data['product']]['lpfooter'];
        //     $this->documentBuilder->generateDocument($lpTemplate,$data,$coverDest,$options);
        //     $data['loss_payee_document'] = $dest['relativePath'].$coi_number.'_Loss_Payees'.'.pdf';
        // }

        if(isset($data['additionalInsured'])){
            $aiTemplate = $this->template[$data['product']]['aiTemplate'];
            $aiDest = $dest['absolutePath'].$data['product'].'_AI'.'.pdf';
            $options['header'] = $this->template[$data['product']]['aiheader'];
            $options['footer'] = $this->template[$data['product']]['aifooter'];
            $this->documentBuilder->generateDocument($aiTemplate,$data,$aiDest,$options);
            $data['ai_document'] = $dest['relativePath'].$data['product'].'_AI'.'.pdf';
            if(isset(self::TEMPLATE[$data['product']]['blanketForm'])){
                $blanketform = self::TEMPLATE[$data['product']]['blanketForm'];
                $this->documentBuilder->copyTemplateToDestination($blanketform,$dest['relativePath']);
                $data['blanket_document'] = $dest['relativePath'].$coi_number.'Individual_AI_Blanket_Endorsement.pdf';
            }
        }

        // if(isset($data['additionalNamedInsured'])){
        //     $qTemplate = $this->template[$data['product']]['aiTemplate'];
        //     $quoteDest = $dest['absolutePath'].$data['product'].'_Quote_AI'.'.pdf';
        //     $options['header'] = $this->template[$data['product']]['qaiHeader'];
        //     $options['footer'] = null;
        //     $this->documentBuilder->generateDocument($qTemplate,$data,$quoteDest,$options);
        //     $data['quote_document'] = $dest['relativePath'].$data['product'].'_Quote_AI'.'.pdf';
        // }

         if(isset($data['additionalNamedInsured'])){
            $aniTemplate =  $this->template[$data['product']]['aniTemplate'];
            $aniDest = $dest['absolutePath'].$data['product'].'_ANI'.'.pdf';
            $options['header'] =  $this->template[$data['product']]['aniheader'];
            $options['footer'] =  $this->template[$data['product']]['anifooter'];
            $this->documentBuilder->generateDocument($aniTemplate,$data,$aniDest,$options);
            $data['ani_document'] = $dest['relativePath'].$data['product'].'_ANI'.'.pdf';
        }

        
        return $data;
    }

    private function generateCOINumber($data,$persistenceService)
    {  
        $sequence = 0;
        $year = date('Y', strtotime($data['end_date']));
        $persistenceService->beginTransaction();
        try{ 
            $select1 = "Select * FROM certificate_of_insurance_number WHERE product ='".$data['product']."' AND year = $year FOR UPDATE";
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
