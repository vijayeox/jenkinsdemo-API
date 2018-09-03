<?php

namespace Oxzion\Model\Entity;

use Oxzion\Model\Entity;

class Bookmark extends Entity{

    protected $data = array(
        'id' => NULL,
        'avatarid' => NULL,
        'groupid' => NULL,
        'orgid' => NULL,
        'name' => NULL,
        'type' => NULL,
        'text' => NULL,
        'url' => NULL,
    );
    public function __construct($data=null){
        $this->tablename = 'links';
        parent::__construct($data,$this);
    }
}