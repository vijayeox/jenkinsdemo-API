<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Widget extends Entity
{
    protected $data = array(
        'id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => FALSE),
        'uuid' => array('type' => parent::UUIDVAL, 'value' => null, 'readonly' => TRUE , 'required' => FALSE),
        'visualization_id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => FALSE , 'required' => TRUE),
        'ispublic' => array('type' => parent::BOOLEANVAL, 'value' => false, 'readonly' => FALSE , 'required' => FALSE),
        'created_by' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => FALSE),
        'date_created' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE , 'required' => FALSE),
        'org_id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => TRUE),
        'isdeleted' => array('type' => parent::BOOLEANVAL, 'value' => false, 'readonly' => FALSE , 'required' => FALSE),
        'name' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE , 'required' => TRUE),
        'configuration' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE , 'required' => TRUE),
        'expression' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE , 'required' => FALSE),
        'version' => array('type' => parent::INTVAL, 'value' => 1, 'readonly' => FALSE, 'required' => FALSE),
        'no_filter_override' => array('type' => parent::BOOLEANVAL, 'value' => false, 'readonly' => FALSE , 'required' => FALSE)
    );

    public function validate()
    {
        $this->completeValidation();
    }

    public function updateValidate()
    {
        $this->typeChecker();
    }
}

