<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class DataSource extends Entity
{
    protected $data = array(
        'id' => 0,
        'uuid' => null,
        'name' => null,
        'type' => null,
        'connection_string' => null,
        'created_by' => 0,
        'date_created' => null,
        'org_id' =>null,
        'isdeleted' => 0
    );

    public function validate()
    {
        $dataArray = array("name", "type", "connection_string", "created_by");
        $this->validateWithParams($dataArray);
    }
}