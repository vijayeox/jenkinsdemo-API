<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Target extends Entity
{
    protected $data = array(
        'id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => FALSE),
        'uuid' => array('type' => parent::UUIDVAL, 'value' => null, 'readonly' => TRUE , 'required' => FALSE),
        'created_by' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => FALSE),
        'date_created' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE , 'required' => FALSE),
        'org_id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => FALSE),
        'type' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE , 'required' => FALSE),
        'period_type' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE , 'required' => FALSE),
        'red_limit' => array('type' => parent::FLOATVAL, 'value' => 0, 'readonly' => FALSE , 'required' => FALSE),
        'yellow_limit' => array('type' => parent::FLOATVAL, 'value' => 0, 'readonly' => FALSE , 'required' => FALSE),
        'green_limit' => array('type' => parent::FLOATVAL, 'value' => 0, 'readonly' => FALSE , 'required' => FALSE),
        'red_workflow_id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => FALSE , 'required' => FALSE),
        'yellow_workflow_id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => FALSE , 'required' => FALSE),
        'green_workflow_id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => FALSE , 'required' => FALSE),
        'trigger_after' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => FALSE, 'required' => FALSE),
        'version' => array('type' => parent::INTVAL, 'value' => 1, 'readonly' => FALSE, 'required' => FALSE),
        'isdeleted' => array('type' => parent::BOOLEANVAL, 'value' => false, 'readonly' => FALSE , 'required' => FALSE)
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