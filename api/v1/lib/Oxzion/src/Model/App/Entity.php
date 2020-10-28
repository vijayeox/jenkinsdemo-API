<?php

namespace Oxzion\Model\App;

use Oxzion\Model\Entity as OxzionEntity;
use Oxzion\ValidationException;

class Entity extends OxzionEntity
{
    protected $data = array(
        'id' => 0,
        'uuid' => 0,
        'start_date_field' => null,
        'end_date_field' => null,
        'status_field' => null,
        'name' => null,
        'app_id' => 0,
        'assoc_id' => null,
        'description'=> null,
        'override_data' => false,
        'date_created' => null,
        'date_modified' => null,
        'created_by' => null,
        'modified_by' => null,
        "ryg_rule" => null,
        'isdeleted' => 0,
    );
    
    public function validate()
    {
        $dataArray = array("name","app_id");
        $this->validateWithParams($dataArray);
    }
}
