<?php
namespace Group\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Group extends Entity {
    protected $data = array(
        'id' => 0,
        'uuid' => NULL,
        'name'=> 0,
        'parent_id' => 0,
        'org_id' => 0,
        'manager_id' => 0,
        'description' => 0,
        'status' => "Active",
        'date_created' => 0,
        'date_modified' => 0,
        'created_id' => 0,
        'modified_id' => 0
    );

    public function validate() {
        $dataArray = Array("name", "manager_id", "status", "date_created", "created_id");
        $this->validateWithParams($dataArray);
    }
}
