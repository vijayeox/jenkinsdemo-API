<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;

class Alert extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'text' => NULL,
        'type' => 'system',
        'orgid' => NULL,
        'disabled' => '0',
        'enddate' => NULL,
        'creatorid' => NULL,
        'startdate' => NULL,
        'socialstatus' => NULL,
    );
    public function __construct($data=null){
        $this->tablename = 'alerts';
        parent::__construct($data,$this);
    }
}