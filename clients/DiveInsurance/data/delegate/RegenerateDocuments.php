<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\AppDelegateTrait;
use Oxzion\AppDelegate\WorkflowTrait;

require_once __DIR__."/PolicyDocument.php"; 

class RegenerateDocuments extends PolicyDocument
{
    use FileTrait;
    use AppDelegateTrait;
    use WorkflowTrait;
    public function __construct(){
        parent::__construct();
    }

    // RegenarateDocuments values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {  
      $files = $data['fileUuid'];
      $param = $data['param'];
      for($i = 0;$i < sizeof($files);$i ++){
        $result = $this->getFile($files[$i],false,$data['orgId']);
        $fileData = $result['data'];
        if(isset($fileData['endorsement_options'])){
            if ($fileData['product'] == 'Individual Professional Liability') {
                $this->logger->info("PADI NUMBER -----".print_r($fileData['padi'],true));
                continue;
            }
        }        
        $fileData['fileId'] = $files[$i];
        $fileData['orgId'] = $data['orgId'];
        if(!isset($fileData['excludedOperation'])){
            $fileData['excludedOperation'] = "";
        }
        $options = array();
        $temp = array();
        $dest = "";
        $documents = $fileData['documents'];
        if($param == "tecRecEndorsment"){
            if ($fileData[$param] == 'withTecRecEndorsementForSelectionAbove') {
                $this->addAdditionalData($fileData,$dest,$temp,$persistenceService);
                 if(isset($data['previous_policy_data'])){
                    $previous_data = array();
                    $previous_data = is_string($data['previous_policy_data']) ? json_decode($data['previous_policy_data'],true) : $data['previous_policy_data'];
                    $length = sizeof($previous_data);
                }else{
                    $previous_data = array();
                }
                if (is_string($documents)) {
                    $documents = json_decode($documents,true);
                }
                $this->setCoverageDetails($fileData,$previous_data,$temp,$documents,$persistenceService); 
                $policyDocuments = $this->generateDocuments($temp,$dest,$options,'template','header','footer');
                $this->policyCOI($policyDocuments,$temp,$documents); 

            }
        }
        if ($param == 'medicalPayment') {
            $this->processFileData($fileData,$documents);
            if($fileData[$param] == 1){
                if(!isset($fileData['MedicalExpenseFP'])){
                    $fileData['MedicalExpenseFP'] = 0;
                }
                if($fileData['MedicalExpenseFP'] == 0){
                    $this->logger->info("TRUE MedicalPayment PADI NO :".print_r($fileData['business_padi'],true)." File ID : ".print_r($files[$i],true));
                    $MedicalExpenseFP = $fileData['MedicalExpenseFP'];
                    $liabilityCoveragesTotalPL = $fileData['liabilityCoveragesTotalPL'];
                    $liabilityPropertyCoveragesTotalPL = $fileData['liabilityPropertyCoveragesTotalPL'];
                    $liabilityProRataPremium = $fileData['liabilityProRataPremium'];
                    $ProRataPremium = $fileData['ProRataPremium'];
                    $LiaTax =  $fileData['LiaTax'];
                    $totalAmount = $fileData['totalAmount'];
                    $amount = $fileData['amount'];
                    $fileData['MedicalExpenseFP'] = 72;

                    $fileData['liabilityCoveragesTotalPL'] = (float)$fileData['liabilityCoveragesTotalPL'] + (float)$fileData['MedicalExpenseFP'];

                    $fileData['liabilityPropertyCoveragesTotalPL'] = (float)$fileData['liabilityCoveragesTotalPL'] + (float)$fileData['propertyCoveragesTotalPL'];

                    $fileData['liabilityProRataPremium'] = (float)$fileData['liabilityCoveragesTotalPL'] * (float)$fileData['proRataPercentage'];

                    $fileData['ProRataPremium'] = (float)$fileData['liabilityProRataPremium'] + (float)$fileData['propertyProRataPremium'];
                    $fileData['ProRataPremium'] = round($fileData['ProRataPremium'],0);

                    $fileData['LiaTax'] = ((float)$fileData['liabilityProRataPremium']*(float)$fileData['liabilityTaxPL'])/100;


                    $fileData['totalAmount'] = (float)$fileData['ProRataPremium'] + (float)$fileData['PropTax'] + (float)$fileData['LiaTax'] + (float)$fileData['AddILocPremium'] + (float)$fileData['AddILocTax'] + (float)$fileData['padiFeePL'] + (float)$fileData['PAORFee'] + (float)$fileData['groupProfessionalLiability'];


                    $fileData['totalAmount'] = round($fileData['totalAmount'],2);

                    $fileData['amount'] = $fileData['totalAmount'];
                    $this->addAdditionalData($fileData,$dest,$temp,$persistenceService);
                    $this->generateDiveStoreLiabilityDocument($fileData,$documents,$temp,$dest,$options,$persistenceService);
                    $this->generateDiveStorePremiumSummary($temp,$documents,$dest,$options);
                    $this->saveFile($fileData,$fileData['fileId']);
                }else{
                    $this->addAdditionalData($fileData,$dest,$temp,$persistenceService);
                    $this->generateDiveStoreLiabilityDocument($fileData,$documents,$temp,$dest,$options,$persistenceService);
                }
            }else if($fileData[$param] == 0){
                $this->logger->info("FALSE MedicalPayment PADI NO :".print_r($fileData['business_padi'],true)." File ID : ".print_r($files[$i],true));
                $this->addAdditionalData($fileData,$dest,$temp,$persistenceService);
                $this->generateDiveStoreLiabilityDocument($fileData,$documents,$temp,$dest,$options,$persistenceService);
            }
        }
        if($param == 'propertyDS'){
            $this->processFileData($fileData,$documents);
            $this->addAdditionalData($fileData,$dest,$temp,$persistenceService);
            $this->generateDiveStorePropertyDocument($fileData,$documents,$temp,$dest,$options,$persistenceService);
        }
        if($param == 'propertyDSEndo'){
            $resultData = $this->getWorkflowInstanceDataFromFileId($fileData['fileId']);
            foreach($resultData as $file){
                if(!isset($file['parent_workflow_instance_id'])){
                    $newFileData = $file['completion_data'];
                    $newFileData =  json_decode($newFileData,true);
                    $newFileData['fileId'] = $files[0];
                    $newFileData['orgId'] = $data['orgId'];
                    $this->processFileData($newFileData,$documents);
                    $this->addAdditionalData($newFileData,$dest,$temp,$persistenceService);
                    $this->generateDiveStorePropertyDocument($newFileData,$documents,$temp,$dest,$options,$persistenceService);        
                }
            }
        }
        if($param == "policyPdf"){
            $this->processFileData($fileData,$documents);
            $orgUuid = $this->processDate($fileData);
            $dest = $this->documentsLocation(null,$fileData,$orgUuid);
            $this->additionalDocumentsDS($fileData,$documents,$dest);
            if($fileData['groupProfessionalLiabilitySelect'] == "yes"){
                $documents['group_policy_document'] = $this->copyDocuments($fileData,$dest['relativePath'],'groupPolicy');    
            }
            $fileData['documents'] = $documents;
            $this->saveFile($fileData,$fileData['fileId']);
        }
        if($param == "propDCPS"){
            $this->processFileData($fileData,$documents);
            $this->addAdditionalData($fileData,$dest,$temp,$persistenceService);
            $this->generateDiveStorePremiumSummary($temp,$documents,$dest,$options);
        }
        if($param == "fileSave"){
            $this->processFileData($fileData,$documents);
            $fileData['documents'] = $documents;
            $this->saveFile($fileData,$fileData['fileId']);
        }
        if($param == "retainPolicy"){
            $this->processFileData($fileData,$documents);
            if(isset($fileData['reinstateDocuments'])){
                $fileData['documents'] = array_merge($documents,$fileData['reinstateDocuments']);
            }
            unset($documents['reinstateDocuments']);
            $this->saveFile($fileData,$fileData['fileId']);
        }
        if($param == "updateCancelDate"){
            $this->processFileData($fileData,$documents);
            $fileData['policyEndDate'] = date_format(date_create($fileData['end_date']),'Y-m-d');;
            $fileData['end_date'] = date_format(date_create($fileData['cancelDate']),'Y-m-d');
            $fileData['documents'] = $documents;
            $this->saveFile($fileData,$fileData['fileId']);
        }
      }
    }

    private function addAdditionalData(&$fileData,&$dest,&$temp,$persistenceService){
        $this->processSurplusYear($fileData);
        $orgUuid = $this->processDate($fileData);
        $dest = $this->documentsLocation(null,$fileData,$orgUuid);
        $fileData['state_in_short'] = $this->getStateInShort($fileData['state'],$persistenceService);
        $temp = $fileData;
        $this->processData($temp);
    }

    private function processFileData(&$fileData,&$documents){
        foreach ($fileData as $key => $value) {
            if(is_string($value)){
                $tempValue = json_decode($value,true);
                if(isset($tempValue)){
                    $fileData[$key] = $tempValue;
                }else if($value === "false"){
                    $fileData[$key] = 0;
                }else if($value === "true"){
                    $fileData[$key] = 1;
                }
            }
        }
        $documents = $fileData['documents'];
    }
}
