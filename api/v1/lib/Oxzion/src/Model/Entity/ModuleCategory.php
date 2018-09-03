<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;

class AppCategory extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'color' => NULL,
        'sequence' => NULL,
        'orgid' => NULL,
    );

    public function __construct($data=null){
        $this->tablename = 'modulecategories';
        parent::__construct($data,$this);
    }
}