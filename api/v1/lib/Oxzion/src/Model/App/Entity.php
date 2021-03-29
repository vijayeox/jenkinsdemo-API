<?php

namespace Oxzion\Model\App;

use Oxzion\Type;
use Oxzion\Model\Entity as OxzionEntity;
use Oxzion\ValidationException;

class Entity extends OxzionEntity
{
    protected static $MODEL = [
        'id' =>                 ['type' => Type::INTEGER,   'readonly' => true , 'required' => false],
        'uuid' =>               ['type' => Type::UUID,      'readonly' => false, 'required' => false],
        'start_date_field' =>   ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'end_date_field' =>     ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'status_field' =>       ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'name' =>               ['type' => Type::STRING,    'readonly' => false, 'required' => true],
        'app_id' =>             ['type' => Type::INTEGER,   'readonly' => false, 'required' => true],
        'assoc_id' =>           ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'description'=>         ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'override_data' =>      ['type' => Type::BOOLEAN,   'readonly' => false, 'required' => false, 'value' => false],
        'date_created' =>       ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'date_modified' =>      ['type' => Type::TIMESTAMP, 'readonly' => true,  'required' => false],
        'created_by' =>         ['type' => Type::INTEGER,   'readonly' => true,  'required' => false],
        'modified_by' =>        ['type' => Type::INTEGER,   'readonly' => true,  'required' => false],
        'ryg_rule' =>           ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'enable_comments' =>    ['type' => Type::BOOLEAN,   'readonly' => false, 'required' => false],
        'enable_documents' =>   ['type' => Type::BOOLEAN,   'readonly' => false, 'required' => false],
        'enable_view' =>        ['type' => Type::BOOLEAN,   'readonly' => false, 'required' => false],
        'enable_auditlog' =>    ['type' => Type::BOOLEAN,   'readonly' => false, 'required' => false],
        'title' =>              ['type' => Type::STRING,    'readonly' => false, 'required' => false],
        'page_id' =>            ['type' => Type::INTEGER,   'readonly' => false, 'required' => false],
        'subscriber_field' =>   ['type' => Type::STRING,    'readonly' => false, 'required' => false]
    ];
    
    public function &getModel()
    {
        return self::$MODEL;
    }
}
