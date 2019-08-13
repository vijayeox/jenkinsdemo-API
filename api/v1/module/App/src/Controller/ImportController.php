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
        $storedProcedureName = $_POST['stored_procedure_name'];
        $orgId = $_POST['org_id'];
        $appId = $_POST['app_id'];
        $appName = $_POST['app_name'];

        try
        {
            $returnData = $this->importService->generateCSVData($storedProcedureName, $orgId, $appId, $appName);
            $filePath = Array(dirname(__dir__) . "/../../../data/import/" . $orgId . "/" . $appId . "/" . $appName . "/data/");
        
            if ($returnData == 2) {
                return $this->getFailureResponse("Cannot find the file", $filePath);
            }
            if ($returnData == 3) {
                return $this->getFailureResponse("File could not be moved to archive", $filePath);
            }

        } catch (Exception $e) {
            throw $e;
            return $this->getFailureResponse("Import Aborted, please make sure your file is in the correct format", $filePath);
        }
        return $this->getSuccessResponseWithData(array("Import Successfull!"));
    }

}
