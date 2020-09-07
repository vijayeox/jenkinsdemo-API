<?php

namespace Oxzion;

use Throwable;
use Oxzion\OxServiceException;

class InvalidApplicationArchiveException extends OxServiceException {
    public function __construct(string $message, $contextData = NULL, Throwable $rootCause = NULL) {
        parent::__construct(
            $message, 
            $contextData, 
            parent::ERR_CODE_NOT_ACCEPTABLE, 
            parent::ERR_TYPE_ERROR, 
            $rootCause
        );
    }
}

?>
