<?php
namespace Oxzion\Model;

use Bos\ValidationException;
use Bos\Model\Entity;

class Form extends Entity{
	protected $data = array(
        'id'=>0,
        'app_id'=>0,
        'workflow_id'=>0,
        'name'=>NULL,
        'description'=>NULL,
        'task_id'=>NULL,
        'process_id'=>NULL,
        'statuslist'=>NULL,
        'template'=>NULL,
        'created_by'=>NULL,
        'modified_by'=>NULL,
        'date_created'=>NULL,
        'date_modified'=>NULL,
    );
    public function validate(){
        $required = array('app_id','name');
        $this->validateWithParams($required);
    }
}