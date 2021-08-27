<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Subscriber extends Entity
{
    protected $data = array(
        'id'=>null ,
        'user_id' => null,
        'file_id' => null,
        'account_id' => null,
        'uuid' => null,
        'created_by' => null,
        'modified_by' => null,
        'date_created' => null,
        'date_modified' => null,
    );
    protected $attributes = array();

    public function validate()
    {
        $required = array('user_id', 'uuid');
        $this->validateWithParams($required);
    }
}
