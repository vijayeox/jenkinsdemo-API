<?php
namespace Oxzion;

use Exception;

class DelegateException extends \Exception{
    private $messageCode;
    public function __construct(string $message,string $messageCode,int $codeValue = 0,array $errors = array()){
        parent::__construct($message,$codeValue);
        $this->messageCode = $messageCode;
        $this->errors = $errors;
    }

    public function getMessageCode(){
        return $this->messageCode;
    }

    public function getErrors()
    {
        return $this->errors;
    } 
}
?>