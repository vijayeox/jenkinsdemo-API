<?php

use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;
require_once __DIR__."/RegenerationDelegate.php";


class RegenerateCertificate extends RegenerationDelegate
{
    use FileTrait;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService) 
    {
     
        $files = $data['fileUuid'];
        for($i = 0;$i < sizeof($files);$i ++){
            $result = $this->getFile($files[$i],false,$data['orgId']);
            $fileData = $result['data'];
            $fileData["regeneratePolicy"] = 'true';
            $fileData['fileId'] = $files[$i];
            $this->saveFile($fileData, $fileData['fileId']);
            foreach ($fileData as $key => $value) {
                if(is_array($fileData[$key])){
                    $fileData[$key] = json_encode($value);
                }
            }
            $data = parent::execute($fileData,$persistenceService);
            $fileData["regeneratePolicy"] = '';
            $this->saveFile($fileData, $fileData['fileId']);

        }
    }
}