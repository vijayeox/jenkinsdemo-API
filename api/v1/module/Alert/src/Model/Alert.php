<?php

namespace Alert\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Alert extends Entity
{
    protected $data = array(
        'id' => 0,
        'name' => null,
        'account_id' => null,
        'status' => null,
        'description' => null,
        'created_date' => 0,
        'created_id' => 0,
    );
    public function validate()
    {
        $errors = array();
        if ($this->data['name'] === null) {
            $errors["name"] = 'required';
        }
        if ($this->data['account_id'] === null) {
            $errors["account_id"] = 'required';
        }
        if ($this->data['status'] === null) {
            $errors["status"] = 'required';
        }
        if (count($errors) > 0) {
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}
