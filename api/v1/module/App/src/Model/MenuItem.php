<?php

namespace App\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class MenuItem extends Entity
{
    //types of Menu
    const PAGE = 1;
    const REPORT = 2;
    const FORM = 3;
    
    protected $data = array(
        'id' => 0,
        'uuid' => 0,
        'name' => null,
        'app_id' => 0,
        'parent_id' => 0,
        'page_id' => 0,
        'privilege_id' => NULL,
        'icon' => null,
        'sequence' => 0,
        'date_created' => null,
        'date_modified' => null,
        'created_by' => null,
        'modified_by' => null
    );
    
    public function validate()
    {
        $dataArray = array("name");
        $this->validateWithParams($dataArray);
    }
}
