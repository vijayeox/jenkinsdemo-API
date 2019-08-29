<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class User extends Entity
{
    protected $data = array(
        'id' => null,
        'uuid' => null,
        'username' => null,
        'password' => null,
        'firstname' => null,
        'lastname' => null,
        'name' => null,
        'email' => null,
        'orgid' => null,
        'icon' => null,
        'status' => 'Active',
        'date_of_birth' => null,
        'designation' => null,
        'phone' => null,
        'address_id' => null,
        'gender' => null,
        'website' => null,
        'about' => null,
        'interest' => null,
        'hobbies' => null,
        'managerid' => null,
        'selfcontribute' => null,
        'contribute_percent' => null,
        'eid' => null,
        'signature' => null,
        'in_game' => '0',
        'timezone' => 'Asia/Kolkata',
        'date_created' => null,
        'date_modified' => null,
        'created_by' => null,
        'modified_by' => null,
        'date_of_join' => null,
        'preferences' => null,
        'password_reset_code' => null,
        'password_reset_expiry_date' => null,
    );

    public function __construct($data = array())
    {
        if ($data) {
            $this->exchangeArray($data);
        }
    }

    public function validate()
    {
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
