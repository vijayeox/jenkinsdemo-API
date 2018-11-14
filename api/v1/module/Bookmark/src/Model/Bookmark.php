<?php

namespace Bookmark\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Bookmark extends Entity {

    protected $data = array(
        'id' => 0,
        'name' => NULL,
        'org_id' => NULL,
        'avatar_id' => NULL,
        'url' => null
    );

    public function validate(){
        $errors = array();
        $required = array(
            'name',
            'url'
        );
        foreach ($required as $field) {
            if($this->data[$field] === null){
                $errors[$field] = 'required';
            }
        }
        if(count($errors) > 0){
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}