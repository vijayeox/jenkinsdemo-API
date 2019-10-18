<?php
namespace Contact\Controller;

use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Exception;
use Zend\InputFilter\Input;
use Oxzion\Utils\FileUtils;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\UserService;
use Contact\Service\ContactService;

class ContactIconController extends AbstractApiControllerHelper
{
    /**
    * @var ProfilepictureService Instance of Projectpicture Service
    */
    private $contactService;
    private $log;
    /**
    * @ignore __construct
    */
    public function __construct(ContactService $contactService, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('contactId');
        $this->contactService = $contactService;
        $this->log = $this->getLogger();
    }

    /**
    * GET Profile API
    * @api
    * @link /user/profile[/:profileId]
    * @method GET
    * @param $profileId ID of user
    * @return profile picture
    */
    public function getIconAction()
    {
        $params = $this->params()->fromRoute();
        $ownerid = $params['ownerId'];
        $contactid = $params['contactId'];
        $logo = $contactid.".png";
        $file = $this->contactService->getContactIconPath($ownerid);
        $iconUrl = $file.$logo;
        if (!FileUtils::fileExists($iconUrl)) {
            $file = $this->contactService->getContactIconPath(null);
            $iconUrl = $file."profile.png";
        }
                
        if (!headers_sent()) {
            header('Content-Type: image/png');
            header("Content-Transfer-Encoding: Binary");
        }
        try {
            $fp = @fopen($iconUrl, 'rb');
            fpassthru($fp);
            fclose($fp);
            $this->response->setStatusCode(200);
            return $this->response;
        } catch (Exception $e) {
            $this->log->error("Error getting Icon", $e);
            return $this->getErrorResponse("Contact Icon not found", 404);
        }
    }
}
