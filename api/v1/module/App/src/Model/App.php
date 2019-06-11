<?php

namespace App\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class App extends Entity {

    //status for the apps
    const DELETED = 1;
    const IN_DRAFT = 2;
    const PREVIEW = 3;
    const PUBLISHED = 4;

    //types of apps
    const PRE_BUILT = 1;
    const MY_APP = 2;

    protected $data = array(
        'id' => 0,  
        'name' => NULL,  
        'uuid' => 0,
        'description' => NULL,
        'type' => NULL,  
        'isdefault' => NULL,
        'logo' => "default_app.png",  
        'category' => NULL,
        'date_created' => NULL,  
        'date_modified' => NULL,
        'created_by' => NULL,
        'modified_by' => NULL,
        'status' => 1,
        'start_options' => NULL
    );
    
    public function validate(){
        $dataArray = Array("name", "type", "category","uuid","date_created","created_by","status");
        $this->validateWithParams($dataArray);
    }
}