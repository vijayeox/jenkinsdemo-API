<?php

namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class App extends Entity
{
    //status for the apps
    const DELETED = 1;
    const IN_DRAFT = 2;
    const PREVIEW = 3;
    const PUBLISHED = 4;

    //types of apps
    const PRE_BUILT = 1;
    const MY_APP = 2;

    protected static $MODEL = [
        'id' =>             ['type' => Type::INTEGER,   'readonly' => true , 'required' => false],
        'name' =>           ['type' => Type::STRING,    'readonly' => false, 'required' => true],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => false,  'required' => false],
        'description' =>    ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'type' =>           ['type' => Type::INTEGER,   'readonly' => false, 'required' => true],
        'isdefault' =>      ['type' => Type::BOOLEAN,   'readonly' => false, 'required' => true, 'value' => false],
        'logo' =>           ['type' => Type::STRING,    'readonly' => false, 'required' => false, 'value' => 'default_app.png'],
        'category' =>       ['type' => Type::STRING,    'readonly' => false, 'required' => true],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'date_modified' =>  ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => true,  'required' => false],
        'modified_by' =>    ['type' => Type::INTEGER,   'readonly' => true,  'required' => false],
        'status' =>         ['type' => Type::INTEGER,   'readonly' => false, 'required' => true, 'value' => 0],
        'start_options' =>  ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'app_properties' =>      ['type' => Type::STRING,   'readonly' => false, 'required' => false]
    ];

    public function &getModel()
    {
        return self::$MODEL;
    }
}
