<?php
//test
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\AppDelegate\FileTrait;
use Oxzion\AppDelegate\AbstractDocumentAppDelegate;

class ExportDriversData extends AbstractDocumentAppDelegate
{
    use FileTrait;

    public function __construct()
    {
        parent::__construct();
    }

    protected $excelTemplate = 'DriversExport.xlsx';

    public function execute(array $data, Persistence $persistenceService)
    {
        $params = array(
            "entityName" => "Drivers Export",
            "orgId" => "5060e4d5-006a-4054-85c0-bbf78579412d"
        );
        $filterParams = array(
            ["skip" => 0, "take" => 10000]
        );
        $files = $this->getParentFileId($data); 
        //print_r($this->excelDataMassage($files["data"])); exit;
        $sumTotal = 0;
        if ($files["total"] > 0) {
            $fileData['exportDate'] = date('Y-m-d');
            $fileData['recordsCount'] = $files["total"];
            $fileData['entity_name'] = "Drivers Export";
            $this->saveFile($fileData);

            $generatedDocumentspath = [];
            $dest =  ArtifactUtils::getDocumentFilePath(
                $this->destination,
                $fileData["uuid"],
                array('orgUuid' => $params["orgId"])
            );
            $this->documentBuilder->fillExcelTemplate(
                $this->excelTemplate,
                $this->excelDataMassage($files["data"]),
                //$files["data"],
                $dest['absolutePath'] .  $this->excelTemplate,
                ["Sheet1"]
            );
            array_push(
                $generatedDocumentspath,
                array(
                    "fullPath" => $dest['absolutePath'] .  $this->excelTemplate,
                    "file" => $dest['relativePath'] . $this->excelTemplate,
                    "originalName" => $this->excelTemplate,
                    "type" => "excel/xlsx"
                )
            );
            $fileData["driversExport"] = json_encode($generatedDocumentspath);
            $fileData["driversExportDocPath"] = $dest['relativePath'] .  $this->excelTemplate;
            $this->saveFile($fileData, $fileData["uuid"]);
        } else {
            throw new Exception("No Records found");
        }
        return $fileData;
    }
    
    private function excelDataMassage($data)
    {
        $result = array();
        foreach ($data as $j => $item) {
            foreach ($item as $i => $response) {
                if (
                    $i == "ackName" ||
                    $i == "ackDate" 
                ) {
                    $result[$i][$j] = $item[$i];
                }
            }
        }
        return $result;
    }

    public function getParentFileId($data) {
        $filterParams = array();
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'ICUserId','operator'=>'neq','value'=> '');
        $data['filterParams'] = $filterParams;

        return $files = $this->getFileList($data, $filterParams);
        //$assocId = $files['data'][0]['uuid'];
    } 
}
