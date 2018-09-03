<?php
namespace Organization\Controller;

use Zend\Log\Logger;
use Organization\Model\Organization;
use Organization\Model\OrganizationTable;
use Oxzion\Controller\AbstractApiController;

class OrganizationController extends AbstractApiController {

	public function __construct(OrganizationTable $table, Logger $log){
		parent::__construct($table, $log, __CLASS__, Organization::class);
		$this->setIdentifierName('orgId');
	}
}