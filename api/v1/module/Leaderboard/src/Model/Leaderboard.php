<?php

namespace Leaderboard\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Leaderboard extends Entity {

    protected $data = array(
        'id' => 0,  
        'name' => NULL,  
        'uuid' => NULL,  
        'description' => NULL,
        'type' => NULL,  
        'logo' => NULL,  
        'date_created' => NULL,  
        'date_modified' => NULL
    );
    
    public function validate(){
        $errors = array();
        if($this->data['name'] === null){
            $errors["name"] = 'required';
        }
        if($this->data['uuid'] === null) {
            $errors["uuid"] = 'required';   
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