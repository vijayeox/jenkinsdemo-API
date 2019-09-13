<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class User extends Entity
{

    protected $data = array(
        'id' => NULL,
        'uuid' => NULL,
        'username' => NULL,
        'password' => NULL,
        'firstname' => NULL,
        'lastname' => NULL,
        'name' => NULL,
        'email' => NULL,
        'orgid' => NULL,
        'icon' => NULL,
        'status' => 'Active',
        'country' => NULL,
        'date_of_birth' => NULL,
        'designation' => NULL,
        'phone' => NULL,
        'address' => NULL,
        'gender' => NULL,
        'website' => NULL,
        'about' => NULL,
        'interest' => NULL,
        'hobbies' => NULL,
        'managerid' => NULL,
        'selfcontribute' => NULL,
        'contribute_percent' => NULL,
        'eid' => NULL,
        'signature' => NULL,
        'in_game' => '0',
        'timezone' => 'Asia/Kolkata',
        'date_created' => NULL,
        'date_modified' => NULL,
        'created_by' => NULL,
        'modified_by' => NULL,
        'date_of_join' => NULL,
        'preferences' => NULL,
        'password_reset_code' => NULL,
        'password_reset_expiry_date' => NULL,
    );

    public function __construct($data = array()) {
        if ($data) {
            $this->exchangeArray($data);
        }
    }

    public function validate() {
        $required = array(
            'username',
            'password',
            'firstname',
            'lastname',
            'email',
            'orgid',
            'status',
            'date_of_birth',
            'date_of_join',
            'date_created',
            'created_by'
        );
        $this->validateWithParams($required);
    }

}
?>