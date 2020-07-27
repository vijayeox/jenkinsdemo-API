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
                        $this->saveFile($fileData, $data['fileId']);
                    } else {
                        // ErrorLog No file attached for status 1
                    }
                    if (isset($data['_errorlist']) && count($data['_errorlist'] > 0)) {
                        // $fileData['_errorList'] = $data['_errorList'];
                        // Do error log stuff
                    }
                } else if ($data['status'] == 0) {
                    // Do error log stuff

                }
            }
        } catch (\Throwable $e) {
            throw new Exception("Error Processing Request", 1);
        }
        return $data;
    }

    private function saveAndUpdateFile($data)
    {
        $data['fieldLabel'] = "documents";
        $this->addAttachment($data, $data['_FILES']);
        $fileData = $this->getFile($data['fileId'],  true, $data['orgId'])['data'];
        if (isset($fileData['filesToBeGenerated'])) {
            if ($fileData['filesToBeGenerated'] == 1) {
                $fileData['filesToBeGenerated'] = 0;
                $fileData['status'] = 'Generated';
            } else {
                $fileData['filesToBeGenerated'] = $fileData['filesToBeGenerated'] - 1;
            }
        } else {
            throw new Exception("Error Processing Request", 1);
        }
        return $fileData;
    }
}