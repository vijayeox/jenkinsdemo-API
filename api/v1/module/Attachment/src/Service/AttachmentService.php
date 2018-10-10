<?php
namespace Attachment\Service;

use Oxzion\Service\AbstractService;
use Oxzion\Service\FileService;
use Oxzion\ValidationException;
use Attachment\Model\AttachmentTable;
use Attachment\Model\Attachment;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Exception;

class AttachmentService extends AbstractService{
    private $table;
    public function __construct($config, $dbAdapter, AttachmentTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    /**
     * createUpload
     *
     * Upload files from Front End and store it in temp Folder
     *
     *  @param files Array of files to upload
     *  @return JSON array of filenames
     */

    public function upload($data,$files){
        $fileArray = array();
        $i = 0;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        if(isset($data['type'])){
            $count = 0;
            $fileArray = array();
            if(isset($files)){
                foreach ($files as  $file) {
                    $fileArray[] = $this->constructAttachment($data,$file);
                }
            } else {
                if(isset($data['files'])){
                    foreach ($data['files'] as $key => $value) {
                        $fileArray[] = $this->constructAttachment($data,$value);
                    }
                }
            }
        }
        return $fileArray;
    }
    protected function constructAttachment($data,$file){
        if(isset($file['name'])){
            $uniqueId = uniqid();
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $data['file_name'] = str_replace(".".$ext, "", $file['name']);
            $data['extension'] = $ext;
            $file['name'] = $data['file_name'];
            $data['uuid'] = $uniqueId;
        } else {
            $data['uuid'] = $file['uuid'];
            $data['file_name'] = $file['file_name'];
            $data['extension'] = $file['extension'];
        }
        $folderPath = $this->constructPath($data['type']);
        $form = new Attachment();
        $data['created_date'] = isset($data['start_date'])?$data['start_date']:date('Y-m-d H:i:s');
        $data['path'] = $folderPath.$data['file_name'].".".$data['extension'];
        $form->exchangeArray($data);
        $form->validate();
        $count = $this->table->save($form);
        $id = $this->table->getLastInsertValue();
        FileService::storeFile($file,$folderPath);
        return $data['uuid'];
    }
    public function getAttachment($id){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_attachment')
        ->columns(array("path"))
        ->where(array('uuid' => $id));
        $result = $this->executeQuery($select)->toArray();
        return $result;
    }
    private function constructPath($type){
        $baseFolder = $this->config['DATA_FOLDER'];
        switch ($type) {
            case 'ANNOUNCEMENT':
                return $baseFolder."organization/".AuthContext::get(AuthConstants::ORG_ID)."/announcements/";
            default:
                return $baseFolder;
        }
    }
}
?>