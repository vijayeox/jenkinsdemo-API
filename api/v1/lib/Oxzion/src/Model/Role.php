<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class Role extends Entity
{
    protected $data = array(
        'id' => null,
        'name' => null,
        'org_id' => 0,
        'description' => null,
        'is_system_role' => null,
        'uuid' => null
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
