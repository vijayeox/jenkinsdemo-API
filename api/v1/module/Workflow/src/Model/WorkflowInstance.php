<?php

namespace Workflow\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class WorkflowInstance extends Entity {

    protected $data = array(
        'id' => 0,
        'workflow_id' => NULL,  
        'app_id' => 0,
        'org_id' => 0,
        'process_instance_id' => NULL,
        'status' => NULL,
        'data' => NULL,
        'date_created' => NULL,  
        'date_modified' => NULL,
        'created_by' => NULL,
        'modified_by' => NULL
    );
    
    public function validate(){
        $dataArray = Array("workflow_id", "app_id", "org_id", "process_instance_id", "date_created","created_by","status");
        $this->validateWithParams($dataArray);
    }
}