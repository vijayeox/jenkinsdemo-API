<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Query extends Entity
{
    protected $data = array(
        'id' => 0,
        'uuid' => null,
        'name' => null,
        'datasource_id' => null,
        'query_json' => null,
        'ispublic' => 0,
        'created_by' => 0,
        'date_created' => null
    );

    public function validate()
    {
        $dataArray = array("name","datasource_id","query_json","created_by");
        $this->validateWithParams($dataArray);
    }
}