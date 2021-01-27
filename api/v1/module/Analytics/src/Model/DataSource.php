<?php

namespace Analytics\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class DataSource extends Entity {
    protected static $MODEL = [
        'id' =>             ['type' => Type::INTEGER,   'readonly' => TRUE ,    'required' => FALSE],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => TRUE,     'required' => FALSE],
        'name' =>           ['type' => Type::STRING,    'readonly' => FALSE,    'required' => TRUE],
        'type' =>           ['type' => Type::STRING,    'readonly' => FALSE,    'required' => TRUE],
        'configuration' =>  ['type' => Type::STRING,    'readonly' => FALSE,    'required' => TRUE],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => TRUE,     'required' => FALSE],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => TRUE,     'required' => FALSE],
        'account_id' =>         ['type' => Type::INTEGER,   'readonly' => TRUE,     'required' => FALSE],
        'isdeleted' =>      ['type' => Type::BOOLEAN,   'readonly' => FALSE,    'required' => FALSE, 'value' => FALSE],
        'version' =>        ['type' => Type::INTEGER,   'readonly' => FALSE,    'required' => FALSE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }
}