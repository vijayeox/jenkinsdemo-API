<?php
namespace User\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\ProfilePictureService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;
use Zend\InputFilter\Input;
use Oxzion\Utils\FileUtils;



class ProfilePictureController extends AbstractApiController { 
    /**
    * @var ProfilepictureService Instance of Projectpicture Service
    */
    private $profilepictureService;
    /**
    * @ignore __construct
    */
    public function __construct(ProfilePictureService $profilepictureService, Logger $log, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('profileId');
        $this->profilepictureService = $profilepictureService;
    }
     /**
    * Update Profilepicture API
    * @api
    * @link /profilepicture[/:profileId]
    * @method POST
    * @param $id ID of Profilepicture to update 
    * @param $data 
    * @return array Returns a JSON Response with Status Code and Created Profilepicture.
    */
    public function updateAction() {
        $files = $_FILES["file"]["tmp_name"];
        try {
            $count = $this->profilepictureService->uploadProfilepicture($files);

        } catch (Exception $e) {
            $this->log->error("Failed to upload profile picture", $e);
            return $this->getErrorResponse("Profile picture upload failed",500);
        }
        return $this->getSuccessResponse("Upload successfull",200);


    }

    /**
    * GET Profile API
    * @api
    * @link /user/profile[/:profileId]
    * @method GET
    * @param $profileId ID of user
    * @return profile picture 
    */
    public function profileAction(){
        $params = $this->params()->fromRoute();
        //print_r($params);
        $profileId = $params['profileId'];
        //echo $profileId;
        $file = $this->profilepictureService->getProfilePicturePath($profileId);
        
        if(FileUtils::fileExists($file) != 1){
             $file = $this->profilepictureService->getProfilePicturePath(null);
             // print $file;
         }
     
        if (!headers_sent()) {
            header('Content-Type: image/png');
            header("Content-Transfer-Encoding: Binary"); 
        }
        try {
            $fp = @fopen($file, 'rb');
            fpassthru($fp);
            fclose($fp);
            $this->response->setStatusCode(200);
            return $this->response;
        } catch(Exception $e){
            print_r($e->getMessage());
            return $this->getErrorResponse("Profile picture not found", 404);
        }
    }
    
}