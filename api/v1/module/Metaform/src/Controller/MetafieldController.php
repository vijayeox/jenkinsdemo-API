<?php
namespace Metaform\Controller;

use Zend\Log\Logger;
use Metaform\Model\Metafield;
use Metaform\Model\MetafieldTable;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Controller\ValidationResult;
use Zend\Db\Adapter\AdapterInterface;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class MetafieldController extends AbstractApiController
{
	private $dbAdapter;

	public function __construct(MetafieldTable $table, Logger $log, AdapterInterface $dbAdapter){
		parent::__construct($table, $log, __CLASS__, Metafield::class, 'formId');
		$this->dbAdapter = $dbAdapter;
		
	}

	protected function validate($model){
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from('fields');
		$select->columns(['id']);
		$select->where(['name' => $model->name]);
		$sql = $sql->buildSqlString($select);
		$results = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
		$row = $results->current();
		if(!is_array($row)){
			return new ValidationResult(ValidationResult::FAIL, "Field '$model->name' must be first defined before adding to Metaform");
		}

		return new ValidationResult(ValidationResult::SUCCESS);
	}
}
