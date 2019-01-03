<?php

namespace Attachment\Model;

use Bos\Model\Entity;
use Oxzion\ValidationException;

class Attachment extends Entity {

    protected $data = array(
        'id' => 0,
        'file_name' => NULL,
        'extension' => NULL,
        'uuid' => NULL,
        'type' => NULL,
        'path' => NULL,
        'created_id' => NULL,
        'created_date' => NULL,
        'org_id'=>NULL
    );

    public function validate(){
        $errors = array();
        if($this->data['file_name'] === null){
            $errors["file_name"] = 'required';
        }
        if($this->data['org_id'] === null) {
            $errors["org_id"] = 'required';   
        }
        if($this->data['type'] === null) {
            $errors["type"] = 'required';
        }
        if(count($errors) > 0){
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}
