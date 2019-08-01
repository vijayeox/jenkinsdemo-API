<?php
namespace Organization\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\OrganizationService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;
use Zend\InputFilter\Input;
use Oxzion\Utils\FileUtils;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\UserService;

class OrganizationLogoController extends AbstractApiControllerHelper { 
    /**
    * @var ProfilepictureService Instance of Projectpicture Service
    */
    private $organizationService;
    /**
    * @ignore __construct
    */
    public function __construct(OrganizationService $organizationService, Logger $log, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('orgId');
        $this->organizationService = $organizationService;
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
        $logo = "logo.png";
        $file = $this->organizationService->getOrgLogoPath($id);
        
        if(FileUtils::fileExists($file) != 1){
             $file = $this->organizationService->getOrgLogoPath(null);
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
        } catch(Exception $e){
            print_r($e->getMessage());
            return $this->getErrorResponse("Organization Logo not found", 404);
        }
    }
}