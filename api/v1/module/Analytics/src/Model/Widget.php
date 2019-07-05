<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Widget extends Entity
{
    protected $data = array(
        'id' => 0,
        'uuid' => null,
        'query_id' => 0,
        'visualization_id' => 0,
        'ispublic' => 0,
        'created_by' => 0,
        'date_created' => null,
        'org_id' => 0
    );

    public function validate()
    {
        $dataArray = array("query_id","visualization_id","ispublic");
        $this->validateWithParams($dataArray);
    }
}