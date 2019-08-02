<?php

namespace Oxzion\Model;

class File extends Entity {

	protected $data = array(
		'id'=>0 ,
		'org_id' => NULL,
        'uuid' => NULL,
        'data' => NULL,
        'workflow_instance_id' =>NULL,
        'created_by' => NULL,
        'modified_by' => NULL,
        'date_created' => NULL,
        'date_modified' => NULL
    );
    protected $attributes = array();

    public function validate(){
        $required = array('uuid', 'org_id','data', 'created_by', 'date_created');
        $this->validateWithParams($required);
    }
}
