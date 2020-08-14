<?php

namespace Oxzion;

use Throwable;
use Oxzion\OxServiceException;

class DuplicateEntityException extends OxServiceException {
    public function __construct(string $message, $contextData = NULL, 
        int $errorCode = parent::ERR_CODE_CONFLICT, 
        string $errorType = parent::ERR_TYPE_ERROR, 
        Throwable $rootCause = NULL) {
            parent::__construct($message, $contextData, 
                empty($errorCode) ? parent::ERR_CODE_CONFLICT : $errorCode, 
                empty($errorType) ? parent::ERR_TYPE_ERROR : $errorType, 
                $rootCause);
    }
}

?>

