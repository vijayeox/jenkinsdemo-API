<?php
namespace Field\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Field\Model\Field;
use Field\Model\FieldTable;

class FieldController extends AbstractApiController
{
	public function __construct(FieldTable $table, Logger $log){
		parent::__construct($table, $log, __CLASS__, Field::class);
	}
}