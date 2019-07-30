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
        parent::__construct(null, $log, __class__, null);
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
    public function updateProfileAction() {
        $this->log->info($this->logClass . ":Profile update controller");
        $params=$this->extractPostData();
        $files=substr($params['file'],strpos($params['file'],",")+1);
        $files=base64_decode($files);
        try {
            $count = $this->profilepictureService->uploadProfilepicture($files);

        } catch (Exception $e) {
            $this->log->err("Failed to upload profile picture", [$e]);
            return $this->getErrorResponse("Profile picture upload failed",500);
        }
        return $this->getSuccessResponse("Upload successfull",200);
        $this->log->info($this->logClass . ":Profile update controller end");
    }    
}