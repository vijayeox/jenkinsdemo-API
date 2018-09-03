<?php
namespace Group\Controller;

use Zend\Log\Logger;
use Group\Model\Group;
use Group\Model\GroupTable;
use Oxzion\Controller\AbstractApiController;

class GroupController extends AbstractApiController {

    public function __construct(GroupTable $table, Logger $log){
		parent::__construct($table, $log, __CLASS__, Group::class);
		$this->setIdentifierName('formId');
	}
}