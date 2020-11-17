<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Role extends Entity
{
    protected $data = array(
        'id' => null,
        'name' => null,
        'account_id' => 0,
        'description' => null,
        'is_system_role' => null,
        'uuid' => null,
        'default_role' => 0
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
            'name', 'uuid'
        );
        $this->validateWithParams($required);
    }
}
