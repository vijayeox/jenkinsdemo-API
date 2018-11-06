<?php
namespace Form\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Metafield extends Entity{
	protected $data = array(
		'id'=>0,
		'name'=>NULL,
		'orgid'=>NULL,
		'text'=>NULL,
		'data_type'=>NULL,
		'options'=>NULL,
		'expression'=>NULL,
		'validationtext'=>NULL,
		'helpertext'=>NULL,
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