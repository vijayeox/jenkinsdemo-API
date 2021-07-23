<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\AppDelegate\AccountTrait;

class ComplianceChecklistPDFGeneration extends AbstractDocumentAppDelegate
{
    use FileTrait;
    use AccountTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $fieldTypeMappingPDF = include(__DIR__ . "/FieldMappingComplianceChecklist.php");
        $complianceChecklistPDFTemplate = "ComplianceChecklist_OnTrac";
        $fileUUID = isset($data['uuid']) ? $data['uuid'] : $data['fileId'];
        $orgUuid = isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID); 

        $currentAccount = isset($data['accountId']) ? $data['accountId'] : null;
        $accountId = isset($data['accountName']) ? $this->getAccountByName($data['accountName']) : (isset($currentAccount) ? $currentAccount : AuthContext::get(AuthConstants::ACCOUNT_UUID));
        $this->logger->info("ACCOUT IS ____" . $accountId);
        $fileDestination =  ArtifactUtils::getDocumentFilePath($this->destination, $fileUUID, array('orgUuid' => $orgUuid));
        $pdfData = array();
        $generatedComplianceChecklistPDF = array();
        $documentpdf = array();
        $documentDestination = $fileDestination['absolutePath'] . $complianceChecklistPDFTemplate . ".pdf";

        $form = array();
        foreach($data as $key=>$value) {
            if($value==true) {
                if(isset($fieldTypeMappingPDF["checkbox"][$key])) {
                    array_push($form, $key);
                }
            }
        }
        foreach($form as $i) {
            $pdfData[$fieldTypeMappingPDF["checkbox"][$i]['fieldname']] = $fieldTypeMappingPDF["checkbox"][$i]["options"]["true"];
        }
        
        $pdfData = array_filter($pdfData);
        $pdfData['appId'] = $data['appId'];
        $this->documentBuilder->fillPDFForm(
            $complianceChecklistPDFTemplate . ".pdf",
            $pdfData,
            $documentDestination
        );
        $documentpdf = $fileDestination['relativePath'] . $complianceChecklistPDFTemplate . ".pdf";
        array_push(
            $generatedComplianceChecklistPDF,
            array(
                "name" => $complianceChecklistPDFTemplate . ".pdf",
                "fullPath" => $documentDestination,
                "file" => $documentpdf,
                "originalName" => $complianceChecklistPDFTemplate . ".pdf",
                "type" => "file/pdf",
            )
        );
        $data['attachments'] = $generatedComplianceChecklistPDF;
        $this->logger->info("PDF MAPPED DOCUMENT : " . print_r($pdfData, true));
        $this->saveFile($data, $fileUUID);
        return $data;
    }
}