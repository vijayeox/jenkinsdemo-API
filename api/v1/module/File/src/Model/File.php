<?php

namespace File\Model;

use Oxzion\Model\Entity;

class File extends Entity {

	protected $data = array(
		'id'=>0 ,
		'name' => NULL,
		'orgid' => NULL,
		'formid' => NULL,
        'status' =>NULL,
		'created_by' => NULL,
		'modified_by' => NULL,
		'date_created' => NULL,
		'date_modified' => NULL
	);
    protected $attributes = array();

    public function validate(){
        $required = array('name','orgid','formid','status');
        $this->validateWithParams($required);
    }
}
