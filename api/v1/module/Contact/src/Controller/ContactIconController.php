<?php
namespace Contact\Controller;

use Contact\Service\ContactService;
use Exception;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Utils\FileUtils;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Log\Logger;

class ContactIconController extends AbstractApiControllerHelper
{
    /**
     * @var ProfilepictureService Instance of Projectpicture Service
     */
    private $contactService;
    /**
     * @ignore __construct
     */
    public function __construct(ContactService $contactService, Logger $log, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('contactId');
        $this->contactService = $contactService;
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
        $logo = $contactid . ".png";
        $file = $this->contactService->getContactIconPath($ownerid);
        $iconUrl = $file . $logo;
        if (!FileUtils::fileExists($iconUrl)) {
            $file = $this->contactService->getContactIconPath(null);
            $iconUrl = $file . "profile.png";
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
            print_r($e->getMessage());
            return $this->getErrorResponse("Contact Icon not found", 404);
        }
    }
}
