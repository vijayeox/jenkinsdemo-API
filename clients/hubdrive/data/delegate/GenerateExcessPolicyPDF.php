<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Model\Field;

class GenerateExcessPolicyPDF extends AbstractDocumentAppDelegate
{
    use FileTrait;
    
    public function __construct()
    {
        parent::__construct();
        $this->formatNumber = array(
            'actsErrorsOrOmissionsLiabilityAggregate',
            'additionalPremium',
            'aggregateLimit',
            'premiumAtEachAnniversary',
            'premiumAtInception',
            'attorneysFeesForAJudgmentOf',
            'bodilyInjuryByAccidentEachAccident',
            'bodilyInjuryByDiseaseEachEmployee',
            'bodilyInjuryByDiseasePolicyLimit',
            'coveredAutosLiabilityEachAccident',
            'eachAccident',
            'eachOccurenceLimit',
            'eachOccurrence',
            'excessLimitOfInsuranceOther',
            'generalAggregate',
            'generalLiabilityAggregate',
            'generalLiabilityBodilyInjuryAndPropertyDamageLiabilityEachAccident',
            'limitOfInsurance',
            'personalAndAdvertisingInjury',
            'personalAndAdvertisingInjuryGen',
            'productsAndWorkYouPerformedAggregate',
            'productsCompletedOperationsAggregate',
            'premiumIncludingpremiumsubjecttoaudit',
        );
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $fieldMapping = include(__DIR__ . "/FieldMappingExcessPDF.php");
        $fileUUID = isset($data['uuid']) ? $data['uuid'] : $data['fileId'];
        $orgUuid = isset($data['accountId']) ? $data['accountId'] : AuthContext::get(AuthConstants::ORG_UUID);
        $fileDestination =  ArtifactUtils::getDocumentFilePath($this->destination, $fileUUID, array('orgUuid' => $orgUuid));
        $finalPolicy = array();
        $pdfName = 'RPG_excess_policy_-_Trisura__1_'.date('Y_m_d').'_FORM_Revised.pdf';
        $documentDestination = $fileDestination['absolutePath'].$pdfName;
        $fileData = $data;

        foreach($this->formatNumber as $value){
            if($fileData[$value] != ""){
                $fileData[$value] = number_format($fileData[$value],2);
            }
        }
        
        $stateObj = is_string($fileData['stateObj']) ? json_decode($fileData['stateObj'],true) : $fileData['stateObj'];
        $fileData['mailingAddress'] = $fileData['address'].",".$fileData['city'].",".$stateObj['abbreviation']."-".$fileData['zipCode'];
        $fileData['policyFrom'] = date_format(date_create($fileData['proposedPolicyStartDate']),'m/d/Y');
        $fileData['policyTo'] = date_format(date_create($fileData['proposedPolicyEndDate']),'m/d/Y');
        $fileData['policyPeriod'] = $fileData['policyFrom']." to ".$fileData['policyTo'];

        $pdfData = array();
        foreach ($fieldMapping["text"] as  $formField => $pdfField) {
            isset($fileData[$formField]) ? $pdfData[$pdfField] = $fileData[$formField] : null;
        }

        foreach ($fieldMapping['radioToCheckBox'] as $formFieldPDF => $pdfFieldData) {
            $fieldNamePDF = $fileData[$formFieldPDF];
            if(isset($fieldMapping['radioToCheckBox'][$formFieldPDF]) && in_array($fileData[$formFieldPDF], array_filter($fieldMapping['radioToCheckBox'][$formFieldPDF]))){
                if($formFieldPDF == "coverageTypeOther"){
                  $fieldNamePDF =  $fileData[$formFieldPDF]."_2";
                }
                $pdfData[$fieldNamePDF] = "On";
            }
        }

        $pdfData = array_filter($pdfData);

        $pdfData['appId'] = $fileData['appId'];
        $this->documentBuilder->fillPDFForm(
            "Excess_Final_Policy.pdf",
            $pdfData,
            $documentDestination
        );
        if(isset($data['documents'])){
            $data['documents'] = is_string($data['documents']) ? json_decode($data['documents'],true) : $data['documents'];
        }else{
            $data['documents'] = array();
        }
        $data['documents']['final_policy'] = array(
            "name" => $pdfName,
            "fullPath" => $documentDestination,
            "file" => $fileDestination['relativePath'] . $pdfName,
            "originalName" => $pdfName,
            "type" => "file/pdf",
        );
        $this->logger->info("PDF MAPPED DOCUMENT : ". print_r($data['documents'], true));
        $this->saveFile($data, $fileUUID);
        return $data;
    }
}