<?php

namespace Oxzion\Utils;


class ValidationResult {
	const SUCCESS = 1;
	const FAIL = 0;

	private $status;
	private $message;
	
	public function __construct($status, $message = ''){
		$this->status = (int) $status;
		$this->message = $message;
	}

	public function isValid()
    {
        return ($this->status > 0);
    }

    public function getMessage()
    {
        return $this->message;
    }
}