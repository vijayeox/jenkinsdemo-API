<?php

namespace App\Model;

use Oxzion\Model\Entity;

class App extends Entity
{
    //status for the apps
    const DELETED = 1;
    const IN_DRAFT = 2;
    const PREVIEW = 3;
    const PUBLISHED = 4;

    //types of apps
    const PRE_BUILT = 1;
    const MY_APP = 2;

    protected $data = array(
        'id' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => TRUE , 'required' => FALSE),
        'name' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => TRUE),
        'uuid' => array('type' => parent::UUIDVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'description' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => FALSE),
        'type' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => FALSE, 'required' => TRUE),
        'isdefault' => array('type' => parent::BOOLEANVAL, 'value' => false, 'readonly' => FALSE, 'required' => TRUE),
        'logo' => array('type' => parent::STRINGVAL, 'value' => 'default_app.png', 'readonly' => FALSE, 'required' => FALSE),
        'category' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE, 'required' => TRUE),
        'date_created' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'date_modified' => array('type' => parent::TIMESTAMPVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'created_by' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'modified_by' => array('type' => parent::INTVAL, 'value' => null, 'readonly' => TRUE, 'required' => FALSE),
        'status' => array('type' => parent::INTVAL, 'value' => 0, 'readonly' => FALSE , 'required' => TRUE),
        'start_options' => array('type' => parent::STRINGVAL, 'value' => null, 'readonly' => FALSE , 'required' => FALSE),
        'version' => array('type' => parent::INTVAL, 'value' => 1, 'readonly' => FALSE, 'required' => FALSE),
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

