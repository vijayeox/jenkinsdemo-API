<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;

class Dashboard extends Entity
{
    protected $data = array(
        'id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => FALSE),
        'uuid' => array('type' => parent::UUIDVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'name' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => TRUE),
        'ispublic' => array('type' => parent::BOOLEANVAL, 'value' => false, 'readonly' => FALSE, 'required' => FALSE),
        'description' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => FALSE),
        'dashboard_type' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => TRUE),
        'created_by' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'date_created' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'org_id' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => FALSE, 'required' => FALSE),
        'isdeleted' => array('type' => parent::BOOLEANVAL, 'value' => false, 'readonly' => FALSE, 'required' => FALSE),
        'content' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => FALSE),
        'version' => array('type' => parent::INTVAL, 'value' => 1, 'readonly' => FALSE, 'required' => FALSE),
        'isdefault' => array('type' => parent::BOOLEANVAL, 'value' => 0, 'readonly' => FALSE, 'required' => FALSE),
        'filter_configuration' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => FALSE)
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
