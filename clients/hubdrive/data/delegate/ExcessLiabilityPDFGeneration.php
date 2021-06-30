<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\AppDelegate\AccountTrait;

class ExcessLiabilityPDFGeneration extends AbstractDocumentAppDelegate
{
    use FileTrait;
    use AccountTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $fieldTypeMappingPDF = include(__DIR__ . "/FieldMappingExcessLiability.php");
        $excessLiabilityPDFTemplate = "ABExcessApplicationFINALFORM";
        $fileUUID = isset($data['uuid']) ? $data['uuid'] : $data['fileId'];

        $currentAccount = isset($data['accountId']) ? $data['accountId'] : null;
        $accountId = isset($data['accountName']) ? $this->getAccountByName($data['accountName']) : (isset($currentAccount) ? $currentAccount : AuthContext::get(AuthConstants::ACCOUNT_UUID));
        $this->logger->info("ACCOUT IS ____" . $accountId);
        $fileDestination =  ArtifactUtils::getDocumentFilePath($this->destination, $fileUUID, array('accountId' => $accountId));
        $pdfData = array();
        $generatedExcessLiabilityPDF = array();
        $documentpdf = array();
        $documentDestination = $fileDestination['absolutePath'] . $excessLiabilityPDFTemplate . ".pdf";
        foreach ($fieldTypeMappingPDF[$excessLiabilityPDFTemplate]["text"] as  $formField => $pdfField) {
            isset($data[$formField]) ? $pdfData[$pdfField] = $data[$formField] : null;
        }
        $pdfData = array_filter($pdfData);
        $pdfData['appId'] = $data['appId'];
        $this->documentBuilder->fillPDFForm(
            $excessLiabilityPDFTemplate . ".pdf",
            $pdfData,
            $documentDestination
        );
        $documentpdf = $fileDestination['relativePath'] . $excessLiabilityPDFTemplate . ".pdf";
        array_push(
            $generatedExcessLiabilityPDF,
            array(
                "name" => $excessLiabilityPDFTemplate . ".pdf",
                "fullPath" => $documentDestination,
                "file" => $documentpdf,
                "originalName" => $excessLiabilityPDFTemplate . ".pdf",
                "type" => "file/pdf",
            )
        );
        $data['attachments'] = $generatedExcessLiabilityPDF;
        $this->logger->info("PDF MAPPED DOCUMENT : " . print_r($data['attachments'], true));
        $this->saveFile($data, $fileUUID);
        return $data;
    }
}
