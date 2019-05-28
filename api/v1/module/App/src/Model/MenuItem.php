<?php

namespace App\Model;

use Bos\Model\Entity;
use Bos\ValidationException;

class MenuItem extends Entity {
    //types of Menu
    const PAGE = 1;
    const REPORT = 2;
    const FORM = 3;
    
    protected $data = array(
        'id' => 0,  
        'name' => NULL,  
        'app_id' => 0,
        'parent_id' => NULL,
        'icon' => NULL,
        'date_created' => NULL,  
        'date_modified' => NULL,
        'created_by' => NULL,
        'modified_by' => NULL
    );
    
    public function validate(){
        $dataArray = Array("name", "type");
        $this->validateWithParams($dataArray);
    }
}