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
        'id' => null,
        'uuid' => null,
        'name' => null,
        'app_id' => null,
        'parent_id' => null,
        'page_id' => null,
        'privilege_name' => null,
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
