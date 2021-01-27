<?php
namespace Oxzion\Model;

use Oxzion\Type;
use Oxzion\Model\Entity;

class Account extends Entity
{
    const BUSINESS = 'BUSINESS';
    const INDIVIDUAL = 'INDIVIDUAL';

    protected static $MODEL = array(
        'id' =>                 ['type' => Type::INTEGER,   'readonly' => TRUE , 'required' => FALSE],
        'uuid' =>               ['type' => Type::UUID,      'readonly' => FALSE,  'required' => FALSE],
        'name' =>               ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'subdomain' =>          ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'contactid' =>          ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE],
        'preferences' =>        ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE],
        'theme' =>              ['type' => Type::STRING,    'readonly' => FALSE, 'required' => FALSE],
        'organization_id' =>    ['type' => Type::INTEGER,   'readonly' => FALSE, 'required' => FALSE, 
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
                if($data["type"] == "BUSINESS" && !$value){
                    return "Organization not set for Business type Account";
                }
            '],
        'status' =>             ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE, 'value' => 'Active'],
        'type' =>               ['type' => Type::STRING,    'readonly' => FALSE, 'required' => TRUE, 'value' => 'BUSINESS'],
    );

    public function &getModel() {
        return self::$MODEL;
    }
}
