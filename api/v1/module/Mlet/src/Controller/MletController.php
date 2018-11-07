<?php
namespace Mlet\Controller;

use Zend\Log\Logger;
use Mlet\Model\Mlet;
use Mlet\Model\MletTable;
use Mlet\Service\MletService;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Utils\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class MletController extends AbstractApiController
{
	private $dbAdapter;
	private $mletService;

	public function __construct(MletTable $table,MletService $mletService,Logger $log){
		parent::__construct($table, $log, __CLASS__, Mlet::class);
		$this->mletService=$mletService;
		$this->setIdentifierName('mletId');
	}

	public function getList() {
		$params = $this->params()->fromRoute();
		$result = $this->mletService->getMlets();
		return $this->getSuccessResponseWithData($result);
	}
  

}
