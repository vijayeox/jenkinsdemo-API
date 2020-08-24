<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\HttpClientTrait;
use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Auth\AuthContext;


use Oxzion\AppDelegate\HTTPMethod;

class RetryDocumentGeneration extends MailDelegate
{
    use HttpClientTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function setDocumentPath($destination)
    {
        $this->destination = $destination;
    }

    public function execute(array $data, Persistence $persistenceService)
    {

        try {
            if (!isset($data['uuid'])) {
                throw new Exception("Record not found. Please contact Support.", 1);
            }
            if (!isset($data['documents'])) {
                throw new Exception("Record not found. Please contact Support.", 1);
            }
            foreach ($this->checkJSON($data['documents']) as $doc) {
                if ($doc['originalName'] == "excelMapperInput.json") {
                    $excelMapperInputPath = $doc['fullPath'];
                    break;
                }
            }
            if (!isset($excelMapperInputPath)) {
                throw new Exception("Record not found. Please contact Support.", 1);
            }
            if (!file_exists($excelMapperInputPath)) {
                throw new Exception("Record not found. Please contact Support.", 1);
            }
            $excelData = file_get_contents($excelMapperInputPath);
            $excelData = $this->checkJSON($excelData);

            $selectQuery = "Select value FROM applicationConfig WHERE type ='excelMapperURL'";
            $ExcelTemplateMapperServiceURL = ($persistenceService->selectQuery($selectQuery))->current()["value"];
            if (count($excelData) > 0) {
                foreach ($excelData as $excelItem) {
                    $response = $this->makeRequest(
                        HTTPMethod::POST,
                        $ExcelTemplateMapperServiceURL,
                        $excelItem
                    );
                    $this->logger->info("Retry Excel Mapper POST Request for " . $excelItem["fileId"] . "\n" . $response);
                    sleep(1);
                }
            } else {
                throw new Exception("Record not found. Please contact Support.", 1);
            }
            $this->triggerMail($persistenceService, $data, $excelMapperInputPath);
        } catch (\Throwable $e) {
            $errorMessage = "Documents Regneration Failed. Contact Support.";
            if (!empty($e->getMessage())) {
                $errorMessage = $e->getMessage();
            }
            throw new Exception($errorMessage, 1);
        };
        return ["fileId" => $data["uuid"]];
    }

    private function triggerMail(Persistence $persistenceService, $data, $mapperFile)
    {

        $selectQuery = "Select value FROM applicationConfig WHERE type ='eoxSupportMail'";
        $supportEmail = ($persistenceService->selectQuery($selectQuery))->current()["value"];

        $mailOptions = array();
        $mailOptions['to'] = $supportEmail;
        $mailOptions['subject'] = "ArrowHead 3.0 - Document Generation Failure";
        $mailOptions['attachments'] = [$mapperFile];
        $this->logger->info("ATTACHMENTS LIST " . print_r($mailOptions, true));
        $data['orgUuid'] = "34bf01ab-79ca-42df-8284-965d8dbf290e";
        // $data['orgUuid'] = isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID);
        $response = $this->sendMail($data, "eoxSupportMail", $mailOptions);
        $this->logger->info("Mail Response" . $response);
        return $response;
    }

    private function checkJSON($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        return $data;
    }
}
