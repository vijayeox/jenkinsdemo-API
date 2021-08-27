<?php

namespace Oxzion;

use Throwable;
use Oxzion\OxServiceException;

class FileNotFoundException extends OxServiceException
{
    public function __construct(
        string $message,
        $contextData = null,
        int $errorCode = parent::ERR_CODE_NOT_FOUND,
        string $errorType = parent::ERR_TYPE_ERROR,
        Throwable $rootCause = null
    )
    {
        parent::__construct(
            $message,
            $contextData,
            empty($errorCode) ? parent::ERR_CODE_NOT_FOUND : $errorCode,
            empty($errorType) ? parent::ERR_TYPE_ERROR : $errorType,
            $rootCause
        );
    }
}

?>

