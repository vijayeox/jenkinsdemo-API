<?php
namespace User\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\ProfilePictureService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;
use Zend\InputFilter\Input;
use Oxzion\Utils\FileUtils;
use Oxzion\Controller\AbstractApiControllerHelper;

class ProfilePictureDownloadController extends AbstractApiControllerHelper { 
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
    * GET Profile API
    * @api
    * @link /user/profile[/:profileId]
    * @method GET
    * @param $profileId ID of user
    * @return profile picture 
    */
    public function get($id){
        $file = $this->profilepictureService->getProfilePicturePath($id);
        if(FileUtils::fileExists($file) != 1){
             $file = $this->profilepictureService->getProfilePicturePath(null);
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