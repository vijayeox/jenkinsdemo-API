<?php

namespace Oxzion\Model;
use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class File extends Entity
{
    protected $data = array(
        'id' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => TRUE , 'required' => FALSE),
        'org_id' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => FALSE, 'required' => TRUE),
        'uuid' => array('type' => parent::UUIDVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'data' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => TRUE),
        'form_id' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => FALSE, 'required' => FALSE),
        'created_by' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'modified_by' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'date_created' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'date_modified' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'entity_id' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => FALSE, 'required' => TRUE),
        'assoc_id' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => FALSE, 'required' => FALSE),
        'is_active' => array('type' => parent::BOOLEANVAL, 'value' => 1, 'readonly' => FALSE, 'required' => FALSE),
        'last_workflow_instance_id' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => FALSE, 'required' => FALSE),
        'start_date' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'end_date' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'status' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => FALSE),
        'version' => array('type' => parent::INTVAL, 'value' => 1, 'readonly' => FALSE, 'required' => FALSE)
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
