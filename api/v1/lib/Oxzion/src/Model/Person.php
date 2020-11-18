<?php
namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class Person extends Entity
{
    protected static $MODEL = array(
        'id' =>             ['type' => Type::INTEGER,   'readonly' => TRUE , 'required' => FALSE],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => FALSE, 'required' => FALSE],
        'firstname' =>      ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'lastname' =>       ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'email' =>          ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'date_of_birth' =>  ['type' => Type::DATE,      'readonly' => FALSE, 'required' => TRUE],
        'phone' =>          ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'gender' =>         ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'signature' =>      ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'address_id' =>     ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'date_modified' =>  ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'modified_by' =>    ['type' => Type::INTEGER,   'readonly' => TRUE,  'required' => FALSE],
    );

    public function &getModel() {
        return self::$MODEL;
    }

}
