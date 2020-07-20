<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Utils\YMLUtils;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class GenerateWorkbook extends AbstractDocumentAppDelegate
{
    protected $carrierTemplateList = array(
        "harco" => array(
            "type" => "excel",
            "template" => "harco.yaml"
        ),
        "dealerGuard_ApplicationOpenLot" => array(
            "type" => "excel",
            "template" => "dealerGuard_ApplicationOpenLot.yaml"
        ),
        "victor_FranchisedAutoDealer" => array(
            "type" => "excel",
            "template" => "victor_FranchisedAutoDealer.yaml"
        ),
        "victor_AutoPhysDamage" => array(
            "type" => "excel",
            "template" => "victor_AutoPhysDamage.yaml"
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
        $fieldTypeMappingPDF = include(__DIR__ . "/fieldMappingPDF.php");
        $fileUUID = isset($data['fileId']) ? $data['fileId'] : $data['uuid'];
        $orgUuid = isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID);
        $fileDestination =  ArtifactUtils::getDocumentFilePath($this->destination, $fileUUID, array('orgUuid' => $orgUuid));
        $this->logger->info("GenerateWorkbook Dest" . json_encode($fileDestination));
        $generatedDocumentsList = array();
        $excelData = array();

        foreach ($this->checkJSON($data['workbooksToBeGenerated']) as  $key => $templateSelected) {
            if ($templateSelected) {
                $selectedTemplate = $this->carrierTemplateList[$key];
                $documentDestination = $fileDestination['absolutePath'] .  $selectedTemplate["template"];
                if ($selectedTemplate["type"] == "excel") {
                    $templateData = array();
                    $fieldMappingExcel = file_get_contents(__DIR__ . "/../template/" . $selectedTemplate["template"]);
                    $fieldMappingExcel = YMLUtils::ymlToArray($fieldMappingExcel);

                    foreach ($fieldMappingExcel as $fieldConfig) {
                        $formFieldKey = str_contains($fieldConfig["key"], "_") ?
                            explode("_", $fieldConfig["key"])[0]
                            : $fieldConfig["key"];
                        if (isset($data[$formFieldKey]) && !empty($data[$formFieldKey]) && $data[$formFieldKey] !== "[]" ) {
                            $userInputValue = $data[$formFieldKey];
                            $tempFieldConfig = $fieldConfig;
                            if (isset($fieldConfig["method"])) {
                                $processMethod = $fieldConfig["method"];
                                $tempFieldConfig['value'] = $this->$processMethod($userInputValue, $fieldConfig, $data);
                                unset($tempFieldConfig['method']);
                            } else {
                                $tempFieldConfig['value'] = $userInputValue;
                            }
                            array_push($templateData, $tempFieldConfig);
                        }
                    }

                    array_push(
                        $excelData,
                        array(
                            "template" => $selectedTemplate["template"],
                            "mapping" => $templateData
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
                                $parentValues = $this->checkJSON($data[$fieldProps["parentKey"]]);
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
                        $customTemplateData = $this->checkJSON($data[$selectedTemplate["customData"]]);
                        foreach ($customTemplateData as  $field => $value) {
                            $pdfData[$field] = $value;
                        }
                    }
                    $this->logger->info("PDF Filling Data \n" . json_encode($pdfData, JSON_PRETTY_PRINT));
                    $this->documentBuilder->fillPDFForm(
                        $selectedTemplate["template"],
                        $pdfData,
                        $documentDestination
                    );
                    array_push(
                        $generatedDocumentsList,
                        array(
                            "fullPath" => $documentDestination,
                            "file" => $fileDestination['relativePath'] . $selectedTemplate["template"],
                            "originalName" => $selectedTemplate["template"],
                            "type" => "file/pdf"
                        )
                    );
                }
            }
        }
        // print_r($excelData);
        // exit();
        if(count($excelData) > 0) {
            $data ["excelData"] = json_encode($excelData);
        }
        $data["status"] = "Queued";
        $data["documents"] = json_encode($generatedDocumentsList);
        $this->logger->info("Completed GenerateWorkbook with data- " . json_encode($data, JSON_PRETTY_PRINT));
        return $data;
    }

    private function checkJSON($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        return $data;
    }

    private function pulloutChild($data, $fieldConfig, $formData)
    {
        $childKey = explode("_", $fieldConfig["key"])[1];
        if (isset($data[$childKey]) && !empty($data[$childKey])) {
            $value = $data[$childKey];
            $valueType = gettype($data[$childKey]);
            if ($valueType == "boolean") {
                $value = $value ? "true" : "false";
            } else {
                if ($value == 'true') {
                    $value = 'true';
                } else if ($value == 'false') {
                    $value = 'false';
                }
            }
            return $value;
        } else {
            return " ";
        }
    }

    private function checkbox_X($data, $fieldConfig, $formData)
    {
        if (str_contains($fieldConfig["key"], "_")) {
            $childKey = explode("_", $fieldConfig["key"])[1];
            if (isset($data[$childKey]) && !empty($data[$childKey])) {
                $value = $data[$childKey];
            } else {
                return " ";
            }
        } else {
            $formKey = $fieldConfig["key"];
            $value = $formData[$formKey];
        }

        $valueType = gettype($value);
        if ($valueType == "boolean") {
            $value = $value ? "X" : " ";
        } else {
            if ($value == 'true') {
                $value = 'X';
            } else if ($value == 'false') {
                $value = " ";
            }
        }
        return $value;
    }
}
