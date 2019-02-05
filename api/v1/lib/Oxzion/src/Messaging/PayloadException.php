<?php
namespace Oxzion\Messaging\Payloads;

class PayloadException extends \Exception{
    private $errors = array();

    public function addError($key, $value){
        $this->errors[$key] = $value;
    }

    public function setErrors(array $errors){
        $this->errors = $errors;
    }

    public function getErrors(){
        return $this->errors;
    }
}
?>