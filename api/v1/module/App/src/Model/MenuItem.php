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
        'id' => NULL,
        'uuid' => NULL,
        'name' => null,
        'app_id' => NULL,
        'parent_id' => NULL,
        'page_id' => NULL,
        'privilege_name' => NULL,
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
