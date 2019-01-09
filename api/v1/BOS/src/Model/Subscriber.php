<?php

namespace Bos\Model;

class Subscriber extends Entity {

	protected $data = array(
		'id'=>0 ,
		'user_id' => 0,
        'file_id' => 0,
        'org_id' => 0,
        'created_by' => 0,
        'modified_by' => NULL,
        'date_created' => 0,
        'date_modified' => NULL,
    );
    protected $attributes = array();

    public function validate() {
        $required = array('user_id');
        $this->validateWithParams($required);
    }
}