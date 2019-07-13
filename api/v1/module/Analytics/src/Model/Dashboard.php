<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Dashboard extends Entity
{
    protected $data = array(
        'id' => 0,
        'uuid' => null,
        'name' => null,
        'ispublic' => 0,
        'description' => null,
        'dashboard_type' => null,
        'created_by' => 0,
        'date_created' => null,
        'org_id' => 0
    );

    public function validate()
    {
        $dataArray = array("name","dashboard_type");
        $this->validateWithParams($dataArray);
    }
}