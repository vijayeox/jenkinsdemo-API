<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\Type;

class Target extends Entity {
    protected static $MODEL = [
        'id' =>                 ['type' => Type::INTEGER,   'readonly' => TRUE ,    'required' => FALSE],
        'uuid' =>               ['type' => Type::UUID,      'readonly' => TRUE ,    'required' => FALSE],
        'created_by' =>         ['type' => Type::INTEGER,   'readonly' => TRUE ,    'required' => FALSE],
        'date_created' =>       ['type' => Type::TIMESTAMP, 'readonly' => TRUE ,    'required' => FALSE],
        'org_id' =>             ['type' => Type::INTEGER,   'readonly' => TRUE ,    'required' => FALSE],
        'type' =>               ['type' => Type::STRING,    'readonly' => FALSE ,   'required' => FALSE],
        'period_type' =>        ['type' => Type::STRING,    'readonly' => FALSE ,   'required' => FALSE],
        'red_limit' =>          ['type' => Type::FLOAT,     'readonly' => FALSE ,   'required' => FALSE, 'value' => 0],
        'yellow_limit' =>       ['type' => Type::FLOAT,     'readonly' => FALSE ,   'required' => FALSE, 'value' => 0],
        'green_limit' =>        ['type' => Type::FLOAT,     'readonly' => FALSE ,   'required' => FALSE, 'value' => 0],
        'red_workflow_id' =>    ['type' => Type::INTEGER,   'readonly' => FALSE ,   'required' => FALSE],
        'yellow_workflow_id' => ['type' => Type::INTEGER,   'readonly' => FALSE ,   'required' => FALSE],
        'green_workflow_id' =>  ['type' => Type::INTEGER,   'readonly' => FALSE ,   'required' => FALSE],
        'trigger_after' =>      ['type' => Type::INTEGER,   'readonly' => FALSE,    'required' => FALSE, 'value' => 0],
        'version' =>            ['type' => Type::INTEGER,   'readonly' => FALSE,    'required' => FALSE],
        'isdeleted' =>          ['type' => Type::BOOLEAN,   'readonly' => FALSE ,   'required' => FALSE, 'value' => FALSE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }
}