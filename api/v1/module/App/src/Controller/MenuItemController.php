<?php
namespace App\Controller;
/**
* MenuItem Api
*/
use Zend\Log\Logger;
use App\Model\MenuItem;
use App\Model\MenuItemTable;
use App\Service\MenuItemService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;

class MenuItemController extends AbstractApiController
{
    private $menuItemService;
    /**
    * @ignore __construct
    */
	public function __construct(MenuItemTable $table, MenuItemService $menuItemService, Logger $log, AdapterInterface $dbAdapter) {
		parent::__construct($table, $log, __CLASS__, MenuItem::class);
		$this->setIdentifierName('menuId');
		$this->menuItemService = $menuItemService;
	}
	/**
    * Create MenuItem API
    * @api
    * @link /app/appId/menuItem
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               Fields from MenuItem
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created MenuItem.
    */
    public function create($data){
        $appId = $this->params()->fromRoute()['appId'];
        try{
            $count = $this->menuItemService->saveMenuItem($appId,$data);
        } catch (ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data,201);
    }
    
    /**
    * GET List MenuItems API
    * @api
    * @link /app/appId/menu
    * @method GET
    * @return array Returns a JSON Response list of MenuItems based on Access.
    */
    public function getList() {
        $appId = $this->params()->fromRoute()['appId'];
        $result = $this->menuItemService->getMenuItems($appId);
        return $this->getSuccessResponseWithData($result);
    }
    /**
    * Update MenuItem API
    * @api
    * @link /app/appId/menuItem[/:id]
    * @method PUT
    * @param array $id ID of MenuItem to update 
    * @param array $data 
    * @return array Returns a JSON Response with Status Code and Created MenuItem.
    */
    public function update($id, $data){
        $appId = $this->params()->fromRoute()['appId'];
        try{
            $count = $this->menuItemService->updateMenuItem($id,$data);
        }catch(ValidationException $e){
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors",404, $response);
        }
        if($count == 0){
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data,200);
    }
    /**
    * Delete MenuItem API
    * @api
    * @link /app/appId/menuItem[/:id]
    * @method DELETE
    * @param $id ID of MenuItem to Delete
    * @return array success|failure response
    */
    public function delete($id){
        $appId = $this->params()->fromRoute()['appId'];
        $response = $this->menuItemService->deleteMenuItem($appId,$id);
        if($response == 0){
            return $this->getErrorResponse("MenuItem not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    /**
    * GET MenuItem API
    * @api
    * @link /app/appId/menuItem[/:id]
    * @method GET
    * @param $id ID of MenuItem
    * @return array $data 
    * @return array Returns a JSON Response with Status Code and Created MenuItem.
    */
    public function get($id){
        $appId = $this->params()->fromRoute()['appId'];
        $result = $this->menuItemService->getMenuItem($appId,$id);
        if($result == 0){
            return $this->getErrorResponse("MenuItem not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($result);
    }
}