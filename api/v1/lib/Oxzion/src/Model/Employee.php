<?php
namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class Employee extends Entity
{
    protected static $MODEL = array(
        'id' =>                 ['type' => Type::INTEGER,   'readonly' => true , 'required' => false],
        'uuid' =>               ['type' => Type::UUID,      'readonly' => false, 'required' => false],
        'org_id' =>             ['type' => Type::INTEGER,   'readonly' => false, 'required' => true],
        'designation' =>        ['type' => Type::STRING,    'readonly' => false, 'required' => true],
        'website' =>            ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'about' =>              ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'interest' =>           ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'hobbies' =>            ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'manager_id' =>         ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'selfcontribute' =>     ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'contribute_percent' => ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'eid' =>                ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'date_created' =>       ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'date_modified' =>      ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'created_by' =>         ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'modified_by' =>        ['type' => Type::INTEGER,   'readonly' => true,  'required' => false],
        'person_id' =>          ['type' => Type::INTEGER,   'readonly' => false, 'required' => true],
        'date_of_join' =>       ['type' => Type::DATE,      'readonly' => false, 'required' => true],
    );

    public function &getModel()
    {
        return self::$MODEL;
    }
}
