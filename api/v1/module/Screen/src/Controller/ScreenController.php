<?php
namespace Screen\Controller;

use Zend\Log\Logger;
use Screen\Model\Screen;
use Screen\Model\ScreenTable;

use Oxzion\Controller\AbstractApiController;
use Oxzion\Controller\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class ScreenController extends AbstractApiController
{
	private $dbAdapter;

	public function __construct(ScreenTable $table, Logger $log){
		parent::__construct($table, $log, __CLASS__, Screen::class);
		$this->setIdentifierName('screenId');
    }
    
    public function screenAction() {
        $params = $this->params()->fromRoute();

    }

}
