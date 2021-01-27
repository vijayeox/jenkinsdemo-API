<?php

namespace Announcement\Model;

use Oxzion\Model\Entity;

class Announcement extends Entity
{
    protected $data = array(
        'id' => 0,
        'uuid' => null,
        'name' => null,
        'account_id' => null,
        'status' => null,
        'description' => null,
        'link' => null,
        'start_date' => null,
        'end_date' => null,
        'created_date' => 0,
        'created_id' => 0,
        'media_type' => null,
        'media' => null,
        'type' => null
    );

    public function validate()
    {
        $required = array('name', 'status', 'start_date', 'end_date', 'media','type');
        $this->validateWithParams($required);
    }
}
