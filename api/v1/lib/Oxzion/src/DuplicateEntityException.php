<?php

namespace Oxzion;

use Throwable;
use Oxzion\OxServiceException;

class DuplicateEntityException extends OxServiceException
{
    public function __construct(string $message, $contextData = null, Throwable $rootCause = null)
    {
        parent::__construct(
            $message,
            $contextData,
            parent::ERR_CODE_CONFLICT,
            parent::ERR_TYPE_ERROR,
            $rootCause
        );
    }
}

?>

