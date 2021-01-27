<?php
namespace Oxzion;

class AccessDeniedException extends OxServiceException
{
    public function __construct($message){
        parent::__construct($message, NULL, OxServiceException::ERR_CODE_UNAUTHORIZED);
    }
}
