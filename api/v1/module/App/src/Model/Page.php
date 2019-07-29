<?php

namespace App\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Page extends Entity {
    
    protected $data = array(
        'id' => 0,
        'uuid' => 0,
        'name' => NULL,
        'app_id' => 0,
        'form_id' => 0,
        'text' => NULL,
        'description'=> NULL,
        'date_created' => NULL,
        'date_modified' => NULL,
        'created_by' => NULL,
        'modified_by' => NULL
    );
    
    public function validate(){
        $dataArray = Array("name","app_id");
        $this->validateWithParams($dataArray);
    }
}