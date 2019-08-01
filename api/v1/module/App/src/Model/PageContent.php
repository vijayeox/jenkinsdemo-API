<?php

namespace App\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class PageContent extends Entity {
    
    protected $data = array(
        'id' => 0,
        'sequence' => 1,
        'content' => NULL,
        'page_id' => 0,
        'form_id' => 0,
        'type' => NULL
    );
    
    public function validate(){
        $dataArray = Array("type","page_id");
        $this->validateWithParams($dataArray);
    }
}