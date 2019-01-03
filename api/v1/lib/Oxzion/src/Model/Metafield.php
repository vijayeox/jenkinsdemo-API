<?php
namespace Oxzion\Model;

use Bos\Model\Entity;
use Oxzion\ValidationException;

class Metafield extends Entity{
	protected $data = array(
		'id'=>0,
		'name'=>NULL,
		'org_id'=>NULL,
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
        $required = array('name','org_id','formid','data_type','sequence');
        $this->validateWithParams($required);
    }
}