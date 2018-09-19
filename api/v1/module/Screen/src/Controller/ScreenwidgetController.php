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

}
