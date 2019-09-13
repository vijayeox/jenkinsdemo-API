<?php
namespace Oxzion\Model;

use Oxzion\ValidationException;
use Oxzion\Model\Entity;

class Field extends Entity{
	protected $data = array(
		'id'=>0,
		'app_id'=>0,
		'name'=>NULL,
		'text'=>NULL,
		'workflow_id'=>0,
		'data_type'=>NULL,
		'options'=>NULL,
		'constraints'=>NULL,
		'properties'=>NULL,
		'dependson'=>NULL,
		'required'=>NULL,
		'readonly'=>NULL,
		'expression'=>NULL,
		'validationtext'=>NULL,
		'helpertext'=>NULL,
		'sequence'=>NULL,
		'created_by'=>NULL,
		'modified_by'=>NULL,
		'date_created'=>NULL,
		'date_modified'=>NULL,
	);

	public function validate(){
        $required = array('app_id','name','data_type');
        $this->validateWithParams($required);
    }
}