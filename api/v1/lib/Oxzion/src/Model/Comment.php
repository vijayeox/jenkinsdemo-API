<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Comment extends Entity
{
    protected $data = array(
        'id'=>0 ,
        'org_id' => 0,
        'parent' => null,
        'text' => 0,
        'file_id' => 0,
        'created_by' => 0,
        'modified_by' => null,
        'date_created' => 0,
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
