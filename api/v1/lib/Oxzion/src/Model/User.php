<?php
namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class User extends Entity
{
    protected static $MODEL = array(
        'id' =>                 ['type' => Type::INTEGER,   'readonly' => TRUE , 'required' => FALSE],
        'uuid' =>               ['type' => Type::UUID,      'readonly' => FALSE, 'required' => FALSE],
        'username' =>           ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'password' =>           ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'name' =>               ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'account_id' =>         ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => TRUE],
        'icon' =>               ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'status' =>             ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE, 'value' => 'Active'],
        'in_game' =>            ['type' => Type::BOOLEAN,    'readonly' => FALSE, 'required' => FALSE, 'value' => FALSE],
        'timezone' =>           ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE, 'value' => 'Asia/Kolkata'],
        'date_created' =>       ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'date_modified' =>      ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'created_by' =>         ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'modified_by' =>        ['type' => Type::INTEGER,   'readonly' => TRUE,  'required' => FALSE],
        'preferences' =>        ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'password_reset_code' => ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'password_reset_expiry_date' => ['type' => Type::TIMESTAMP,    'readonly' => FALSE, 'required' => FALSE],
        'person_id' =>          ['type' => Type::INTEGER,    'readonly' => FALSE, 'required' => TRUE],
    );

    public function &getModel() {
        return self::$MODEL;
    }

}
