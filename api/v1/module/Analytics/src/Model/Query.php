<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\Type;

class Query extends Entity
{
    protected static $MODEL = [
        'id' => ['type' => Type::INTEGER, 'readonly' => true, 'required' => false],
        'uuid' => ['type' => Type::UUID, 'readonly' => true, 'required' => false],
        'name' => ['type' => Type::STRING, 'readonly' => false, 'required' => true],
        'datasource_id' => ['type' => Type::INTEGER, 'readonly' => false, 'required' => true],
        'configuration' => ['type' => Type::STRING, 'readonly' => false, 'required' => true],
        'ispublic' => ['type' => Type::BOOLEAN, 'readonly' => false, 'required' => false, 'value' => false],
        'created_by' => ['type' => Type::INTEGER, 'readonly' => true, 'required' => false],
        'date_created' => ['type' => Type::TIMESTAMP, 'readonly' => true, 'required' => false],
        'org_id' => ['type' => Type::INTEGER, 'readonly' => true, 'required' => false],
        'isdeleted' => ['type' => Type::BOOLEAN, 'readonly' => false, 'required' => false, 'value' => false],
        'version' => ['type' => Type::INTEGER, 'readonly' => false, 'required' => false],
    ];

    protected function &getModel()
    {
        return self::$MODEL;
    }
}
