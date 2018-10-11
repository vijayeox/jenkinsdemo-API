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
    

  public function getList() {
      $params = $this->params()->fromRoute();
      $result = $this->screenwidgetService->getWidgets($params['screenId']);
      return $this->getSuccessResponseWithData($result);
  }

  public function create($data){
    try{
        $params = $this->params()->fromRoute();
        $data['screenid'] = $params['screenId'];  
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

public function update($id, $data){
    try{
        $params = $this->params()->fromRoute();
        $data['screenid'] = $params['screenId'];
        $data['widgetid'] = $id;  
        $count = $this->screenwidgetService->updateWidget($data);
    }catch(ValidationException $e){
        $response = ['data' => $data, 'errors' => $e->getErrors()];
        return $this->getErrorResponse("Validation Errors",404, $response);
    }
    if($count == 0){
        return $this->getFailureResponse("Failed to update  entity", $data);
    }
    return $this->getSuccessResponseWithData($data,200);
}


public function delete($id){
    try{
        $data = array();
        $params = $this->params()->fromRoute();
        $data['screenid'] = $params['screenId'];
        $data['widgetid'] = $id;  
        $count = $this->screenwidgetService->deleteWidget($data);
    }catch(ValidationException $e){
        $response = ['data' => $data, 'errors' => $e->getErrors()];
        return $this->getErrorResponse("Validation Errors",404, $response);
    }
    if($count == 0){
        return $this->getFailureResponse("Failed to delete entity", $data);
    }
    return $this->getSuccessResponseWithData($data,200);
}

}
