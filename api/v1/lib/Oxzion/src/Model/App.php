<?php

namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class App extends Entity {
    //status for the apps
    const DELETED = 1;
    const IN_DRAFT = 2;
    const PREVIEW = 3;
    const PUBLISHED = 4;

    //types of apps
    const PRE_BUILT = 1;
    const MY_APP = 2;

    protected static $MODEL = [
        'id' =>             ['type' => Type::INTEGER,   'readonly' => TRUE , 'required' => FALSE],
        'name' =>           ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => FALSE,  'required' => FALSE],
        'description' =>    ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'type' =>           ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => TRUE],
        'isdefault' =>      ['type' => Type::BOOLEAN,   'readonly' => FALSE, 'required' => TRUE, 'value' => FALSE],
        'logo' =>           ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE, 'value' => 'default_app.png'],
        'category' =>       ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'date_modified' =>  ['type' => Type::TIMESTAMP, 'readonly' => TRUE,  'required' => FALSE],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => TRUE,  'required' => FALSE],
        'modified_by' =>    ['type' => Type::INTEGER,   'readonly' => TRUE,  'required' => FALSE],
        'status' =>         ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => TRUE, 'value' => 0],
        'start_options' =>  ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }
}
