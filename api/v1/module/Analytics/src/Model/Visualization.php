<?php

namespace Analytics\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Visualization extends Entity
{
    protected static $MODEL = [
        'id' =>             ['type' => Type::INTEGER,   'readonly' => true ,    'required' => false],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => true ,    'required' => false],
        'name' =>           ['type' => Type::STRING,    'readonly' => false ,   'required' => true],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => true ,    'required' => false],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => true ,    'required' => false],
        'account_id' =>         ['type' => Type::INTEGER,   'readonly' => true ,    'required' => false],
        'isdeleted' =>      ['type' => Type::BOOLEAN,   'readonly' => false ,   'required' => false, 'value' => false],
        'configuration' =>  ['type' => Type::STRING,    'readonly' => false ,   'required' => true],
        'renderer' =>       ['type' => Type::STRING,    'readonly' => false ,   'required' => true],
        'type' =>           ['type' => Type::STRING,    'readonly' => false ,   'required' => true,
            //Dynamic validation code is run using PHP eval function. It runs in the context of Entity.
            //Dynamic code has access to following implicit variables:
            //      $data - Array containing all the properties of this entity.
            //      $value - Value of the property being validated.
            //      $property - Name of the property being validated.
            //Dynamically evaluated code should return:
            //      NULL if validation passes.
            //      Validation error message if validation fails.
            //Dynamically evaluated code may also throw InvalidPropertyValueException
            //(\Oxzion\InvalidPropertyValueException) if validation fails.
            'dynamicValidation' => '
                $allowedValues = ["chart", "html", "inline", "table"];
                return in_array($value, $allowedValues) ? 
                    NULL : "Value not in list:" . json_encode($allowedValues);
            '],
        'version' =>        ['type' => Type::INTEGER,   'readonly' => false,    'required' => false]
    ];

    public function &getModel()
    {
        return self::$MODEL;
    }
}
