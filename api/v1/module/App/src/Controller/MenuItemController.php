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
    public function __construct(MenuItemTable $table, MenuItemService $menuItemService, Logger $log, AdapterInterface $dbAdapter)
    {
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
    public function create($data)
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        try {
            $count = $this->menuItemService->saveMenuItem($appUuid, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }
    
    /**
    * GET List MenuItems API
    * @api
    * @link /app/appId/menu
    * @method GET
    * @return array Returns a JSON Response list of MenuItems based on Access.
    */
    public function getList()
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        $result = $this->menuItemService->getMenuItems($appUuid);
        if($result == 0){ 
            return $this->getErrorResponse("No Menus Found for the specified App", 404);  
        }
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
    public function update($menuId, $data)
    {
        try {
            $count = $this->menuItemService->updateMenuItem($menuId, $data);
        } catch (ValidationException $e) { 
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) { 
            return $this->getErrorResponse("Entity not found for id - $menuId", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }
    /**
    * Delete MenuItem API
    * @api
    * @link /app/appId/menuItem[/:id]
    * @method DELETE
    * @param $id ID of MenuItem to Delete
    * @return array success|failure response
    */
    public function delete($menuId)
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        $response = $this->menuItemService->deleteMenuItem($appUuid,$menuId);
        if ($response == 0) { 
            return $this->getErrorResponse("MenuItem not found", 404, ['id' => $menuId]);
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
    public function get($menuId)
    {
        $appUuid = $this->params()->fromRoute()['appId'];
        $result = $this->menuItemService->getMenuItem($appUuid, $menuId);
        if ($result == 0) {
            return $this->getErrorResponse("MenuItem not found", 404, ['id' => $menuId]);
        }
        return $this->getSuccessResponseWithData($result);
    }
}
