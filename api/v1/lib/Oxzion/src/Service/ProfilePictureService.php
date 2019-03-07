<?php
namespace Oxzion\Service;

use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\Service\AbstractService;
use Oxzion\Model\User;
use Bos\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;
// use v1\module\Attachment\Service\AttachmentService;
use Oxzion\Utils\FileUtils;


class ProfilePictureService extends AbstractService{
    
    private $profilePic = "profile.png";
    
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter){
        parent::__construct($config, $dbAdapter);
    }

    public function getProfilePicturePath($id,$ensureDir=false){

        $baseFolder = $this->config['DATA_FOLDER'];
        //TODO : Replace the User_ID with USER uuid
        $folder = $baseFolder."user/";
        if(isset($id)){
            $folder = $folder.$id."/";
        }

        if($ensureDir && !file_exists($folder)){
            FileUtils::createDirectory($folder);
        }

        return $folder.$this->profilePic;
    }


    

    /**
     * createUpload
     *
     * Upload files from Front End and store it in temp Folder
     *
     *  @param files Array of files to upload
     *  @return JSON array of filenames
    */
    public function uploadProfilepicture($file){
        $id = AuthContext::get(AuthConstants::USER_UUID);

        if(isset($file)){

            $destFile = $this->getProfilePicturePath($id,true);

            // move_uploaded_file($file, $destFile);
            file_put_contents($destFile, $file);
        }
    }
}