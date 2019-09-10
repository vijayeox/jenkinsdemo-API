<?php

namespace Payment\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Payment extends Entity
{
    protected $data = array(
        'id' => 0,
        'app_id' => null,
        'payment_client' => null,
        'api_url' => null,
        'server_instance_name' => null,
        'payment_config' => null,
        'created_date' => 0,
        'created_id' => 0,
        'modified_date' => 0,
        'modified_id' => 0,
    );

    public function validate()
    {
        $errors = array();
        if ($this->data['app_id'] === null) {
            $errors["app_id"] = 'required';
        }
        if ($this->data['payment_client'] === null) {
            $errors["payment_client"] = 'required';
        }
        if ($this->data['api_url'] === null) {
            $errors["api_url"] = 'required';
        }
        if (count($errors) > 0) {
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}
