<?php
use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Utils\UuidUtil;
use Oxzion\AppDelegate\UserContextTrait;

class DocumentFetchDelegate extends AbstractDocumentAppDelegate
{
    use UserContextTrait;
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
    $this->logger->info("DocumentFetchDelegate".print_r($data,true));
    $privileges = $this->getPrivilege();
    if($data['product'] == 'Individual Professional Liability' || $data['product'] == 'Emergency First Response'){
        if(!isset($data['endorsement_options'])){
            if(isset($data['initiatedByCsr']) && ($data['initiatedByCsr'] == false && (isset($privileges['MANAGE_POLICY_APPROVAL_WRITE']) && 
                $privileges['MANAGE_POLICY_APPROVAL_WRITE'] == true))){
                $this->logger->info("INITIATED BY CSR".json_encode($privileges));

              if (isset($data['csrAttachmentsFieldnames'])) {
                $attachmentsFieldnames = $data['csrAttachmentsFieldnames'];
                $this->getAttachmentsData($data,$attachmentsFieldnames); 
              }  
            }
            else{ 
                $this->logger->info("NOT INITIATED BY CSR");
                if (isset($data['attachmentsFieldnames'])) {
                    $attachmentsFieldnames = $data['attachmentsFieldnames'];
                    $this->getAttachmentsData($data,$attachmentsFieldnames);
                }
            }
        }else{
            if (isset($data['csrAttachmentsFieldnames'])) {
                $attachmentsFieldnames = $data['csrAttachmentsFieldnames'];
                $this->getAttachmentsData($data,$attachmentsFieldnames);
            }
            if (isset($data['attachmentsFieldnames'])) {
                    $attachmentsFieldnames = $data['attachmentsFieldnames'];
                    $this->getAttachmentsData($data,$attachmentsFieldnames);
            }
        }
     } else{
        if (isset($data['attachmentsFieldnames'])) {
            $attachmentsFieldnames = $data['attachmentsFieldnames'];
            if(is_array($attachmentsFieldnames)){
                $this->getAttachmentsData($data,$attachmentsFieldnames);
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
                    $documentsArray[$i]['file_url'] = $documentsArray[$i]['file'];
                    unset($documentsArray[$i]['file']);
                }
            }
        }
        return $documentsArray;
    }

    private function getAttachmentsData(array &$data, array $attachmentsFieldnames){

        if(!is_array($attachmentsFieldnames)){
            $attachmentsFieldnames = json_decode($attachmentsFieldnames,true);
        }

        for ($i = 0;$i < sizeof($attachmentsFieldnames);$i++) {
                $fieldNamesArray = is_string($attachmentsFieldnames[$i]) ? array($attachmentsFieldnames[$i]) : $attachmentsFieldnames[$i];
                if (sizeof($fieldNamesArray) == 1) {
                   $fieldName = $fieldNamesArray[0];
                   if(isset($data[$fieldName])){
                        $data[$fieldName] = is_string($data[$fieldName]) ? json_decode($data[$fieldName],true) :$data[$fieldName];
                        if(empty($data[$fieldName])){ 
                            $data[$fieldName] = array();
                        }else {
                            $data[$fieldName] = $this->getFileData($data[$fieldName]);
                        }
                   }
                } else if (sizeof($fieldNamesArray) == 2) {
                    $gridFieldName = $fieldNamesArray[0];
                    $fieldName = $fieldNamesArray[1];
                    if(is_array($data[$gridFieldName])){
                    	for ($j = 0;$j < sizeof($data[$gridFieldName]);$j++) {
                        	if (isset($data[$gridFieldName][$j][$fieldName])) {
                            	$data[$gridFieldName][$j][$fieldName] = $this->getFileData($data[$gridFieldName][$j][$fieldName]);
                        	}
                        }
                    }
                }
            }
    }
}
