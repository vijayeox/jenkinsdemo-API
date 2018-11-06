<?php
namespace Form\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Form extends Entity{
	protected $data = array(
        'id'=>0,
        'uuid'=>NULL,
        'name'=>NULL,
        'description'=>NULL,
        'orgid'=>NULL,
        'statuslist'=>NULL,
        'template'=>NULL,
        'created_by'=>NULL,
        'modified_by'=>NULL,
        'date_created'=>NULL,
        'date_modified'=>NULL,
    );
    public function validate(){
        $required = array('name','orgid','statuslist');
        $this->validateWithParams($required);
    }
}