<?php

namespace Attachment\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Attachment extends Entity
{
    protected $data = array(
        'id' => 0,
        'file_name' => null,
        'extension' => null,
        'uuid' => null,
        'type' => null,
        'path' => null,
        'created_id' => null,
        'created_date' => null,
        'account_id' => null,
    );

    public function validate()
    {
        $errors = array();
        if ($this->data['file_name'] === null) {
            $errors["file_name"] = 'required';
        }
        if ($this->data['account_id'] === null) {
            $errors["account_id"] = 'required';
        }
        if ($this->data['type'] === null) {
            $errors["type"] = 'required';
        }
        if (count($errors) > 0) {
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}
