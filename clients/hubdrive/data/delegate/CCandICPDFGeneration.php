<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\CommentTrait;
use Oxzion\AppDelegate\EsignTrait;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Model\Field;

class CCandICPDFGeneration extends AbstractDocumentAppDelegate
{
    use FileTrait;
    use CommentTrait;
    use EsignTrait;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function execute(array $data, Persistence $persistenceService)
    {
        $fieldTypeMappingPDF = include(__DIR__ . "/FieldMappingCCPDF.php");
        $this->logger->info("PDF MAPPING DATA : ". print_r($data, true));
        $PDFTemplateList = array("CC");
        $fileUUID = isset($data['uuid']) ? $data['uuid'] : $data['fileId'];
        $orgUuid = isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID);
        $fileDestination =  ArtifactUtils::getDocumentFilePath($this->destination, $fileUUID, array('orgUuid' => $orgUuid));
        $this->logger->info("GenerateFilledPDF Dest" . json_encode($fileDestination, JSON_UNESCAPED_SLASHES));
        $generatedPDFList = array();
        foreach ($PDFTemplateList as $selectedTemplate) {
            $this->logger->info("selected template",$selectedTemplate);
            $pdfData = array();
            $documentDestination = $fileDestination['absolutePath'].$selectedTemplate .".pdf";
                foreach ($fieldTypeMappingPDF[$selectedTemplate]["text"] as  $formField => $pdfField) {
                isset($data[$formField]) ? $pdfData[$pdfField] = $data[$formField] : null;
                }
                $data['pdfDate'] = explode('T',$data['freightBrokerDetailsDate'])[0];
                $pdfData['freightBrokerDetailsDate'] = date("m/d/Y", strtotime($data['pdfDate']));
                $pdfData = array_filter($pdfData);
                $pdfData['appId'] = $data['appId'];
                $this->logger->info("PDF Filling Data \n" . json_encode($pdfData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->documentBuilder->fillPDFForm(
                $selectedTemplate.".pdf",
                $pdfData,
                $documentDestination
            );
            $documentpdf = $fileDestination['relativePath'] . $selectedTemplate.".pdf";
            array_push(
                $generatedPDFList,
                array(
                    "fullPath" => $documentDestination,
                    "file" => $documentpdf,
                    "originalName" => $selectedTemplate.".pdf",
                )
            );  
    }
    $data['attachments'] = $generatedPDFList;
        $this->logger->info("PDF MAPPING : ". print_r($data['attachments'], true));
        $this->saveFile($data, $fileUUID);
        return $data;
}
}