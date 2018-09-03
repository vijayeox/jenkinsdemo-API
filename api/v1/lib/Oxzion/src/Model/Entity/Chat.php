<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;

class Chat extends Entity{

    protected $data = array(
        'id' => NULL,
        'from' => NULL,
        'to' => NULL,
        'message' => NULL,
        'sent' => '0',
        'read' => '0',
        'direction' => '0',
    );

    public function __construct($data=null){
        $this->tablename = 'cometchat';
        parent::__construct($data,$this);
    }
    
}