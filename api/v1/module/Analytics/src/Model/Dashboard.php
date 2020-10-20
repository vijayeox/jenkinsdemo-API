<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\Type;

class Dashboard extends Entity
{
    protected static $MODEL = [
        'id' => ['type' => Type::INTEGER, 'readonly' => true, 'required' => false],
        'uuid' => ['type' => Type::UUID, 'readonly' => true, 'required' => false],
        'name' => ['type' => Type::STRING, 'readonly' => false, 'required' => true],
        'ispublic' => ['type' => Type::BOOLEAN, 'readonly' => false, 'required' => false, 'value' => false],
        'description' => ['type' => Type::STRING, 'readonly' => false, 'required' => false],
        'dashboard_type' => ['type' => Type::STRING, 'readonly' => false, 'required' => true],
        'created_by' => ['type' => Type::INTEGER, 'readonly' => true, 'required' => false],
        'date_created' => ['type' => Type::TIMESTAMP, 'readonly' => true, 'required' => false],
        'org_id' => ['type' => Type::INTEGER, 'readonly' => false, 'required' => false],
        'isdeleted' => ['type' => Type::BOOLEAN, 'readonly' => false, 'required' => false, 'value' => false],
        'content' => ['type' => Type::STRING, 'readonly' => false, 'required' => false],
        'version' => ['type' => Type::INTEGER, 'readonly' => false, 'required' => false],
        'isdefault' => ['type' => Type::BOOLEAN, 'readonly' => false, 'required' => false, 'value' => false],
        'filter_configuration' => ['type' => Type::STRING, 'readonly' => false, 'required' => false],
        'export_configuration' => ['type' => Type::STRING, 'readonly' => false, 'required' => false],
    ];

    public function &getModel()
    {
        return self::$MODEL;
    }
}
