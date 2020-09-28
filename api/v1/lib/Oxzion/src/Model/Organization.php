<?php
namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class Organization extends Entity
{
    protected static $MODEL = array(
        'id' =>                     ['type' => Type::INTEGER,   'readonly' => TRUE , 'required' => FALSE],
        'uuid' =>                   ['type' => Type::UUID,      'readonly' => FALSE, 'required' => FALSE],
        'address_id' =>             ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => TRUE],
        'parent_id' =>              ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'main_organization_id' =>   ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'labelfile' =>              ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'languagefile' =>           ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'date_created' =>           ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'date_modified' =>          ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'created_by' =>             ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'modified_by' =>            ['type' => Type::INTEGER,   'readonly' => TRUE,  'required' => FALSE]
    );

    public function &getModel() {
        return self::$MODEL;
    }
}
