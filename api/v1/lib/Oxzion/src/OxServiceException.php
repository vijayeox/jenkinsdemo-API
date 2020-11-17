<?php

namespace Oxzion;

use Throwable;
use Exception;

abstract class OxServiceException extends Exception {
    public const ERR_TYPE_ERROR     = 'error';
    public const ERR_TYPE_FAILURE   = 'failure';

    public const ERR_CODE_OK                    = 200; //Corresponds to HTTP 200.
    public const ERR_CODE_UNAUTHORIZED          = 401; //Corresponds to HTTP 401.
    public const ERR_CODE_FORBIDDEN             = 403; //Corresponds to HTTP 403.
    public const ERR_CODE_NOT_FOUND             = 404; //Corresponds to HTTP 404.
    public const ERR_CODE_NOT_ACCEPTABLE        = 406; //Corresponds to HTTP 406.
    public const ERR_CODE_CONFLICT              = 409; //Corresponds to HTTP 409.
    public const ERR_CODE_PRECONDITION_FAILED   = 412; //Corresponds to HTTP 412.
    public const ERR_CODE_UNPROCESSABLE_ENTITY  = 422; //Corresponds to HTTP 422.
    public const ERR_CODE_INTERNAL_SERVER_ERROR = 500; //Corresponds to HTTP 500.

    protected $errorCode;
    protected $errorType;
    protected $contextData;

	public function __construct($message, $contextData, 
        int $errorCode = self::ERR_CODE_INTERNAL_SERVER_ERROR, 
        string $errorType=self::ERR_TYPE_ERROR, 
        Throwable $rootCause = NULL) {
            parent::__construct($message, 0, $rootCause);
            $this->contextData = $contextData;
            $this->errorCode = empty($errorCode) ? self::ERR_CODE_INTERNAL_SERVER_ERROR : $errorCode;
            $this->errorType = empty($errorType) ? self::ERR_TYPE_ERROR : $errorType;
    }

    public function getContextData() {
        return $this->contextData;
    }

    public function getErrorCode() {
        return $this->errorCode;
    }

    public function getErrorType() {
        return $this->errorType;
    }

    public function getDisplayMessage() {
        $contextInformation = '';
        if (!empty($this->contextData)) {
            $contextInformation = '|Context:' . json_encode($this->contextData);
        }
        return parent::getMessage() . $contextInformation;
    }
}

?>

