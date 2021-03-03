<?php
namespace Team\Controller;

use Oxzion\Controller\AbstractApiController;
use Team\Service\TeamService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;
use Zend\InputFilter\Input;
use Oxzion\Utils\FileUtils;
use Oxzion\Controller\AbstractApiControllerHelper;

class TeamLogoController extends AbstractApiControllerHelper
{
    /**
    * @var ProfilepictureService Instance of Projectpicture Service
    */
    private $teamService;
    /**
    * @ignore __construct
    */
    public function __construct(TeamService $teamService, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('teamId');
        $this->teamService = $teamService;
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
        $orgId = $this->params()->fromRoute()['orgId'];
        $logo = "logo.png";
        $file = $this->teamService->getTeamLogoPath($orgId, $id);
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
            $this->logger->error($e->getMessage(), $e);
            return $this->getErrorResponse("Team Logo not found", 404);
        }
    }
}
