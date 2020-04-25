<?php
use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;
use Oxzion\AppDelegate\UserContextTrait;

class DocumentSaveDelegate extends AbstractDocumentAppDelegate {
    use UserContextTrait;
    public function __construct() {
        parent::__construct();
    }
    public function execute(array $data, Persistence $persistenceService) {
        $this->logger->info("Document Save Entry");

        $privileges = $this->getPrivilege();
        if($data['product'] == 'Individual Professional Liability' || $data['product'] == 'Emergency First Response'){
           if(!isset($data['endorsement_options'])){
            if ((isset($privileges['MANAGE_POLICY_APPROVAL_WRITE']) && 
                $privileges['MANAGE_POLICY_APPROVAL_WRITE'] == true) && (isset($data['initiatedByCsr']) && ($data['initiatedByCsr'] == false))) {
                if((isset($data['csrAttachmentsFieldnames']))){
                    if(!isset($data['fileId'])) {
                        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
                    } else {
                        $data['uuid'] = $data['fileId'];
                    }
                    $attachmentsFieldnames = $data['csrAttachmentsFieldnames'];
                    $this->logger->info("csrAttachmentsFieldnames Data: ".print_r($data['csrAttachmentsFieldnames'],true));
                    $this->getAttchments($data,$attachmentsFieldnames);
                }
            }else{
                if (isset($data['attachmentsFieldnames'])) {
                    if (!isset($data['fileId'])) {
                        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
                    } else {
                        $data['uuid'] = $data['fileId'];
                    }
                    $attachmentsFieldnames = $data['attachmentsFieldnames'];
                    $this->logger->info("attachmentsFieldnames: ".print_r($data['attachmentsFieldnames'],true));
                    $this->getAttchments($data,$attachmentsFieldnames);
                }
            }
          }else{
                if (isset($data['endorAttachmentsFieldnames'])) {
                    if (!isset($data['fileId'])) {
                        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
                    } else {
                        $data['uuid'] = $data['fileId'];
                    }
                    $attachmentsFieldnames = $data['endorAttachmentsFieldnames'];
                    $this->logger->info("endorAttachmentsFieldnames Data: ".print_r($data['endorAttachmentsFieldnames'],true));
                    $this->getAttchments($data,$attachmentsFieldnames);
                }

                   if((isset($data['csrAttachmentsFieldnames']))){
                    if(!isset($data['fileId'])) {
                        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
                    } else {
                        $data['uuid'] = $data['fileId'];
                    }
                    $attachmentsFieldnames = $data['csrAttachmentsFieldnames'];
                    $this->logger->info("csrAttachmentsFieldnames Data: ".print_r($data['csrAttachmentsFieldnames'],true));
                    $this->getAttchments($data,$attachmentsFieldnames);
                }

         } 
        }else{
            if (isset($data['attachmentsFieldnames'])) {
                $data['attachmentsFieldnames'] = is_string($data['attachmentsFieldnames']) ? json_decode($data['attachmentsFieldnames'],true) : $data['attachmentsFieldnames'];
                if (!isset($data['fileId'])) {
                    $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
                } else {
                    $data['uuid'] = $data['fileId'];
                }
                $attachmentsFieldnames = $data['attachmentsFieldnames'];
                $this->logger->info("attachmentsFieldnames: ".print_r($data['attachmentsFieldnames'],true));
                $this->getAttchments($data,$attachmentsFieldnames);
            }
        }
        if(isset($data['documentFieldnames'])){
            $this->cleanDocumentFields($data, $data['documentFieldnames']);
        }
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
        if(is_array($documentsArray)){
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
        }
        $this->logger->info("saveFile return: ".print_r($documentsArray,true));
        return $documentsArray;
    }
    
    private function cleanDocumentFields(array &$data,array $documentFieldnames) {
        $this->logger->info("Document Field NAmes: ".print_r($documentFieldnames,true));
        for ($i = 0;$i < sizeof($documentFieldnames);$i++) {
            $fieldNamesArray =is_string($documentFieldnames[$i]) ? array($documentFieldnames[$i]) : $documentFieldnames[$i];
            if(is_string($documentFieldnames[$i])){
                unset($data[$documentFieldnames[$i]]);
            }
            if(is_array($documentFieldnames[$i])){
                $gridFieldName = $documentFieldnames[$i][0];
                if(sizeof($documentFieldnames[$i]) > 1){
                    for ($j=1; $j < sizeof($documentFieldnames[$i]); $j++) {
                        if(isset($documentFieldnames[$i][$j]) ){
                            if(is_array($data[$gridFieldName])){
                                foreach ($data[$gridFieldName] as $k => $v) {
                                    if(isset($data[$gridFieldName][$k][$documentFieldnames[$i][$j]])){
                                        unset($data[$gridFieldName][$k][$documentFieldnames[$i][$j]]);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    unset($data[$documentFieldnames[$i][0]]);
                }
            }
        }
    }
    private function getAttchments(array &$data,array $attachmentsFieldnames){
        if(is_array($attachmentsFieldnames)){
            for ($i = 0;$i < sizeof($attachmentsFieldnames);$i++) {
            $fieldNamesArray =is_string($attachmentsFieldnames[$i]) ? array($attachmentsFieldnames[$i]) : $attachmentsFieldnames[$i];
            $this->logger->info("Document Save Entry fieldNamesArray: ".print_r($fieldNamesArray,true));
            if (sizeof($fieldNamesArray) == 1) {
                $this->logger->info("Document Save Entry fieldNamesArray size 1");
                $fieldName = $fieldNamesArray[0];
                if(isset($data[$fieldName]) && is_array($data[$fieldName]) ){
                    $data[$fieldName] = $this->saveFile($data, $data[$fieldName]);
                }
            } else if (sizeof($fieldNamesArray) == 2) {
                $this->logger->info("Document Save Entry fieldNamesArray size 2");
                $gridFieldName = $fieldNamesArray[0];
                $fieldName = $fieldNamesArray[1];
                if(isset($data[$gridFieldName]) && !empty($data[$gridFieldName])){
                    $data[$gridFieldName] = is_string($data[$gridFieldName]) ? json_decode($data[$gridFieldName],true) : $data[$gridFieldName];
                    for ($j = 0;$j < sizeof($data[$gridFieldName]);$j++) {
                        if (isset($data[$gridFieldName][$j][$fieldName])  && is_array($data[$gridFieldName][$j][$fieldName]) ) {
                            $data[$gridFieldName][$j][$fieldName] = $this->saveFile($data, $data[$gridFieldName][$j][$fieldName]);
                        }
                    }

                } 
                }
            }
        }
    }
}
