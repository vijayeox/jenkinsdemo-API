<?php

namespace Oxzion;

use Throwable;
use Oxzion\OxServiceException;

class MultipleRowException extends OxServiceException {
    public function __construct(string $message, $contextData = NULL, 
        int $errorCode = parent::ERR_CODE_INTERNAL_SERVER_ERROR, 
        string $errorType = parent::ERR_TYPE_ERROR, 
        Throwable $rootCause = NULL) {
            parent::__construct($message, $contextData, 
                empty($errorCode) ? parent::ERR_CODE_INTERNAL_SERVER_ERROR : $errorCode, 
                empty($errorType) ? parent::ERR_TYPE_ERROR : $errorType, 
                $rootCause);
    }
}

?>

