<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\Type;

class BusinessRole extends Entity
{
    protected static $MODEL = [
        'id' =>             ['type' => Type::INTEGER,   'readonly' => TRUE, 'required' => FALSE],
        'name' =>           ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'app_id' =>         ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => TRUE],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => TRUE, 'required' => FALSE],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => TRUE, 'required' => FALSE],
        'modified_by' =>    ['type' => Type::INTEGER,   'readonly' => TRUE, 'required' => FALSE],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => TRUE, 'required' => FALSE],
        'date_modified' =>  ['type' => Type::TIMESTAMP, 'readonly' => TRUE, 'required' => FALSE],
        'version' =>        ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }
}
