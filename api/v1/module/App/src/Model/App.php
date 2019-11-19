<?php

namespace App\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class App extends Entity
{

    //status for the apps
    const DELETED = 1;
    const IN_DRAFT = 2;
    const PREVIEW = 3;
    const PUBLISHED = 4;

    //types of apps
    const PRE_BUILT = 1;
    const MY_APP = 2;

    protected $data = array(
        'id' => 0,
        'name' => null,
        'uuid' => 0,
        'description' => null,
        'type' => null,
        'isdefault' => 0,
        'logo' => "default_app.png",
        'category' => null,
        'date_created' => null,
        'date_modified' => null,
        'created_by' => null,
        'modified_by' => null,
        'status' => 1,
        'start_options' => null
    );
    
    public function validate()
    {
        $dataArray = array("name", "type", "category","uuid","date_created","created_by","status");
        $this->validateWithParams($dataArray);
    }
}
