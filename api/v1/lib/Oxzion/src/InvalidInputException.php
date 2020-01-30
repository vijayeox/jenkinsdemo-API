<?php
namespace Oxzion;
use Exception;

class InvalidInputException extends \Exception {
	private $messageCode;
	private $errors = array();
	public function __construct($message,$messageCode) {
		parent::__construct($message, 0, null);
		$this->messageCode = $messageCode;
	}

	public function getMessageCode(){
		return $this->messageCode;
	}
}
?>