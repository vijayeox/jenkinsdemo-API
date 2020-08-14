<?php

use Oxzion\AppDelegate\FileTrait;

class ProcessGeneratedFiles
{
    use FileTrait;

    public function execute(array $data)
    {
        try {
            if (isset($data['fileId'])) {
                if ($data['status'] == 1) {
                    if (isset($data['_FILES'])) {
                        $fileData = $this->saveAndUpdateFile($data);
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

    private function saveAndUpdateFile($data)
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
            } else {
                $fileData['documentsToBeGenerated'] = $fileData['documentsToBeGenerated'] - 1;
                $status = $status . " File status is Processing (" . $fileData['documentsToBeGenerated'] . ")";
            }
        } else {
            throw new Exception("No documents to be generated", 1);
        }
        return ["fileData" => $fileData, "status" => $status];
    }
}
