<?php

use Oxzion\AppDelegate\FileTrait;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Auth\AuthContext;


use Oxzion\AppDelegate\MailDelegate;

class ProcessGeneratedFiles extends MailDelegate
{
    use FileTrait;

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
            if (isset($data['fileId'])) {
                if ($data['status'] == 1) {
                    if (isset($data['_FILES'])) {
                        $fileData = $this->saveAndUpdateFile($data, $persistenceService);
                        $this->saveFile($fileData["fileData"], $data['fileId']);
                        $data['status'] = empty($fileData['status']) ? $data['status'] : $fileData['status'];
                    } else {
                        $data['status'] = 'No file/document provided';
                        // ErrorLog No file attached for status 1
                    }
                    if (isset($data['_errorlist']) && count($data['_errorlist'] > 0)) {
                        // $fileData['_errorList'] = $data['_errorList'];
                        // Do error log stuff
                    }
                } else {
                    // Do error log stuff

                }
            } else {
                $data['status'] = 'No fileId provided';
            }
        } catch (\Throwable $e) {
            $errorMessage = "Error Processing Request. In Catch";
            if (!empty($e->getMessage())) {
                $errorMessage = $e->getMessage();
            }
            throw new Exception($errorMessage, 1);
        }
        unset($data['orgId']);
        unset($data['errorlist']);
        return $data;
    }

    private function saveAndUpdateFile($data, $persistenceService)
    {
        $status = "";

        $data['fieldLabel'] = "documents";
        $attachment = $this->addAttachment($data, $data['_FILES']);
        if (!isset($attachment['created_id'])) {
            $status = "Failed to add attachment to file.";
        } else {
            $status = "Attachment Added to file.";
        }

        $fileData = $this->getFile($data['fileId'],  true, $data['orgId'])['data'];
        // if ($fileData == 0) {
        //     throw new Exception("Cannot update unknown file. Please check the fileId", 1);
        // }
        if (isset($fileData['documentsToBeGenerated'])) {
            if ($fileData['documentsToBeGenerated'] == 1) {
                $fileData['documentsToBeGenerated'] = 0;
                $fileData['status'] = 'Generated';
                $status = $status . "File status is Generated";
                $policyMail = $this->triggerMail($persistenceService, $fileData);
                $fileData['mailStatus'] = $policyMail;
            } else {
                $fileData['documentsToBeGenerated'] = $fileData['documentsToBeGenerated'] - 1;
                $status = $status . " File status is Processing (" . $fileData['documentsToBeGenerated'] . ")";
            }
        } else {
            throw new Exception("No documents to be generated", 1);
        }
        return ["fileData" => $fileData, "status" => $status];
    }

    private function triggerMail(Persistence $persistenceService, $data)
    {

        $selectQuery = "Select value FROM applicationConfig WHERE type ='arrowHeadInboxMail'";
        $submissionEmail = ($persistenceService->selectQuery($selectQuery))->current()["value"];

        $emailAttachments = [];
        foreach ($this->checkJSON($data['documents']) as $doc) {
            if ($doc['originalName'] !== "excelMapperInput.json") {
                if(isset($doc["fullPath"])){
                    array_push($emailAttachments, $doc['fullPath'] );
                } else {
                    array_push($emailAttachments, $doc['path'] );
                }
            }
        }

        $mailOptions = array();
        $mailOptions['to'] = $submissionEmail;
        $mailOptions['subject'] = "New business – " . $data['namedInsured'] . " - " . $this->formatDate($data['effectiveDate']) . " - " . $data['producername'];
        $mailOptions['attachments'] = $emailAttachments;
        $this->logger->info("Arrowhead Policy Mail " . print_r($mailOptions, true));
        $data['orgUuid'] = "34bf01ab-79ca-42df-8284-965d8dbf290e";
        // $data['orgUuid'] = isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID);
        $response = $this->sendMail($data, "finalSubmissionMail", $mailOptions);
        $this->logger->info("Mail has " . $response ? "been sent." : "not been sent.");
        return $response;
    }

    private function formatDate($data)
    {
        $date = strpos($data, "T") ? explode("T", $data)[0] : $data;
        return date(
            "m-d-Y",
            strtotime($date)
        );
    }

    private function checkJSON($data)
    {
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        return $data;
    }
}
