<?php
use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;
class DocumentSaveDelegate extends AbstractDocumentAppDelegate {
    public function __construct() {
        parent::__construct();
    }
    public function execute(array $data, Persistence $persistenceService) {
        $this->logger->info("Document Save Entry");
        if (isset($data['attachmentsFieldnames'])) {
            if (!isset($data['fileId'])) {
                $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
            } else {
                $data['uuid'] = $data['fileId'];
            }
            $attachmentsFieldnames = $data['attachmentsFieldnames'];
            $this->logger->info("attachmentsFieldnames: ".print_r($data['attachmentsFieldnames'],true));
            for ($i = 0;$i < sizeof($attachmentsFieldnames);$i++) {
                $fieldNamesArray =is_string($attachmentsFieldnames[$i]) ? array($attachmentsFieldnames[$i]) : $attachmentsFieldnames[$i];
                $this->logger->info("Document Save Entry fieldNamesArray: ".print_r($fieldNamesArray,true));
                if (sizeof($fieldNamesArray) == 1) {
                    $this->logger->info("Document Save Entry fieldNamesArray size 1");
                    $fieldName = $fieldNamesArray[0];
                    if((is_array($data[$fieldName]))){
                    $data[$fieldName] = $this->saveFile($data, $data[$fieldName]);
                    }
                } else if (sizeof($fieldNamesArray) == 2) {
                    $this->logger->info("Document Save Entry fieldNamesArray size 2");
                    $gridFieldName = $fieldNamesArray[0];
                    $fieldName = $fieldNamesArray[1];
                    for ($j = 0;$j < sizeof($data[$gridFieldName]);$j++) {
                        if (isset($data[$gridFieldName][$j][$fieldName])) {
                            $data[$gridFieldName][$j][$fieldName] = $this->saveFile($data, $data[$gridFieldName][$j][$fieldName]);
                        }
                    }
                }
            }
        }
        $this->logger->info("Document Save return data ".print_r($data,true));
        return $data;
    }

    public function saveFile(array $data, $documentsArray) {
        $this->logger->info("saveFile start: ".print_r($documentsArray,true));
        if (!isset($data['orgId'])) {
            $data['orgId'] = $this->getOrgId();
        }
        $filepath = $data['orgId'] . '/' . $data['uuid'] . '/';
        if (!is_dir($this->destination . $filepath)) {
            mkdir($this->destination . $filepath, 0777, true);
        }
        for ($i = 0;$i < sizeof($documentsArray);$i++) {
            if(isset($documentsArray[$i]['url'])){
                $base64Data = explode(',', $documentsArray[$i]['url']);
                $content = base64_decode($base64Data[1]);
                $file = fopen($this->destination . $filepath . $documentsArray[$i]['name'], 'wb');
                fwrite($file, $content);
                fclose($file);
                unset($documentsArray[$i]['url']);
                $documentsArray[$i]['file'] = $filepath . $documentsArray[$i]['name'];
            }
        }
        $this->logger->info("saveFile return: ".print_r($documentsArray,true));
        return $documentsArray;
    }
}
