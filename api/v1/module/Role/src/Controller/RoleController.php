<?php
namespace Role\Controller;

/**
 * Role Api
 */
use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\Role;
use Oxzion\Model\RoleTable;
use Oxzion\ServiceException;
use Oxzion\Service\RoleService;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\AccessDeniedException;

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
            $count = $this->roleService->saveRole($params, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (AccessDeniedException $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 403);
        } catch (ServiceException $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseWithData($data, 201);
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
        } catch (AccessDeniedException $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 403);
        }
        return $this->getSuccessResponseDataWithPagination($result['data'], $result['total']);
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
            $count = $this->roleService->saveRole($params, $data, $id);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (AccessDeniedException $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 403);
        } catch (ServiceException $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
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
        } catch (ServiceException $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        return $this->getSuccessResponse();
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
        } catch (ValidationException $e) {
            $response = ['data' => $params, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($result);
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
        } catch (ValidationException $e) {
            $response = ['errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($result);
    }
}
