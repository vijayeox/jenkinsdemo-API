<?php

namespace Prehire\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class Prehire extends Entity
{
    protected static $MODEL = [
        'id' =>                     ['type' => Type::INTEGER,   'readonly' => true , 'required' => false],
        'uuid' =>                   ['type' => Type::UUID,      'readonly' => false, 'required' => false],
        'user_id' =>                ['type' => Type::INTEGER,   'readonly' => false, 'required' => true],
        'request_type' =>           ['type' => Type::STRING,   'readonly' => false, 'required' => true],
        'request' =>                ['type' => Type::STRING,   'readonly' => false, 'required' => true],
        'implementation' =>         ['type' => Type::STRING,    'readonly' => false, 'required' => true],
        'date_created' =>           ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'date_modified' =>          ['type' => Type::TIMESTAMP, 'readonly' => false,  'required' => false]
    ];

    public function &getModel()
    {
        return self::$MODEL;
    }
}
