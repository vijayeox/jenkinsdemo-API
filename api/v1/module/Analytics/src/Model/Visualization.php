<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Visualization extends Entity
{
    protected $data = array(
        'id' => 0,
        'uuid' => null,
        'type' => null,
        'created_by' => 0,
        'date_created' => null,
        'org_id' => 0
    );

    public function validate()
    {
        $dataArray = array("type","created_by","date_created","org_id","uuid");
        $this->validateWithParams($dataArray);
    }
}