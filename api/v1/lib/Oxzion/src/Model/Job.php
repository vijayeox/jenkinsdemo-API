<?php

namespace Oxzion\Model;
use Oxzion\Type;
use Oxzion\Model\Entity;

class Job extends Entity
{
    protected static $MODEL = [
        'id' => ['type' => Type::INTEGER,   'readonly' => TRUE , 'required' => FALSE],
        'app_id' => ['type' => Type::INTEGER,   'readonly' => FALSE , 'required' => TRUE],
        'account_id' => ['type' => Type::INTEGER,   'readonly' => FALSE , 'required' => FALSE],
        'name' => ['type' => Type::STRING,   'readonly' => FALSE , 'required' => FALSE],
        'job_id' => ['type' => Type::STRING,   'readonly' => FALSE , 'required' => FALSE],
        'group_name' => ['type' => Type::STRING,   'readonly' => FALSE , 'required' => FALSE],
        'config' => ['type' => Type::STRING,   'readonly' => FALSE , 'required' => FALSE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }
}
