<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class ActivityInstance extends Entity
{
    protected $data = array(
        'id'=>0 ,
        'workflow_instance_id' => 0,
        'activity_instance_id' => 0,
        'activity_id' => 0,
        'start_data' => null,
        'completion_data' => null,
        'status' => 0,
        'modified_by' => null,
        'submitted_date' => null,
        'account_id' => 0,
        'start_date' => null,
        'isdeleted' => 0,
    );
    protected $attributes = array();

    public function validate()
    {
        $required = array('workflow_instance_id','activity_instance_id');
        $this->validateWithParams($required);
    }
}
