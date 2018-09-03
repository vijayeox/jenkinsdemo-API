<?php
namespace User\Controller;

use Zend\Log\Logger;
use User\Model\User;
use User\Model\UserTable;
use Oxzion\Controller\AbstractApiController;

class UserController extends AbstractApiController {

	public function __construct(UserTable $table, Logger $log){
		parent::__construct($table, $log, __CLASS__, User::class);
		$this->setIdentifierName('avatarId');
	}
}