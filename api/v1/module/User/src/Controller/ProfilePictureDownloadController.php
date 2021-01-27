<?php
namespace User\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\ProfilePictureService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;
use Zend\InputFilter\Input;
use Oxzion\Utils\FileUtils;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\UserService;

class ProfilePictureDownloadController extends AbstractApiControllerHelper
{
    /**
    * @var ProfilepictureService Instance of Projectpicture Service
    */
    private $profilepictureService;
    private $userService;
    /**
    * @ignore __construct
    */
    public function __construct(ProfilePictureService $profilepictureService, AdapterInterface $dbAdapter, $userService)
    {
        $this->setIdentifierName('profileId');
        $this->profilepictureService = $profilepictureService;
        $this->userService = $userService;
    }

    /**
    * GET Profile API
    * @api
    * @link /user/profile[/:profileId]
    * @method GET
    * @param $profileId ID of user
    * @return profile picture
    */
    public function get($id)
    {
        $file = $this->profilepictureService->getProfilePicturePath($id);
        if (FileUtils::fileExists($file) != 1) {
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
        } catch (Exception $e) {
            $this->log-error($e->getMessage(), $e);
            return $this->getErrorResponse("Profile picture not found", 404);
        }
    }

    /**
    * GET Profile API Using Username
    * @api
    * @link /user/profile/username[/:username]
    * @method GET
    * @param $username of the user
    * @return profile picture
    */
    public function profilePictureByUsernameAction()
    {
        $params = $this->params()->fromRoute();
        $file = "nonexistant_file";
        if (isset($params['username'])) {
            $userInfo = $this->userService->getUserContextDetails($params['username']);
            $file = $this->profilepictureService->getProfilePicturePath($userInfo['userId']);
        }
        if (FileUtils::fileExists($file) != 1) {
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
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Profile picture not found", 404);
        }
    }
}
