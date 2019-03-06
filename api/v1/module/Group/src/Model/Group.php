<?php
namespace Group\Model;

use Bos\Model\Entity;
use Bos\ValidationException;

class Group extends Entity {
    protected $data = array(
        'id' => 0,
        'name'=> 0,
        'parent_id' => 0,
        'org_id' => 0,
        'manager_id' => 0,
        'description' => 0,
        'logo'=> 0,
        'cover_photo' => 0,
        'type' => 0,
        'status' => "Active",
        'date_created' => 0,
        'date_modified' => 0,
        'created_id' => 0,
        'modified_id' => 0
    );

    public function validate() {
        $dataArray = Array("name", "manager_id", "type", "status", "date_modified");
        $this->validateWithParams($dataArray);
    }
}
