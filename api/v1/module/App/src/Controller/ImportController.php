<?php

namespace App\Controller;

use App\Service\ImportService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Log\Logger;

class ImportController extends AbstractApiController
{
    /**
     * @var ImportService Instance of ImportService Service
     */
    private $importService;

    /**
     * @ignore __construct
     */
    public function __construct(ImportService $importService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct(null, $log, __CLASS__, null);
        $this->setIdentifierName('appId');
        $this->importService = $importService;
    }

    public function importCSVAction()
    {
        // dirname(__FILE__);exit;

        // $dir =  realpath('./');
        // echo $dir;exit;

        $filePath = $_POST['file_path'];   
        $storedProcedureName = $_POST['stored_procedure_name'];
        $archivePath = dirname(__dir__) . "/../../../" . $_POST['archive_path'] . Date("Ymd"); //The path to the folder Ex: /clients/hub/data/migrations/app/hub/archive/
        try
        {
            $dataSet = array_diff(scandir($filePath), array(".", ".."));
            // print_r($dataSet);exit;
            $filePath = $filePath . $dataSet[2];
            if (!file_exists($filePath)) {
                return $this->getFailureResponse("Cannot find the file", $dataSet);
            }

            $f_pointer = fopen($filePath, "r");
            while (!feof($f_pointer)) {
                $ar = fgetcsv($f_pointer);
                if (!empty($ar)) {
                    $listStr = implode(",", $ar);
                    $data = $this->importService->importCSVData($storedProcedureName, $ar);
                    $importData[] = $data;
                }
            }

            if (!is_dir($archivePath)) {
                $destinationFile = $archivePath . $dataSet[2] . "_" . Date("Ymd h:i:s");
                mkdir($archivePath);
                copy($filePath, $destinationFile);
            }

        } catch (Exception $e) {
            throw $e;
            return $this->getFailureResponse("Import Aborted, please make sure your file is in the correct format", $dataSet);
        }
        return $this->getSuccessResponseWithData(array("CHecl"));
    }

}
