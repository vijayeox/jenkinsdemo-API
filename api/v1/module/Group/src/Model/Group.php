<?php
namespace Group\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Group extends Entity {
    protected $data = array(
        'id' => NULL,
        'uuid' => NULL,
        'name'=> '',
        'parent_id' => NULL,
        'org_id' => NULL,
        'manager_id' => NULL,
        'description' => '',
        'status' => "Active",
        'date_created' => NULL,
        'date_modified' => NULL,
        'created_id' => NULL,
        'modified_id' => NULL
    );

    public function validate() {
        $dataArray = Array("name", "manager_id", "status", "date_created", "created_id");
        $this->validateWithParams($dataArray);
    }
}
