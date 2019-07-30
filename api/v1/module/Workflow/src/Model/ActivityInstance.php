<?php

namespace Workflow\Model;
use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class ActivityInstance extends Entity {

	protected $data = array(
		'id'=>0 ,
		'workflow_instance_id' => 0,
		'activity_instance_id' => NULL,
        'assignee' => 0,
        'form_id' => 0,
        'status' => 0,
    );
    protected $attributes = array();

    public function validate() {
        $required = array('workflow_instance_id','assignee','activity_instance_id','form_id');
        $this->validateWithParams($required);
    }
}