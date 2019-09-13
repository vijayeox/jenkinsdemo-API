<?php
namespace App\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Utils\FileUtils;

class ImportService extends AbstractService
{

    protected $config;
    protected $workflowService;
    protected $fieldService;
    protected $formService;
    protected $param;

    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter)
    {
        parent::__construct($config, $dbAdapter);
    }

    public function generateCSVData($storedProcedureName, $orgId, $appId, $appName)
    {
        $filePath = dirname(__dir__) . "/../../../data/import/" . $orgId . "/" . $appId . "/" . $appName . "/data/";
        $archivePath = dirname(__dir__) . "/../../../data/import/" . $orgId . "/" . $appId . "/" . $appName . "/archive/"; //The path to the folder Ex: /clients/hub/data/migrations/app/hub/archive/

        $dataSet = array_diff(scandir($filePath), array(".", ".."));
        $filePath = $filePath . $dataSet[2];
        if (!file_exists($filePath)) {
            return 2;
        }

        $f_pointer = fopen($filePath, "r");
        while (!feof($f_pointer)) {
            $ar = fgetcsv($f_pointer);
            if (!empty($ar)) {
                $listStr = implode(",", $ar);
                $data = $this->importCSVData($storedProcedureName, $ar);
                $importData[] = $data;
            }
        }
        if (is_dir($archivePath)) {
            FileUtils::copy($filePath, $dataSet[2], $archivePath);
        }else{
            return 3;
        }
        return 1;
    }

    public function importCSVData($storedProcedureName, $data)
    {
        $this->param = "";
        foreach ($data as $val) {
            $this->param .= "'" . trim($val) . "', ";
        }
        $this->param = rtrim($this->param, ", ");
        $queryString = "call " . $storedProcedureName . "(" . $this->param . ")";
        return $this->runGenericQuery($queryString);
    }

}
