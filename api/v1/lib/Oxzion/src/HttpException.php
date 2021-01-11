<?php

namespace Oxzion;

use Throwable;
use Oxzion\OxServiceException;

class HttpException extends OxServiceException {
    public function __construct(string $message, 
        int $errorCode = parent::ERR_CODE_NOT_FOUND) {
            parent::__construct($message, $contextData, empty($errorCode) ? parent::ERR_CODE_NOT_FOUND : $errorCode,parent::ERR_TYPE_ERROR , NULL);
    }
}

?>

