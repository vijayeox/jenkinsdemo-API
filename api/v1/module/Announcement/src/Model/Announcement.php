<?php

namespace Announcement\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Announcement extends Entity {

    protected $data = array(
        'id' => 0,
        'name' => NULL,
        'org_id' => NULL,
        'status' => NULL,
        'description' => NULL,
        'start_date' => NULL,
        'end_date' => NULL,
        'created_date' => 0,
        'created_id' => 0,
        'media_type' => NULL,
        'media_location' => NULL
    );

    public function validate(){
        $errors = array();
        if($this->data['name'] === null){
            $errors["name"] = 'required';
        }
        if($this->data['org_id'] === null) {
            $errors["org_id"] = 'required';   
        }
        if($this->data['status'] === null) {
            $errors["status"] = 'required';  
        }
        if($this->data['start_date'] === null) {
            $errors["start_date"] = 'required';   
        }
        if($this->data['end_date'] === null) {
            $errors["end_date"] = 'required';   
        }
        if($this->data['media_location'] === null) {
            $errors["media_location"] = 'required';   
        }
        if(count($errors) > 0){
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}
