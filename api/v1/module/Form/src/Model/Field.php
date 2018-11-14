<?php
namespace Form\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Field extends Entity{
	protected $data = array(
		'id'=>0,
		'uuid'=>NULL,
        'app_id'=>0,
		'name'=>NULL,
		'org_id'=>NULL,
		'text'=>NULL,
		'form_id'=>NULL,
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
        $required = array('app_id','name','org_id','form_id','data_type','sequence');
        $this->validateWithParams($required);
    }
}