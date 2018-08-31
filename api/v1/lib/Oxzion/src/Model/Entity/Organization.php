<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;

class Organization extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'address' => NULL,
        'city' => NULL,
        'state' => NULL,
        'zip' => NULL,
        'logo' => NULL,
        'defaultgroupid' => NULL,
        'statusbox' => 'Matrix|MyKRA|StarPoints|Alerts',
        'labelfile' => NULL,
        'messagecount' => '200',
        'languagefile' => 'en',
        'orgtype' => '0',
        'flash_msg' => '0',
        'email' => 'Active',
        'themes' => '0',
        'formview' => '0',
        'assign_followuplimit' => '10',
        'insurelearn' => '0',
        'reset_password' => '0',
        'status' => 'Active',
    );

    public function __construct($data=null){
        $this->tablename = 'organizations';
        parent::__construct($data,$this);
    }
}