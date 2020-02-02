<?php
use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;

class DocumentFetchDelegate extends AbstractDocumentAppDelegate
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("DocumentFetchDelegate".print_r($data,true));
        if (isset($data['attachmentsFieldnames'])) {
            $attachmentsFieldnames = $data['attachmentsFieldnames'];
            for ($i = 0;$i < sizeof($attachmentsFieldnames);$i++) {
                $fieldNamesArray = is_string($attachmentsFieldnames[$i]) ? array($attachmentsFieldnames[$i]) : $attachmentsFieldnames[$i];
                if (sizeof($fieldNamesArray) == 1) {
                    $fieldName = $fieldNamesArray[0];
                    $data[$fieldName] = $this->getFileData($data[$fieldName]);
                } else if (sizeof($fieldNamesArray) == 2) {
                    $gridFieldName = $fieldNamesArray[0];
                    $fieldName = $fieldNamesArray[1];
                    for ($i = 0;$i < sizeof($data[$gridFieldName]);$i++) {
                        if (isset($data[$gridFieldName][$i][$fieldName])) {
                            $data[$gridFieldName][$i][$fieldName] = $this->getFileData($data[$gridFieldName][$i][$fieldName]);
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function getFileData(array $documentsArray) {
        for ($i = 0;$i < sizeof($documentsArray);$i++) {
            if(isset($documentsArray[$i]['file'])){
                $file = $this->destination.$documentsArray[$i]['file'];
                $fileData = file_get_contents($file);
                if($fileData){
                    $documentsArray[$i]['url']='data:'.$documentsArray[$i]['type'].';base64,'.base64_encode($fileData);
                    unset($documentsArray[$i]['file']);
                }
            }
        }
        return $documentsArray;
    }
}
