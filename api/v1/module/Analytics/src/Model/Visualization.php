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
        'org_id' =>         ['type' => Type::INTEGER,   'readonly' => TRUE ,    'required' => FALSE],
        'isdeleted' =>      ['type' => Type::BOOLEAN,   'readonly' => FALSE ,   'required' => FALSE, 'value' => FALSE],
        'configuration' =>  ['type' => Type::STRING,    'readonly' => FALSE ,   'required' => TRUE],
        'renderer' =>       ['type' => Type::STRING,    'readonly' => FALSE ,   'required' => TRUE],
        'type' =>           ['type' => Type::STRING,    'readonly' => FALSE ,   'required' => TRUE],
        'version' =>        ['type' => Type::INTEGER,   'readonly' => FALSE,    'required' => FALSE]
    ];

    public function &getModel() {
        return self::$MODEL;
    }

    public function validate() {
        $errors = array();
        try {
            parent::validate();
        }
        catch (ValidationException $e) {
            $validationException = $e;
            $errors = $e->getErrors();
        }
        try {
            $this->validateType();
        }
        catch (ValidationException $e) {
            $errors = array_merge($errors, $e->getErrors());
        }
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    public function validateType()
    {
        $allowedValues = ['chart', 'html', 'inline', 'table'];
        if (!in_array($this->data['type'], $allowedValues)) {
            throw new ValidationException(['type' => ['value' => $this->data['type'], 'error' => 'Not one of ' . json_encode($allowedValues)]]);
        }
    }
}

