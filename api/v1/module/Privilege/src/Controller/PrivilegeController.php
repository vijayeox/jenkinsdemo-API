<?php
namespace Privilege\Controller;

/**
 * Privilege Api
 */
use Bos\ValidationException;
use Oxzion\Controller\AbstractApiController;
use Privilege\Model\Privilege;
use Privilege\Model\PrivilegeTable;
use Privilege\Service\PrivilegeService;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Log\Logger;

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
    public function __construct(PrivilegeTable $table, PrivilegeService $privilegeService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __class__, Privilege::class);
        $this->setIdentifierName('privilegeId');
        $this->privilegeService = $privilegeService;
    }

    /**
     * Get list of all the privileges for the logged in user for a specific app
     * @api
     * @link /privilege/app/:appId
     * @method get
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               permission_allowed : integer,
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Privilege.
     */

    public function getUserPrivilegesAction()
    {
        $params = $this->params()->fromRoute();
        $appId = $params['appId'];
        $result = $this->privilegeService->getAppPrivilegeForUser($appId);
        if ($result['status'] === 'error') {
            return $this->getFailureResponse("No Privileges to show, there is something wrong with your request");
        }
        if ($result == null || empty($result)) {
            return $this->getErrorResponse("There is nothing in your privilege list!", 404, ['id' => $appId]);
        }
        return $this->getSuccessResponseWithData($result);
    }

    public function getAppIdAction()
    {
        $result = $this->privilegeService->getAppId();
        if($result == 0){
            return $this->getFailureResponse("Something went wrong");
        }
        return $this->getSuccessResponseWithData($result);
    }

}
