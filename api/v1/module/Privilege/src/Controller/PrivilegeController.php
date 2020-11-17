<?php
namespace Privilege\Controller;

/**
 * Privilege Api
 */
use Exception;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\Privilege;
use Oxzion\Model\PrivilegeTable;
use Oxzion\Service\PrivilegeService;
use Zend\Db\Adapter\AdapterInterface;

/**
 * Privilege Controller
 */
class PrivilegeController extends AbstractApiController
{
    /**
     * @ignore PrivilegeService
     */
    private $privilegeService;

    /**
     * @ignore __construct
     */
    public function __construct(PrivilegeTable $table, PrivilegeService $privilegeService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Privilege::class);
        $this->setIdentifierName('privilegeId');
        $this->privilegeService = $privilegeService;
        $this->log = $this->getLogger();
    }

    public function getMasterPrivilegeAction()
    {
        $params = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> Get Master Privilege - " . json_encode($params, true));
        try {
            $result = $this->privilegeService->getMasterPrivilegeList($params);
            return $this->getSuccessResponseWithData($result);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        
    }

    /**
     * Get list of all the privileges for the logged in user for a specific app
     * @api
     * @link /privilege/app/:appId
     * @method get
     * @param array $data Array of elements as shown
     * <code> {
     *      id : integer,
     *      name : string,
     *      permission_allowed : integer,
     * } </code>
     * @return array Returns a JSON Response with Status Code and Created Privilege.
     * TODO Not sure about the way we are returning the array back here. Im not changing anything now, but we need to change them - Rakshith
     */
    public function getUserPrivilegesAction()
    {
        $params = $this->params()->fromRoute();
        $appId = $params['appId'];
        $this->log->info(__CLASS__ . "-> Get User Privilege - " . json_encode($params, true));
        try {
            $result = $this->privilegeService->getAppPrivilegeForUser($appId);
            if (empty($result)) {
                return $this->getErrorResponse("There is nothing in your privilege list!", 404, ['id' => $appId]);
            }
            return $this->getSuccessResponseWithData($result);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
        
    }

}
