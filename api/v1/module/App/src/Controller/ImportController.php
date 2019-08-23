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

    /*
     * POST Import the CSV fuction
     * @api
     * @link /app/1/importcsv
     * @method POST
     * @return Status mesassge based on success and failure
     * <code>status : "success|error",
     *       data :  {
     * String stored_procedure_name
     * int: org_id
     * string: app_id
     * string: app_name
     * }
     * </code>
     */

    public function importCSVAction()
    {
        $params = $this->extractPostData();
        $storedProcedureName = $params['stored_procedure_name'];
        $orgId = $params['org_id'];
        $appId = $params['app_id'];
        $appName = $params['app_name'];
        $srcURL = "";

        try
        {
            $uploadData = $this->importService->uploadCSVData($storedProcedureName, $orgId, $appId, $appName, $srcURL);
            $returnData = $this->importService->generateCSVData($storedProcedureName, $orgId, $appId, $appName);
            $filePath = array(dirname(__dir__) . "/../../../data/import/" . $orgId . "/" . $appId . "/" . $appName . "/data/");

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
