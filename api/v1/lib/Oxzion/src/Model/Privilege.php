<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Privilege extends Entity
{
    protected $data = array(
        'id' => null,
        'name' => null,
        'permission_allowed' => 0,
        'app_id' => null
    );

    public function __construct($data = array())
    {
        if ($data) {
            $this->exchangeArray($data);
        }
    }

    public function validate()
    {
        $required = array(
            "permission_allowed"
        );
        $this->validateWithParams($required);
    }
}
