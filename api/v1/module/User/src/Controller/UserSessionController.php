<?php
namespace User\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Exception;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\UserSessionService;
use Zend\Db\Sql\Sql;

class UserSessionController extends AbstractApiController
{
    private $sessionService;

    /**
     * @ignore __construct
     */
    public function __construct(UserSessionService $sessionService, Logger $log, AdapterInterface $dbAdapter)
    {
        $this->setIdentifierName('userId');
        $this->sessionService = $sessionService;
    }

    /**
     * GET Session API
     * @api
     * @link /user/session
     * @method GET
     * @param array $dataget of User
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created User.
     */
    public function getSessionAction()
    {
        $result = $this->sessionService->getSessionData();
        if (!empty($result[0])) {
            return $this->getSuccessResponseWithData(json_decode($result[0], true), 200);
        } else {
            return $this->getSuccessResponseWithData(array(), 200);
        }
    }

    /**
     * Update User Session API
     * @api
     * @link /user/session
     * @method POST
     * @param array $id ID of User to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created User.
     */
    public function updateSessionAction()
    {
        $data=$this->extractPostData();
        try {
            $count = $this->sessionService->updateSessionData($data);
        } catch (Exception $e) {
            return $this->getErrorResponse("Update Failure", 404, array("message" -> $e->getMessage()));
        }
        if (!empty(($data['data']))) {
            return $this->getSuccessResponseWithData(json_decode($data['data'], true), 200);
        } else {
            return $this->getSuccessResponseWithData(array(), 200);
        }
    }
}
