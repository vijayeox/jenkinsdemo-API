<?php

namespace App\Model;

use Bos\Model\Entity;
use Bos\ValidationException;

class App extends Entity {

    protected $data = array(
        'id' => 0,  
        'name' => NULL,  
        'uuid' => 0,
        'description' => NULL,
        'type' => NULL,  
        'logo' => "default_app.png",  
        'category' => NULL,
        'date_created' => NULL,  
        'date_modified' => NULL,
        'created_by' => NULL,
        'modified_by' => NULL,
        'status' => false
    );
    
    public function validate(){
        $dataArray = Array("name", "type", "category","uuid","date_created","created_by");
        $this->validateWithParams($dataArray);
    }
}