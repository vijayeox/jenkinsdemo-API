<?php
namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class Organization extends Entity
{
    protected static $MODEL = array(
        'id' =>                     ['type' => Type::INTEGER,   'readonly' => true , 'required' => false],
        'uuid' =>                   ['type' => Type::UUID,      'readonly' => false, 'required' => false],
        'address_id' =>             ['type' => Type::INTEGER,   'readonly' => false, 'required' => true],
        'parent_id' =>              ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'labelfile' =>              ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'languagefile' =>           ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'date_created' =>           ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'date_modified' =>          ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'created_by' =>             ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'modified_by' =>            ['type' => Type::INTEGER,   'readonly' => true,  'required' => false]
    );

    public function &getModel()
    {
        return self::$MODEL;
    }
}
