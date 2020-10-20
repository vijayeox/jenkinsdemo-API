<?php

namespace Oxzion;

use Throwable;
use Oxzion\OxServiceException;

class InvalidPropertyValueException extends OxServiceException {
    public function __construct(string $message, $contextData = NULL, 
        int $errorCode = parent::ERR_CODE_NOT_ACCEPTABLE, 
        string $errorType = parent::ERR_TYPE_ERROR, 
        Throwable $rootCause = NULL) {
            parent::__construct($message, $contextData, 
                empty($errorCode) ? parent::ERR_CODE_NOT_ACCEPTABLE : $errorCode, 
                empty($errorType) ? parent::ERR_TYPE_ERROR : $errorType, 
                $rootCause);
    }
}

?>
