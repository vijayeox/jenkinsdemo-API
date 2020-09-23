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
        'name' => null,
        'account_id' => null,
        'icon' => null,
        'status' => 'Active',
        'in_game' => '0',
        'timezone' => 'Asia/Kolkata',
        'date_created' => null,
        'date_modified' => null,
        'created_by' => null,
        'modified_by' => null,
        'preferences' => null,
        'password_reset_code' => null,
        'password_reset_expiry_date' => null,
        'person_id' => null,
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
            'account_id',
            'status',
            'date_created',
            'created_by'
        );
        $this->validateWithParams($required);
    }
}
