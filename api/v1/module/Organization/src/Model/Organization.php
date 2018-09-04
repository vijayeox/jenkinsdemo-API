<?php
namespace Organization\Model;
use Oxzion\Model\Entity;

class Organization extends Entity{

    protected $data = array(
        'id' => NULL,
        'name' => NULL,
        'address' => "NA",
        'city' => "NA",
        'state' => "NA",
        'zip' => "NA",
        'logo' => "NA",
        'defaultgroupid' => 0,
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
}