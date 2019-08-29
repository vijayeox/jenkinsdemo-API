<?php

namespace App\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class PageContent extends Entity
{
    protected $data = array(
        'id' => 0,
        'sequence' => 1,
        'content' => null,
        'page_id' => null,
        'form_id' => null,
        'type' => null
    );
    
    public function validate()
    {
        $dataArray = array("type","page_id","sequence");
        $this->validateWithParams($dataArray);
    }
}
