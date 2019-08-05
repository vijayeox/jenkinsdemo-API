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
        'page_id' => 0,
        'form_id' => 0,
        'type' => null
    );
    
    public function validate()
    {
        $dataArray = array("type","page_id");
        $this->validateWithParams($dataArray);
    }
}
