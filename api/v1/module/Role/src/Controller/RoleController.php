<?php
namespace Role\Controller;
/**
* Role Api
*/
use Zend\Log\Logger;
use Oxzion\Model\Role;
use Oxzion\Model\RoleTable;
use Oxzion\Service\RoleService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\InputFilter\Input;
use Oxzion\ServiceException;

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
	public function __construct(RoleTable $table, RoleService $roleService, Logger $log, AdapterInterface $dbAdapter) {
		parent::__construct($table, $log, __CLASS__, Role::class);
		$this->setIdentifierName('roleId');
		$this->roleService = $roleService;
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
    public function create($data){
        $id = $this->params()->fromRoute();
        try{
            $count = $this->roleService->saveRole(null,$data,$id);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        catch(ServiceException $e){
            return $this->getErrorResponse($e->getMessage(),404);
        }
        if($count == 0){
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data,201);
    }
    
    /**
    * GET List Roles API
    * @api
    * @link /role
    * @method GET
    * @return array Returns a JSON Response list of roles based on Form id.
    */
    public function getList() {
        $filterParams = $this->params()->fromQuery(); // empty method call  
        $result = $this->roleService->getRoles($filterParams);
        return $this->getSuccessResponseDataWithPagination($result['data'],$result['total']);
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
    public function update($id, $data){
        try{
            $params = $this->params()->fromRoute(); 
            $count = $this->roleService->saveRole($id,$data,$params);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        catch(ServiceException $e){
            return $this->getErrorResponse($e->getMessage(),404);
        }
        return $this->getSuccessResponseWithData($data,200);
    }
    /**
    * Delete Role API
    * @api
    * @link /role[/:roleId]
    * @method DELETE
    * @param $id ID of Role to Delete
    * @return array success|failure response
    */
    public function delete($id){
        $params = $this->params()->fromRoute();
        $response = $this->roleService->deleteRole($id,$params);
        if($response == 0){
            return $this->getErrorResponse("Role not found", 404, ['id' => $id]);
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
    public function get($id){
        try {
            $result = $this->roleService->getRole($id);
        } catch (ValidationException $e) { 
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if(($result == 0)||(empty($result))){
            return $this->getErrorResponse("Role not found", 404, ['id' => $id]);
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
    public function roleprivilegeAction(){
        $role=$this->params()->fromRoute();
        $id=$role['roleId'];
        try {
            $result = $this->roleService->getRolePrivilege($id);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if(($result == 0)||(empty($result))){
            return $this->getErrorResponse("Priviledges not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);  
    } 
       
}
