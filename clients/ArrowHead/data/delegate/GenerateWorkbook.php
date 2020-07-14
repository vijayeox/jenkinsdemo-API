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
            "template" => "GAIC - EPLI.pdf",
            "customData" => "gaicEpliPdfData"
        ),
        "rpsCyber" => array(
            "type" => "pdf",
            "template" => "rpsCyber.pdf",
            "customData" => "rpsCyberPDFData"
        )
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing GenerateWorkbook with data- " . json_encode($data));
        // $temp = $data;
        // foreach ($temp as $key => $value) {
        //     if (is_array($temp[$key])) {
        //         $temp[$key] = json_encode($value);
        //     }
        // }
        // $data = $temp;
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
        foreach (json_decode($data['workbooksToBeGenerated'], true) as  $key => $templateSelected) {
            if ($templateSelected) {
                $selectedTemplate = $this->carrierTemplateTypeMapping[$key];
                $docDest = $dest['absolutePath'] .  $selectedTemplate["template"];
                if ($selectedTemplate["type"] == "excel") {
                    $excelData = array();
                    foreach ($fieldTypeMappingExcel as  $fieldkey => $field) {
                        $varFunction = $field["method"];
                        $excelData[$fieldkey] = $this->$varFunction($data[$fieldkey]);
                    }
                    $this->documentBuilder->fillExcelTemplate(
                        $selectedTemplate["template"],
                        $excelData,
                        $docDest,
                        $selectedTemplate["sheets"] ? $selectedTemplate["sheets"] : "Sheet1"
                    );
                    array_push(
                        $generatedDocumentspath,
                        array(
                            "fullPath" => $docDest,
                            "file" => $dest['relativePath'] . $selectedTemplate["template"],
                            "originalName" => $selectedTemplate["template"],
                            "type" => "excel/xlsx"
                        )
                    );
                } else {
                    $pdfData = array();
                    foreach ($fieldTypeMappingPDF[$key]["text"] as  $formField => $pdfField) {
                        isset($data[$formField]) ? $pdfData[$pdfField] = $data[$formField] : null;
                    }
                    foreach ($fieldTypeMappingPDF[$key]["radioYN"] as  $formFieldPDF => $pdfFieldData) {
                        if (isset($data[$formFieldPDF])) {
                            $fieldNamePDFData = $pdfFieldData["fieldname"];
                            if (isset($pdfFieldData["options"])) {
                                $fieldOption = $pdfFieldData["options"];
                                $optionKeys = array_keys($fieldOption);
                                $pdfData[$fieldNamePDFData] = ($data[$formFieldPDF] == $optionKeys[0]) ?
                                    $fieldOption[array_key_first($fieldOption)] : $fieldOption[array_key_last($fieldOption)];
                            } else {
                                $pdfData[$fieldNamePDFData] = $data[$formFieldPDF];
                            }
                        }
                    }
                    if (isset($fieldTypeMappingPDF[$key]["checkbox"])) {
                        foreach ($fieldTypeMappingPDF[$key]["checkbox"] as  $formChildField => $fieldProps) {
                            if (isset($data[$fieldProps["parentKey"]]) && !empty($data[$fieldProps["parentKey"]])) {
                                $fieldNamePDFData = $fieldProps["fieldname"];
                                $fieldOptions = $fieldProps["options"];
                                if (!is_array($data[$fieldProps["parentKey"]])) {
                                    $parentValues = json_decode($data[$fieldProps["parentKey"]], true);
                                } else {
                                    $parentValues = $data[$fieldProps["parentKey"]];
                                }
                                if (!empty($parentValues[$formChildField]) && $parentValues[$formChildField] == true) {
                                    $pdfData[$fieldNamePDFData] =  $fieldOptions["true"];
                                } else {
                                    $pdfData[$fieldNamePDFData] =  $fieldOptions["false"];
                                }
                            }
                        }
                    }
                    if (isset($fieldTypeMappingPDF[$key]["date"])) {
                        foreach ($fieldTypeMappingPDF[$key]["date"] as  $formField => $pdfField) {
                            isset($data[$formField]) ?
                                $pdfData[$pdfField] = date(
                                    "m-d-Y",
                                    strtotime($data[$formField])
                                )
                                : null;
                        }
                    }
                    if (isset($selectedTemplate["customData"])) {
                        if (!is_array($data[$selectedTemplate["customData"]])) {
                            $customTemplateData = json_decode($data[$selectedTemplate["customData"]], true);
                        } else {
                            $customTemplateData = $data[$selectedTemplate["customData"]];
                        }
                        foreach ($customTemplateData as  $field => $value) {
                            $pdfData[$field] = $value;
                        }
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
                            "fullPath" => $docDest,
                            "file" => $dest['relativePath'] . $selectedTemplate["template"],
                            "originalName" => $selectedTemplate["template"],
                            "type" => "file/pdf"
                        )
                    );
                }
            }
        }
        $data["status"] = "PDF_Generated";
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
