<?php
namespace Account\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\AccountService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;
use Zend\InputFilter\Input;
use Oxzion\Utils\FileUtils;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\UserService;

class AccountLogoController extends AbstractApiControllerHelper
{
    /**
    * @var ProfilepictureService Instance of Projectpicture Service
    */
    private $accountService;
    /**
    * @ignore __construct
    */
    public function __construct(AccountService $accountService, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('accountId');
        $this->accountService = $accountService;
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
        $logo = "logo.png";
        $file = $this->accountService->getAccountLogoPath($id);
        
        if (FileUtils::fileExists($file) != 1) {
            $file = $this->accountService->getAccountLogoPath(null);
        }
        $file = $file . $logo;
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
            print_r($e->getMessage());
            return $this->getErrorResponse("Account Logo not found", 404);
        }
    }
}
