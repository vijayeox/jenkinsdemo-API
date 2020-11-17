<?php

namespace Oxzion\Model;
use Oxzion\Model\Entity;
use Oxzion\ValidationException;
use Oxzion\Type;

class File extends Entity
{
    protected static $MODEL = [
        'id' =>                         ['type' => Type::INTEGER,   'readonly' => TRUE , 'required' => FALSE],
        'account_id' =>                 ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => TRUE],
        'uuid' =>                       ['type' => Type::UUID,      'readonly' => TRUE, 'required' => FALSE],
        'data' =>                       ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'form_id' =>                    ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'created_by' =>                 ['type' => Type::INTEGER,   'readonly' => TRUE, 'required' => FALSE],
        'modified_by' =>                ['type' => Type::INTEGER,   'readonly' => TRUE, 'required' => FALSE],
        'date_created' =>               ['type' => Type::TIMESTAMP, 'readonly' => TRUE, 'required' => FALSE],
        'date_modified' =>              ['type' => Type::TIMESTAMP, 'readonly' => TRUE, 'required' => FALSE],
        'entity_id' =>                  ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => TRUE],
        'assoc_id' =>                   ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'is_active' =>                  ['type' => Type::BOOLEAN,   'value' => 1, 'readonly' => FALSE, 'required' => FALSE],
        'last_workflow_instance_id' =>  ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'start_date' =>                 ['type' => Type::TIMESTAMP, 'readonly' => TRUE, 'required' => FALSE],
        'end_date' =>                   ['type' => Type::TIMESTAMP, 'readonly' => TRUE, 'required' => FALSE],
        'status' =>                     ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'version' =>                    ['type' => Type::INTEGER,  'value' => 1, 'readonly' => FALSE, 'required' => FALSE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }
}
