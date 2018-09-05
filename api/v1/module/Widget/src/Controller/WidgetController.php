<?php
namespace Widget\Controller;

use Zend\Log\Logger;
use Widget\Model\Widget;
use Widget\Model\WidgetTable;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Controller\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class WidgetController extends AbstractApiController
{
	private $dbAdapter;

	public function __construct(WidgetTable $table, Logger $log){
		parent::__construct($table, $log, __CLASS__, Widget::class);
		$this->setIdentifierName('widgetId');
	}

}
