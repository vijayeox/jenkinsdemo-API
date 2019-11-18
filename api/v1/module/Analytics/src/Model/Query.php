<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Query extends Entity
{
    protected $data = array(
        'id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => FALSE),
        'uuid' => array('type' => parent::UUIDVAL, 'value' => null, 'readonly' => TRUE , 'required' => FALSE),
        'name' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE , 'required' => TRUE),
        'datasource_id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => FALSE , 'required' => TRUE),
        'configuration' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE , 'required' => TRUE),
        'ispublic' => array('type' => parent::BOOLEANVAL, 'value' => false, 'readonly' => FALSE , 'required' => FALSE),
        'created_by' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => FALSE),
        'date_created' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE , 'required' => FALSE),
        'org_id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => FALSE),
        'isdeleted' => array('type' => parent::BOOLEANVAL, 'value' => false, 'readonly' => FALSE , 'required' => FALSE)
    );

    public function validate()
    {
        $this->completeValidation();
    }
}