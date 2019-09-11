<?php

namespace PaymentGateway\Model;

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
        'created_date' => null,
        'created_id' => null,
        'modified_date' => null,
        'modified_id' => null,
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
