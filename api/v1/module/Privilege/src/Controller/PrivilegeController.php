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
        } catch (Exception $e) {
            throw $e;
        }
        return $this->getSuccessResponseWithData($result);
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
            if ($result == null || empty($result)) {
                return $this->getErrorResponse("There is nothing in your privilege list!", 404, ['id' => $appId]);
            }
            if ($result) {
                $result['status'] = isset($result['status']) ? $result['status'] : null;
                if ($result['status'] === 'error') {
                    return $this->getFailureResponse("No Privileges to show, there is something wrong with your request");
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $this->getSuccessResponseWithData($result);
    }

    public function getAppIdAction()
    {
        $result = $this->privilegeService->getAppId();
        if ($result == 0) {
            return $this->getFailureResponse("Something went wrong");
        }
        return $this->getSuccessResponseWithData($result);
    }
}
