<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class UserProfile extends Entity
{
    protected $data = array(
        'id' => null,
        'uuid' => null,
        'firstname' => null,
        'lastname' => null,
        'email' => null,
        'org_id' => null,
        'date_of_birth' => null,
        'phone' => null,
        'gender' => null,
        'signature' => null,
        'address_id' => null,
        'date_modified' => null,
        'created_by' => null,
        'modified_by' => null,
        'date_created' => null,
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
            'firstname',
            'lastname',
            'email',
            'date_of_birth',
        );
        $this->validateWithParams($required);
    }
}
