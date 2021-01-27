<?php

namespace Project\Model;

use Oxzion\Model\Entity;

class Project extends Entity
{
    protected $data = array(
        'id' => NULL,
        'uuid' => NULL,
        'name' => NULL,
        'account_id' => NULL,
        'manager_id' => NULL,
        'description' => NULL,
        'created_by' => NULL,
        'modified_by' => NULL,
        'date_created' => NULL,
        'date_modified' => NULL,
        'isdeleted' => NULL,
        'parent_id' => null
    );

    public function validate()
    {
        $dataArray = array("name", "description", "manager_id", "account_id");
        $this->validateWithParams($dataArray);
    }
}
