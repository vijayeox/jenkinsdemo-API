<?php
namespace Screen\Controller;

use Zend\Log\Logger;
use Screen\Model\Screenwidget;
use Screen\Model\ScreenwidgetTable;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Controller\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class ScreenwidgetController extends AbstractApiController
{
	private $dbAdapter;

	public function __construct(ScreenwidgetTable $table, Logger $log){
		parent::__construct($table, $log, __CLASS__, Screenwidget::class);
		$this->setIdentifierName('id');
    }
    
    public function screenAction() {
        $params = $this->params()->fromRoute();

    }

}
