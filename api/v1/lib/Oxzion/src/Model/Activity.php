<?php
namespace Oxzion\Model;

use Oxzion\ValidationException;
use Oxzion\Model\Entity;

class Activity extends Entity{
	protected $data = array(
        'id'=>0,
        'name'=>NULL,
        'app_id'=>0,
        'workflow_id'=>0,
        'created_by'=>NULL,
        'modified_by'=>NULL,
        'date_created'=>NULL,
        'date_modified'=>NULL,
    );
    public function validate(){
        $required = array('name');
        $this->validateWithParams($required);
    }
}