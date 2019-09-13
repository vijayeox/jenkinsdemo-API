<?php
namespace Oxzion;

class AccessDeniedException extends \Exception{
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