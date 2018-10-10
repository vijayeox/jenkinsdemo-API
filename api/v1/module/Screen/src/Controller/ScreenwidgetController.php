<?php
namespace Screen\Controller;

use Zend\Log\Logger;
use Screen\Model\Screenwidget;
use Screen\Model\ScreenwidgetTable;
use Screen\Service\ScreenwidgetService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class ScreenwidgetController extends AbstractApiController
{
	private $dbAdapter;
  private $screenwidgetService;

	public function __construct(ScreenwidgetTable $table,ScreenwidgetService $screenwidgetService, Logger $log){
		parent::__construct($table, $log, __CLASS__, Screenwidget::class);
    $this->screenwidgetService=$screenwidgetService;
    $this->setIdentifierName('id');
    
    }
    

  public function getWidgetsAction() {
    $params = $this->params()->fromRoute();
    $result = $this->screenwidgetService->getWidgets($params['screenId']);
    return $this->getSuccessResponseWithData($result);
  }

  public function create($data){
    try{
        $count = $this->screenwidgetService->createWidget($data);
    }catch(ValidationException $e){
        $response = ['data' => $data, 'errors' => $e->getErrors()];
        return $this->getErrorResponse("Validation Errors",404, $response);
    }
    if($count == 0){
        return $this->getFailureResponse("Failed to create a new entity", $data);
    }
    return $this->getSuccessResponseWithData($data,201);
}

// public function update($id, $data){
//     try{
//         $count = $this->screenwidgetService->update($id,$data);
//     }catch(ValidationException $e){
//         $response = ['data' => $data, 'errors' => $e->getErrors()];
//         return $this->getErrorResponse("Validation Errors",404, $response);
//     }
//     if($count == 0){
//         return $this->getErrorResponse("Entity not found for id - $id", 404);
//     }
//     return $this->getSuccessResponseWithData($data,200);
// }

public function getList(){
    return;
}

}
