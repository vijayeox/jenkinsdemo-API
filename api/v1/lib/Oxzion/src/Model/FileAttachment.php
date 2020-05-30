<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class FileAttachment extends Entity
{
    protected $data = array(
        'id' => 0,
        'name' => null,
        'originalName' => null,
        'extension' => null,
        'uuid' => null,
        'type' => null,
        'path' => null,
        'url' => null,
        'created_id' => null,
        'created_date' => null,
        'org_id' => null,
    );

    public function validate()
    {
        $required = array('name', 'uuid', 'org_id');
        $this->validateWithParams($required);
    }
}
