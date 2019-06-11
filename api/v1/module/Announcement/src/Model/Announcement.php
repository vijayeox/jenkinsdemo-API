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
        'media' => NULL
    );

    public function validate(){
        $required = array('name','org_id','status','start_date','end_date','media');
        $this->validateWithParams($required);
    }
}
