<?php

namespace SplashPage\Model;

use Bos\Model\Entity;
use Bos\ValidationException;

class SplashPage extends Entity {

    protected $data = array(
        'id' => 0,
        // 'name' => NULL,
        'org_id' => NULL,
        'content' => NULL,
        'enabled' => NULL
        // 'status' => NULL,
        // 'description' => NULL,
        // 'start_date' => NULL,
        // 'end_date' => NULL,
        // 'created_date' => 0,
        // 'created_id' => 0,
        // 'media_type' => NULL,
        // 'media' => NULL
    );

    public function validate(){
        $required = array('org_id','content');
        $this->validateWithParams($required);
    }
}
