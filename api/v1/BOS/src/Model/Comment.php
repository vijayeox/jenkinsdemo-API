<?php

namespace Bos\Model;

class Comment extends Entity {

	protected $data = array(
		'id'=>0 ,
		'org_id' => 0,
		'parent' => NULL,
        'text' => 0,
        'file_id' => 0,
        'created_by' => 0,
        'modified_by' => NULL,
        'date_created' => 0,
        'date_modified' => NULL,
        'isdeleted' => 0,
    );
    protected $attributes = array();

    public function validate() {
        $required = array('text');
        $this->validateWithParams($required);
    }
}