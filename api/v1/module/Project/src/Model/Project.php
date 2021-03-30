<?php

namespace Project\Model;

use Oxzion\Model\Entity;

class Project extends Entity
{
    protected $data = array(
        'id' => null,
        'uuid' => null,
        'name' => null,
        'account_id' => null,
        'manager_id' => null,
        'description' => null,
        'created_by' => null,
        'modified_by' => null,
        'date_created' => null,
        'date_modified' => null,
        'isdeleted' => null,
        'parent_id' => null
    );

    public function validate()
    {
        $dataArray = array("name", "description", "manager_id", "account_id");
        $this->validateWithParams($dataArray);
    }
}
