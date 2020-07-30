<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\AppDelegateTrait;

require_once __DIR__."/PolicyDocument.php"; 

class RegenarateDocuments extends PolicyDocument
{
    use FileTrait;
    use AppDelegateTrait;
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
                $this->logger->info("Endorsement File MedicalPayment PADI NO :".print_r($fileData['business_padi'],true)." File ID : ".print_r($files[$i],true));
                continue;
            }
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
            $fileData['fileId'] = $files[$i];
            $fileData['orgId'] = $data['orgId'];
            if(!isset($fileData['excludedOperation'])){
                $fileData['excludedOperation'] = "";
            }
            $options = array();
            $temp = array();
            $dest = "";
            $documents = $fileData['documents'];
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
            }
            else 
            if($fileData[$param] == 0){
                $this->logger->info("FALSE MedicalPayment PADI NO :".print_r($fileData['business_padi'],true)." File ID : ".print_r($files[$i],true));
                $this->addAdditionalData($fileData,$dest,$temp,$persistenceService);
                $this->generateDiveStoreLiabilityDocument($fileData,$documents,$temp,$dest,$options,$persistenceService);
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
}
