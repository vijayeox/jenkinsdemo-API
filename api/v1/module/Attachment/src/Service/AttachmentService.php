<?php
namespace Attachment\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Service\FileService;
use Oxzion\ValidationException;
use Exception;

class AttachmentService extends AbstractService{
    private $fileService;
    public function __construct($config, $dbAdapter){
        parent::__construct($config, $dbAdapter);
    }

    /**
     * createUpload
     *
     * Upload files from Front End and store it in temp Folder
     *
     *  @param files Array of files to upload
     *  @return JSON array of filenames
     */

    public function createUpload($files){
        $fileArray = array();
        $i = 0;
        foreach ($files as  $file) {
            if(isset($file['name'])){
                $file['name'] = uniqid()."-".$file['name'];
                FileService::storeFile($file,$this->config['DATA_FOLDER']."temp/");
                $fileArray[$i]['name'] = $file['name'];
                $i++;
            }
        }
        return $fileArray;
    }
}
?>