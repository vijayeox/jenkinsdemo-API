<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;

class UserCache extends Entity
{
    protected $data = array(
        'id'=>0 ,
        'app_id' => 0,
        'content' => null,
        'user_id' => 0
    );
    protected $attributes = array();

    public function validate()
    {
        
    }
}
