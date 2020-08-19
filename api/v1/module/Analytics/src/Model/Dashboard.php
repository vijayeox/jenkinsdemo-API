<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\Type;

class Dashboard extends Entity {
    protected static $MODEL = [
        'id' =>                     ['type' => Type::INTEGER,   'readonly' => TRUE ,    'required' => FALSE],
        'uuid' =>                   ['type' => Type::UUID,      'readonly' => TRUE,     'required' => FALSE],
        'name' =>                   ['type' => Type::STRING,    'readonly' => FALSE,    'required' => TRUE],
        'ispublic' =>               ['type' => Type::BOOLEAN,   'readonly' => FALSE,    'required' => FALSE, 'value' => FALSE],
        'description' =>            ['type' => Type::STRING,    'readonly' => FALSE,    'required' => FALSE],
        'dashboard_type' =>         ['type' => Type::STRING,    'readonly' => FALSE,    'required' => TRUE],
        'created_by' =>             ['type' => Type::INTEGER,   'readonly' => TRUE,     'required' => FALSE],
        'date_created' =>           ['type' => Type::TIMESTAMP, 'readonly' => TRUE,     'required' => FALSE],
        'org_id' =>                 ['type' => Type::INTEGER,   'readonly' => FALSE,    'required' => FALSE],
        'isdeleted' =>              ['type' => Type::BOOLEAN,   'readonly' => FALSE,    'required' => FALSE, 'value' => FALSE],
        'content' =>                ['type' => Type::STRING,    'readonly' => FALSE,    'required' => FALSE],
        'version' =>                ['type' => Type::INTEGER,   'readonly' => FALSE,    'required' => FALSE],
        'isdefault' =>              ['type' => Type::BOOLEAN,   'readonly' => FALSE,    'required' => FALSE, 'value' => FALSE],
        'filter_configuration' =>   ['type' => Type::STRING,    'readonly' => FALSE,    'required' => FALSE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }
}
