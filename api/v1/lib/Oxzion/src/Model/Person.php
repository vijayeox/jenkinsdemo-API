<?php
namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class Person extends Entity
{
    protected static $MODEL = array(
        'id' =>             ['type' => Type::INTEGER,   'readonly' => true , 'required' => false],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => false, 'required' => false],
        'firstname' =>      ['type' => Type::STRING,    'readonly' => false, 'required' => true],
        'lastname' =>       ['type' => Type::STRING,    'readonly' => false, 'required' => true],
        'email' =>          ['type' => Type::STRING,    'readonly' => false, 'required' => true],
        'date_of_birth' =>  ['type' => Type::DATE,      'readonly' => false, 'required' => true],
        'phone' =>          ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'gender' =>         ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'signature' =>      ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'address_id' =>     ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'date_modified' =>  ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'modified_by' =>    ['type' => Type::INTEGER,   'readonly' => true,  'required' => false],
    );

    public function &getModel()
    {
        return self::$MODEL;
    }
}
