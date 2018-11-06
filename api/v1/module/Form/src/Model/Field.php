<?php
namespace Form\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Field extends Entity{
	protected $data = array(
		'id'=>0,
		'uuid'=>NULL,
		'name'=>NULL,
		'orgid'=>NULL,
		'text'=>NULL,
		'formid'=>NULL,
		'data_type'=>NULL,
		'options'=>NULL,
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
        $required = array('name','orgid','formid','data_type','sequence');
        $this->validateWithParams($required);
    }
}