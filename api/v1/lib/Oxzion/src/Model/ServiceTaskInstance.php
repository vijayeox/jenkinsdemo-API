<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class ServiceTaskInstance extends Entity
{
    protected $data = array(
        'id'=>0 ,
        'name' => 0,
        'workflow_instance_id' => 0,
        'task_id' => 0,
        'start_data' => null,
        'completion_data' => null,
        'date_executed' => 0,
        'file_id' => null
    );
    protected $attributes = array();

    public function validate()
    {
        $required = array('workflow_instance_id','task_id');
        $this->validateWithParams($required);
    }
}
