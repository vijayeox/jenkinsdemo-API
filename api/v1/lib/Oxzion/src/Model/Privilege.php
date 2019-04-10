<?php

namespace Privilege\Model;

use Bos\Model\Entity;
use Bos\ValidationException;

class Privilege extends Entity
{
    protected $data = array(
        'id' => 0,
        'name' => null,
        'permission_allowed' => 0,
        'org_id' => null,
        'app_id' => null
    );

    public function validate()
    {
        $dataArray = array("permission_allowed");
        $this->validateWithParams($dataArray);
    }
}