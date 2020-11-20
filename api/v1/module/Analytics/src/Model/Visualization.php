<?php

namespace Analytics\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Visualization extends Entity {
    protected static $MODEL = [
        'id' =>             ['type' => Type::INTEGER,   'readonly' => TRUE ,    'required' => FALSE],
        'uuid' =>           ['type' => Type::UUID,      'readonly' => TRUE ,    'required' => FALSE],
        'name' =>           ['type' => Type::STRING,    'readonly' => FALSE ,   'required' => TRUE],
        'created_by' =>     ['type' => Type::INTEGER,   'readonly' => TRUE ,    'required' => FALSE],
        'date_created' =>   ['type' => Type::TIMESTAMP, 'readonly' => TRUE ,    'required' => FALSE],
        'account_id' =>         ['type' => Type::INTEGER,   'readonly' => TRUE ,    'required' => FALSE],
        'isdeleted' =>      ['type' => Type::BOOLEAN,   'readonly' => FALSE ,   'required' => FALSE, 'value' => FALSE],
        'configuration' =>  ['type' => Type::STRING,    'readonly' => FALSE ,   'required' => TRUE],
        'renderer' =>       ['type' => Type::STRING,    'readonly' => FALSE ,   'required' => TRUE],
        'type' =>           ['type' => Type::STRING,    'readonly' => FALSE ,   'required' => TRUE, 
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
        'version' =>        ['type' => Type::INTEGER,   'readonly' => FALSE,    'required' => FALSE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }
}
