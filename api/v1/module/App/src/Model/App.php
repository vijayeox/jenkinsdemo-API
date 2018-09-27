<?php

namespace App\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class App extends Entity {

    protected $data = array(
        'id' => 0,  
        'name' => NULL,  
        'description' => NULL,  
        'sequence' => NULL,  
        'htmltext' => NULL,  
        'type' => NULL,  
        'viewtype' => NULL,  
        'customname' => NULL,  
        'logo' => NULL,  
        'email' => NULL,  
        'appcolor' => NULL,  
        'helppdf' => NULL,  
        'matrix_reference_name' => NULL,  
        'hidepivotgrid0' => NULL,  
        'hidepivotgrid1' => NULL,  
        'hidepivotgrid2' => NULL
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
        if(count($errors) > 0){
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}