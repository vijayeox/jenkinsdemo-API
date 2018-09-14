<?php
namespace Screen\Controller;

use Zend\Log\Logger;
use Screen\Model\Screenwidget;
use Screen\Model\ScreenwidgetTable;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\ValidationResult;
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
    
    public function getList() {
        $params = $this->params()->fromRoute();
        
        $data=$this->table->fetchAll(['userid' => $this->authContext->getId(),'screenid' =>$params['screenId']])->toArray();
        return $this->getSuccessResponseWithData($data);
     }

}
