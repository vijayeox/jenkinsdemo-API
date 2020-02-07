<?php
namespace Oxzion;

class InvalidParameterException extends \Exception
{
    
    public function __construct(string $message,int $codeValue = 0){
        parent::__construct($message,$codeValue);
    }

}
