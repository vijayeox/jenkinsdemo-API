<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;

use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class GenerateWorkbook extends AbstractDocumentAppDelegate
{
    protected $type;
    protected $template;
    protected $carrierTemplateTypeMapping = array(
        "harco" => array(
            "type" => "excel",
            "template" => "Workbooktemplate.xlsx",
            "sheets" => array("Dealer")
        ),
        "dealerGuard" => array(
            "type" => "excel",
            "template" => "Workbooktemplate.xlsx",
            "sheets" => array("Dealer")
        ),
        "schinnererFranchisedAutoDealer" => array(
            "type" => "excel",
            "template" => "SchinnererAuto.xlsx"
        ),
        "schinnererDolApplication" => array(
            "type" => "excel",
            "template" => "SchinnererGarage.xlsx"
        ),
        "epli" => array(
            "type" => "pdf",
            "template" => "GAIC - EPLI.pdf"
        ),
        "rpsCyber" => array(
            "type" => "pdf",
            "template" => "rpsCyber.pdf"
        )
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing GenerateWorkbook with data- " . json_encode($data));
        $temp = $data;
        foreach ($temp as $key => $value) {
            if (is_array($temp[$key])) {
                $temp[$key] = json_encode($value);
            }
        }
        $data = $temp;
        $fieldTypeMappingExcel = include(__DIR__ . "/fieldMappingExcel.php");
        $fieldTypeMappingPDF = include(__DIR__ . "/fieldMappingPDF.php");
        $fileUUID = isset($data['fileId']) ? $data['fileId'] : $data['uuid'];
        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : (isset($data['orgId']) ?
            $data['orgId'] :
            AuthContext::get(AuthConstants::ORG_UUID));
        $dest =  ArtifactUtils::getDocumentFilePath($this->destination, $fileUUID, array('orgUuid' => $orgUuid));
        $this->logger->info("GenerateWorkbook Dest" . json_encode($dest));
        $generatedDocumentspath = array();
        $this->logger->info("Execute generate document ---------");
        $tempData = $data;
        foreach (json_decode($data['workbooksToBeGenerated'], true) as  $key => $templateSelected) {
            if ($templateSelected) {
                $selectedTemplate = $this->carrierTemplateTypeMapping[$key];
                $docDest = $dest['absolutePath'] .  $selectedTemplate["template"];
                if ($selectedTemplate["type"] == "excel") {
                    foreach ($fieldTypeMappingExcel as  $fieldkey => $field) {
                        $varFunction = $field["method"];
                        $data[$fieldkey] = $this->$varFunction($data[$fieldkey]);
                    }
                    $this->documentBuilder->fillExcelTemplate(
                        $selectedTemplate["template"],
                        $data,
                        $docDest,
                        $selectedTemplate["sheets"] ? $selectedTemplate["sheets"] : "Sheet1"
                    );
                    array_push(
                        $generatedDocumentspath,
                        array(
                            "file" => $dest['relativePath'] . $selectedTemplate["template"],
                            "originalName" => $selectedTemplate["template"],
                            "type" => "excel/xlsx"
                        )
                    );
                } else {
                    $pdfData = array();
                    foreach ($fieldTypeMappingPDF["text"] as  $formField => $pdfField) {
                        isset($data[$formField]) ? $pdfData[$pdfField] = $data[$formField] : null;
                    }
                    foreach ($fieldTypeMappingPDF["radio"] as  $formField => $pdfField) {
                        isset($data[$formField]) ? $pdfData[$pdfField] = $data[$formField] : null;
                    }
                    foreach ($fieldTypeMappingPDF["date"] as  $formField => $pdfField) {
                        isset($data[$formField]) ?
                            $pdfData[$pdfField] = date(
                                "m-d-Y",
                                strtotime($data[$formField])
                            )
                            : null;
                    }
                    $this->logger->info("PDF Filling Data \n" . json_encode($pdfData, JSON_PRETTY_PRINT));
                    $this->documentBuilder->fillPDFForm(
                        $selectedTemplate["template"],
                        $pdfData,
                        $docDest
                    );
                    array_push(
                        $generatedDocumentspath,
                        array(
                            "file" => $dest['relativePath'] . $selectedTemplate["template"],
                            "originalName" => $selectedTemplate["template"],
                            "type" => "file/pdf"
                        )
                    );
                }
            }
        }
        $data = $tempData;
        $data["documents"] = json_encode($generatedDocumentspath);
        $this->logger->info("Completed GenerateWorkbook with data- " . json_encode($data, JSON_PRETTY_PRINT));
        return $data;
    }

    private function parseArray($data)
    {
        $temp = array();
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        foreach ($data as $tempItem) {
            array_push($temp,  $tempItem ? "true" : "false");
        }
        return $temp;
    }
}
