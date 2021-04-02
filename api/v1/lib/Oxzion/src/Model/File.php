<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;
use Oxzion\Type;

class File extends Entity
{
    const RED = "RED";
    const YELLOW = "YELLOW";
    const GREEN = "GREEN";

    protected static $MODEL = [
        'id' =>                         ['type' => Type::INTEGER,   'readonly' => true , 'required' => false],
        'account_id' =>                 ['type' => Type::INTEGER,   'readonly' => false, 'required' => true],
        'uuid' =>                       ['type' => Type::UUID,      'readonly' => true, 'required' => false],
        'data' =>                       ['type' => Type::STRING,    'readonly' => false, 'required' => true],
        'form_id' =>                    ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'created_by' =>                 ['type' => Type::INTEGER,   'readonly' => true, 'required' => false],
        'modified_by' =>                ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'date_created' =>               ['type' => Type::TIMESTAMP, 'readonly' => true, 'required' => false],
        'date_modified' =>              ['type' => Type::TIMESTAMP, 'readonly' => false, 'required' => false],
        'entity_id' =>                  ['type' => Type::INTEGER,   'readonly' => false, 'required' => true],
        'assoc_id' =>                   ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'is_active' =>                  ['type' => Type::BOOLEAN,   'value' => 1, 'readonly' => false, 'required' => false],
        'last_workflow_instance_id' =>  ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'start_date' =>                 ['type' => Type::DATE, 'readonly' => false, 'required' => false],
        'end_date' =>                   ['type' => Type::DATE, 'readonly' => false, 'required' => false],
        'status' =>                     ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'version' =>                    ['type' => Type::INTEGER,   'value' => 1, 'readonly' => false, 'required' => false],
        'rygStatus' =>                  ['type' => Type::STRING,    'value' => 'GREEN', 'readonly' => false, 'required' => false],
        'fileTitle' =>                  ['type' => Type::STRING,    'readonly' => false, 'required' => false]
    ];

    public function &getModel()
    {
        return self::$MODEL;
    }
}
