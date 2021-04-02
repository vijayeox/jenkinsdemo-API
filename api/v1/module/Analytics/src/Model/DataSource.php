<?php

namespace Analytics\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class DataSource extends Entity
{
    protected static $MODEL = [
        'id' =>             ['type' => Type::INTEGER,   'readonly' => true ,    'required' => false],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => true,     'required' => false],
        'name' =>           ['type' => Type::STRING,    'readonly' => false,    'required' => true],
        'type' =>           ['type' => Type::STRING,    'readonly' => false,    'required' => true],
        'configuration' =>  ['type' => Type::STRING,    'readonly' => false,    'required' => true],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => true,     'required' => false],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => true,     'required' => false],
        'account_id' =>     ['type' => Type::INTEGER,   'readonly' => false,    'required' => false],
        'isdeleted' =>      ['type' => Type::BOOLEAN,   'readonly' => false,    'required' => false, 'value' => false],
        'version' =>        ['type' => Type::INTEGER,   'readonly' => false,    'required' => false]
    ];

    public function &getModel()
    {
        return self::$MODEL;
    }
}
