<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Email extends Entity
{
    public function __construct()
    {
        $this->data = array(
            'id' => null,
            'userid'=> null,
            'email' => null,
            'password' => null,
            'host' => null,
            'token' => null,
            'isdefault'=> null,
        );
    }

    public function validate()
    {
        try {
            $dataArray = array("email", "host");
            $this->validateWithParams($dataArray);
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
        } finally {
            if (($this->data['password'] === null || $this->data['password'] === "") &&
                ($this->data['token'] === null || $this->data['token'] === "")
                || empty($this->data)) {
                if (!isset($errors)) {
                    $errors = array();
                    $e = new ValidationException();
                }
                $errors['password'] = 'required';
                $e->setErrors($errors);
                throw $e;
            }
        }
    }
}
