<?php

namespace App\Model;

use Bos\Model\Entity;
use Bos\ValidationException;

class WorkflowInstance extends Entity {

    protected $data = array(
        'id' => 0,
        'workflow_id' => NULL,  
        'app_id' => 0,
        'org_id' => 0,
        'status' => NULL,
        'data' => NULL,
        'date_created' => NULL,  
        'date_modified' => NULL,
        'created_by' => NULL,
        'modified_by' => NULL
    );
    
    public function validate(){
        $dataArray = Array("name","date_created","created_by","status");
        $this->validateWithParams($dataArray);
    }
}