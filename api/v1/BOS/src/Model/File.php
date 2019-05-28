<?php

namespace Bos\Model;

class File extends Entity {

	protected $data = array(
		'id'=>0 ,
		'name' => NULL,
		'org_id' => NULL,
		'form_id' => NULL,
        'workflow_instance_id' =>NULL,
        'created_by' => NULL,
        'modified_by' => NULL,
        'date_created' => NULL,
        'date_modified' => NULL
    );
    protected $attributes = array();

    public function validate(){
        $required = array('name','org_id','form_id','workflow_instance_id');
        $this->validateWithParams($required);
    }
}
