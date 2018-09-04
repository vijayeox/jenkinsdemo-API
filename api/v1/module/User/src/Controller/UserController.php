<?php
namespace User\Controller;

use Zend\Log\Logger;
use User\Model\User;
use User\Model\UserTable;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Controller\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class UserController extends AbstractApiController {

	private $dbAdapter;

	public function __construct(UserTable $table, Logger $log, AdapterInterface $dbAdapter){
		parent::__construct($table, $log, __CLASS__, User::class);
		$this->dbAdapter = $dbAdapter;
		$this->setIdentifierName('userId');
	}

	protected function validate($model){
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('avatars');
		$select->columns(['username']);
		$select->where(['username' => $model->username]);
		$sql = $sql->buildSqlString($select);
		$results = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$row = $results->current();
		if(is_array($row)){
			return new ValidationResult(ValidationResult::FAIL, "User name Already Exists");
		}
		return new ValidationResult(ValidationResult::SUCCESS);
	}
}