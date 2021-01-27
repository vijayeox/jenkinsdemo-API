<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\Type;

class Target extends Entity
{
    protected static $MODEL = [
        'id' =>                 ['type' => Type::INTEGER,   'readonly' => true,     'required' => false],
        'uuid' =>               ['type' => Type::UUID,      'readonly' => true,     'required' => false],
        'created_by' =>         ['type' => Type::INTEGER,   'readonly' => true,     'required' => false],
        'date_created' =>       ['type' => Type::TIMESTAMP, 'readonly' => true,     'required' => false],
        'account_id' =>         ['type' => Type::INTEGER,   'readonly' => true,     'required' => false],
        'type' =>               ['type' => Type::STRING,    'readonly' => false,    'required' => false],
        'period_type' =>        ['type' => Type::STRING,    'readonly' => false,    'required' => false],
        'red_limit' =>          ['type' => Type::FLOAT,     'readonly' => false,    'required' => false,    'value' => 0],
        'yellow_limit' =>       ['type' => Type::FLOAT,     'readonly' => false,    'required' => false,    'value' => 0],
        'green_limit' =>        ['type' => Type::FLOAT,     'readonly' => false,    'required' => false,    'value' => 0],
        'red_workflow_id' =>    ['type' => Type::INTEGER,   'readonly' => false,    'required' => false],
        'yellow_workflow_id' => ['type' => Type::INTEGER,   'readonly' => false,    'required' => false],
        'green_workflow_id' =>  ['type' => Type::INTEGER,   'readonly' => false,    'required' => false],
        'trigger_after' =>      ['type' => Type::INTEGER,   'readonly' => false,    'required' => false,    'value' => 0],
        'version' =>            ['type' => Type::INTEGER,   'readonly' => false,    'required' => false],
        'isdeleted' =>          ['type' => Type::BOOLEAN,   'readonly' => false,    'required' => false,    'value' => false],
    ];

    public function &getModel()
    {
        return self::$MODEL;
    }
}
