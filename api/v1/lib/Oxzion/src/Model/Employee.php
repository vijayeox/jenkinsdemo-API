<?php
namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class Employee extends Entity
{
    protected static $MODEL = array(
        'id' =>                 ['type' => Type::INTEGER,   'readonly' => TRUE , 'required' => FALSE],
        'uuid' =>               ['type' => Type::UUID,      'readonly' => FALSE, 'required' => FALSE],
        'org_id' =>             ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => TRUE],
        'designation' =>        ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'website' =>            ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'about' =>              ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'interest' =>           ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'hobbies' =>            ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'manager_id' =>         ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'selfcontribute' =>     ['type' => Type::BOOLEAN,   'readonly' => FALSE, 'required' => FALSE],
        'contribute_percent' => ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'eid' =>                ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'date_created' =>       ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'date_modified' =>      ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'created_by' =>         ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'modified_by' =>        ['type' => Type::INTEGER,   'readonly' => TRUE,  'required' => FALSE],
        'person_id' =>          ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => TRUE],
        'date_of_join' =>       ['type' => Type::DATE,      'readonly' => FALSE, 'required' => TRUE],
    );

    public function &getModel() {
        return self::$MODEL;
    }
}
