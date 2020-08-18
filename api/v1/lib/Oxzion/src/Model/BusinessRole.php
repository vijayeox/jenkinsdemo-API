<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class BusinessRole extends Entity
{
    protected $data = array(
        'id' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'name' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => TRUE),
        'app_id' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => FALSE, 'required' => TRUE),
        'uuid' => array('type' => parent::UUIDVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'created_by' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'modified_by' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'date_created' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'date_modified' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
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
