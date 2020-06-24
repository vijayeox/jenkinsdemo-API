<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Comment extends Entity
{
    protected $data = array(
        'id'=>NULL ,
        'org_id' => NULL,
        'parent' => null,
        'uuid' => NULL,
        'text' => NULL,
        'file_id' => NULL,
        'created_by' => NULL,
        'modified_by' => null,
        'date_created' => NULL,
        'date_modified' => null,
        'isdeleted' => 0,
    );
    protected $attributes = array();

    public function validate()
    {
        $required = array('text');
        $this->validateWithParams($required);
    }
}
