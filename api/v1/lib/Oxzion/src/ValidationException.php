<?php
namespace Oxzion;

class ValidationException extends \Exception
{
    private $errors = array();

    public function __construct($errors = null)
    {
        if (isset($errors) && !is_null($errors)) {
            $this->errors = $errors;
        }
    }

    public function addError($key, $value)
    {
        $this->errors[$key] = $value;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
