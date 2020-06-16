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
        "AFDealer" => array(
            "type" => "excel",
            "template" => "Workbooktemplate.xlsx",
            "sheets" => array("Dealer")
        ),
        "DGApplication" => array(
            "type" => "excel",
            "template" => "DGApplication.xlsx"
        ),
        "SchinnererAuto" => array(
            "type" => "excel",
            "template" => "SchinnererAuto.xlsx"
        ),
        "SchinnererGarage" => array(
            "type" => "excel",
            "template" => "SchinnererGarage.xlsx"
        ),
        "HarcoDealerPackApplication" => array(
            "type" => "excel",
            "template" => "HarcoDealerPackApplication.xlsx"
        ),
        "HarcoEEList" => array(
            "type" => "excel",
            "template" => "HarcoEEList.xlsx"
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
        $fieldTypeMapping = include(__DIR__ . "/fieldMapping.php");
        $orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : (isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
        $dest = ArtifactUtils::getDocumentFilePath($this->destination, $data['fileId'], array('orgUuid' => $orgUuid));
        $this->logger->info("GenerateWorkbook Dest" . json_encode($dest));
        $generatedDocumentspath = array();
        $this->logger->info("Execute generate document ---------");
        $tempData = $data;
        foreach (json_decode($data['workbooksToBeGenerated'], true) as  $key => $templateSelected) {
            if ($templateSelected) {
                $selectedTemplate = $this->carrierTemplateTypeMapping[$key];
                $docDest = $dest['absolutePath'] .  $selectedTemplate["template"];
                foreach ($fieldTypeMapping as  $fieldkey => $field) {
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
        foreach (json_decode($data, true) as $tempItem) {
            array_push($temp,  $tempItem ? "true" : "false");
        }
        return $temp;
    }
}
