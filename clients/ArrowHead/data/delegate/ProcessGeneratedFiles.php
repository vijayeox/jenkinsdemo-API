<?php

use Oxzion\AppDelegate\FileTrait;

class ProcessGeneratedFiles
{
    use FileTrait;

    public function execute(array $data)
    {
        try {
            if (isset($data['file_id'])) {
                if ($data['status'] == 1) {
                    if (isset($data['errorlist']) && count($data['errorlist'] > 0)) {
                        $fileData = $this->getFile($data['file_id'],  true, $data['orgId'])['data'];
                        if (isset($fileData['filesToBeGenerated'])) {
                            if ($fileData['filesToBeGenerated'] == 1) {
                                $fileData['filesToBeGenerated'] = 0;
                                $fileData['status'] = 'Generated';
                            } else {
                                $fileData['filesToBeGenerated'] = $fileData['filesToBeGenerated'] - 1;
                            }
                        }
                        $fileData['errorList'] = $data['errorList'];
                        // Do error log stuff
                        $this->saveFile($fileData, $data['file_id']);
                    } else {
                        $fileData = $this->getFile($data['file_id'],  true, $data['orgId'])['data'];
                        if (isset($fileData['documents'])) {
                            if (is_string($fileData['documents'])) {
                                $fileData['documents'] = json_decode($fileData['documents'], true);
                            }
                            array_push($fileData['documents'], $data['file']);
                        } else {
                            $fileData['documents'] = [$data['file']];
                        }
                        if (isset($fileData['filesToBeGenerated'])) {
                            if ($fileData['filesToBeGenerated'] == 1) {
                                $fileData['filesToBeGenerated'] = 0;
                                $fileData['status'] = 'Generated';
                            } else {
                                $fileData['filesToBeGenerated'] = $fileData['filesToBeGenerated'] - 1;
                            }
                        }
                        $this->saveFile($fileData, $data['file_id']);
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
}
