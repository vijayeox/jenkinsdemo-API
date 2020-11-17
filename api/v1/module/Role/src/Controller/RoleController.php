<?php
namespace Role\Controller;

/**
 * Role Api
 */
use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\Role;
use Oxzion\Model\RoleTable;
use Oxzion\Service\RoleService;
use Zend\Db\Adapter\AdapterInterface;
use Exception;

/**
 * Role Controller
 */
class RoleController extends AbstractApiController
{
    /**
     * @ignore roleService
     */
    private $roleService;
    /**
     * @ignore __construct
     */
    public function __construct(RoleTable $table, RoleService $roleService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Role::class);
        $this->setIdentifierName('roleId');
        $this->roleService = $roleService;
        $this->log = $this->getLogger();
    }

    /**
     * Create Role API
     * @api
     * @link /role
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               userid : integer,
     *               privileges : [
     *                   {
     *                       id : integer
     *                       name : string,
     *                       permission : integer,
     *
     *                   }, ...
     *                ]
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Role.
     */
    public function create($data)
    {
        $params = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> \n Create Role - " . print_r($data, true) . "Parameters - " . print_r($params, true));
        try {
            $this->roleService->saveRole($params, $data);
            return $this->getSuccessResponseWithData($data, 201);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
    }

    /**
     * GET List Roles API
     * @api
     * @link /role
     * @method GET
     * @return array Returns a JSON Response list of roles based on Form id.
     */
    public function getList()
    {
        $filterParams = $this->params()->fromQuery(); // empty method call
        $params = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> \n Get Role in List- " . print_r($params, true) . "Filter Parameters - " . print_r($filterParams, true));
        try {
            $result = $this->roleService->getRoles($filterParams, $params);
            return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        
    }
    /**
     * Update Role API
     * @api
     * @link /role[/:roleId]
     * @method PUT
     * @param array $id ID of Role to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Role.
     */
    public function update($id, $data)
    {
        $params = $this->params()->fromRoute();
        $this->log->info(__CLASS__ . "-> \n Get Role in List- " . print_r($data, true) . "Parameters - " . print_r($params, true));
        try {
            $this->roleService->saveRole($params, $data, $id);
            return $this->getSuccessResponseWithData($data, 200);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        
    }

    /**
     * Delete Role API
     * @api
     * @link /role[/:roleId]
     * @method DELETE
     * @param $id ID of Role to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        try {
            $params = $this->params()->fromRoute();
            $response = $this->roleService->deleteRole($id, $params);
            return $this->getSuccessResponse();
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        
    }

    /**
     * GET Role API
     * @api
     * @link /role[/:roleId]
     * @method GET
     * @param $id ID of Role
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created Role.
     */
    public function get($id)
    {
        try {
            $params = $this->params()->fromRoute();
            $result = $this->roleService->getRole($params);
            return $this->getSuccessResponseWithData($result);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        
    }

    /**
     * GET Role Priviledges API
     * @api
     * @link /role/:roleId/priviledges
     * @method GET
     * @param $id ID of Role
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Priviledges of Created Role.
     */
    public function roleprivilegeAction()
    {
        $params = $this->params()->fromRoute();
        try {
            $result = $this->roleService->getRolePrivilege($params);
            return $this->getSuccessResponseWithData($result);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        } 
        
    }
}
