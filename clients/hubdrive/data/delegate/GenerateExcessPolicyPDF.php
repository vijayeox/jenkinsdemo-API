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
        $pdfData = array();
        foreach ($fieldMapping["text"] as  $formField => $pdfField) {
            isset($data[$formField]) ? $pdfData[$pdfField] = $data[$formField] : null;
        }
        if(isset($fieldMapping["checkbox"])){
            foreach ($fieldMapping["checkbox"] as $formField => $fieldProps) {
                $fieldNamePDF = $fieldProps['fieldname'];
                $fieldOptions = $fieldProps["options"];
                if (isset($data[$formField]) && $data[$formField] == 'true') {
                    $pdfData[$fieldNamePDF] = $fieldOptions[$data[$formField]];
                }
            }
        }
        $pdfData = array_filter($pdfData);
        $pdfData['appId'] = $data['appId'];
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