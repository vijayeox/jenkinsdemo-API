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
                     'aiheader' => 'IPL_AI_header.html'),
        'Dive Boat' 
            => array('template' => 'DiveBoatCOI',
                     'header' => 'DiveBoatHeader.html',
                     'footer' => 'DiveBoatFooter.html',
                     'slWording' => 'SL Wording.pdf',
                     'policy' => 'Dive_Boat_Policy.pdf'),
        'Dive Store'
            => array('template' => 'DiveStoreCOI',
                     'header' => 'DiveStoreHeader.html',
                     'footer' => 'DiveStoreFooter.html',
                     'slWording' => 'SL Wording.pdf',
                     'policy' => array('liability' => '','property' => 'Policy.pdf')));

    // public function __construct(){

    // }

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
        $this->logger->info("Executing Policy Document");
        $coi_number = $this->generateCOINumber($data,$persistenceService);
        $license_number = $this->getLicenseNumber($data,$persistenceService);
        $policyDetails = $this->getPolicyDetails($data,$persistenceService);
        $data['certificate_no'] = $coi_number;
        $data['license_number'] = $license_number;
        
        if($policyDetails){
            $data['policy_id'] = $policyDetails['policy_number'];
            $data['carrier'] = $policyDetails['carrier'];
        } 
        $template = self::TEMPLATE[$data['product']]['template'];
        $policy = self::TEMPLATE[$data['product']]['policy'];
        $options = array();
        $options['header'] = self::TEMPLATE[$data['product']]['header'];
        $options['footer'] = self::TEMPLATE[$data['product']]['footer'];
        
        if(!isset($data['uuid'])){
            $data['uuid'] = UuidUtil::uuid();
        }
       
        $dest = ArtifactUtils::getDocumentFilePath($this->destination,$data['uuid']);
        $destAbsolute = $dest['absolutePath'].$template.'.pdf';
        $this->documentBuilder->generateDocument($template, $data, $destAbsolute, $options);
        
        $data['coi_document'] = $dest['relativePath'].$template.'.pdf';
        $data['policy_document'] = $dest['relativePath'].$policy;
        if($data['state'] == 'California'){
            $slWording = self::TEMPLATE[$data['product']]['slWording'];
            $this->documentBuilder->copyTemplateToDestination($slWording,$dest['relativePath']);
            $data['slWording'] = $dest['relativePath'].$slWording;
            // $options['append'] = self::TEMPLATE[$data['product']]['append'];
        }
        $this->documentBuilder->copyTemplateToDestination($policy,$dest['relativePath']);
        if(isset(self::TEMPLATE[$data['product']]['card'])){
            $cardTemplate = self::TEMPLATE[$data['product']]['card'];
            $cardDest = $dest['absolutePath'].$coi_number.'_Pocket_Card'.'.pdf';
            $this->documentBuilder->generateDocument($cardTemplate,$data,$cardDest);
            $data['card'] = $dest['relativePath'].$coi_number.'_Pocket_Card'.'.pdf';
        } 

        if(isset($data['addInsured'])){
            $aiTemplate = self::TEMPLATE[$data['product']]['aiTemplate'];
            $aiDest = $dest['absolutePath'].$coi_number.'_AI'.'pdf';
            // $options['header'] = self::TEMPLATE[$data['product']]['aiheader'];
            $this->documentBuilder->generateDocument($aiTemplate,$data,$aiDest,$options);
            $data['ai_document'] = $dest['relativePath'].$coi_number.'_AI'.'.pdf';
            if(isset(self::TEMPLATE[$data['product']]['blanketForm'])){
                $blanketform = self::TEMPLATE[$data['product']]['blanketForm'];
                $this->documentBuilder->copyTemplateToDestination($blanketform,$dest['relativePath']);
                $data['blanket_document'] = $dest['relativePath'].$coi_number.'Individual_AI_Blanket_Endorsement.pdf';
            }
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
