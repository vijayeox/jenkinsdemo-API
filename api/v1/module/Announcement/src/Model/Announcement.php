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
        if($data['name'] === null){
            $errors["name"] = 'required';
        }
        if($data['org_id'] === null) {
            $errors["org_id"] = 'required';   
        }
        if($data['status'] === null) {
            $errors["status"] = 'required';   
        }
        if($data['start_date'] === null) {
            $errors["start_date"] = 'required';   
        }
        if($data['end_date'] === null) {
            $errors["end_date"] = 'required';   
        }

        if(count($errors) > 0){
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}
