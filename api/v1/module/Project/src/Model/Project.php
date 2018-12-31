<?php

namespace Project\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Project extends Entity {

    protected $data = array(
        'id' => 0,
        'name'=> 0,
        'org_id' => 0,
        'description' => 0,
        'created_by' => 0,
        'modified_by' => 0,
        'date_created' => 0,
        'date_modified' => 0,
        'isdeleted'=>0
    );

    public function validate() {
        $dataArray = array("name");
        $this->validateWithParams($dataArray);
    }
        
}
