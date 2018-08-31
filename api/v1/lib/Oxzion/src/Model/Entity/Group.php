<?php

namespace Oxzion\Model\Entity;
use Oxzion\Model\Entity;

class Group extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'parentid' => NULL,
        'orgid' => NULL,
        'managerid' => NULL,
        'moduleid' => NULL,
        'disablechat' => NULL,
        'assigntomanager' => '0',
        'description' => NULL,
        'logo' => NULL,
        'coverphoto' => NULL,
        'power_users' => '0',
        'type' => '0',
        'hiddentopicons' => NULL,
        'hidetiles' => NULL,
        'hidewall' => NULL,
        'hideannouncement' => NULL,
        'hideleaderboard' => NULL,
        'hiddenmessage' => NULL,
        'hiddenassignment' => NULL,
        'hiddenfollowup' => NULL,
        'hiddencreate' => NULL,
        'hiddensearch' => NULL,
        'hiddengroup' => NULL,
        'status' => 'Active',
    );

    public function __construct($data=null){
        $this->tablename = 'groups';
        parent::__construct($data,$this);
    }
}