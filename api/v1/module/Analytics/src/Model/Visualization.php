<?php

namespace Analytics\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class Visualization extends Entity
{
    protected $data = array(
        'id' => 0,
        'uuid' => null,
        'name' => null,
        'created_by' => 0,
        'date_created' => null,
        'org_id' => 0,
        'isdeleted' => 0
    );

    public function validate()
    {
        $dataArray = array("name","created_by","date_created","org_id","uuid");
        $this->validateWithParams($dataArray);
    }

    public function validateType($type)
    {
        if($type == 'Aggregate' || $type == 'Bar' || $type == 'Line' || $type == 'Pie')
            return;
        else
        {
            $errors = array('data' => 'Type must be Aggregate, Bar, Line or Pie');
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}