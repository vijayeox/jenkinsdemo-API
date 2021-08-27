<?php

namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class Job extends Entity
{
    protected static $MODEL = [
        'id' => ['type' => Type::INTEGER,   'readonly' => true , 'required' => false],
        'app_id' => ['type' => Type::INTEGER,   'readonly' => false , 'required' => true],
        'account_id' => ['type' => Type::INTEGER,   'readonly' => false , 'required' => false],
        'name' => ['type' => Type::STRING,   'readonly' => false , 'required' => false],
        'job_id' => ['type' => Type::STRING,   'readonly' => false , 'required' => false],
        'group_name' => ['type' => Type::STRING,   'readonly' => false , 'required' => false],
        'config' => ['type' => Type::STRING,   'readonly' => false , 'required' => false]
    ];

    public function &getModel()
    {
        return self::$MODEL;
    }
}
