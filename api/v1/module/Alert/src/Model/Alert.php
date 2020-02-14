<?php

namespace Alert\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Alert extends Entity
{
    protected $data = array(
        'id' => 0,
        'name' => null,
        'org_id' => null,
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
        if ($this->data['org_id'] === null) {
            $errors["org_id"] = 'required';
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
