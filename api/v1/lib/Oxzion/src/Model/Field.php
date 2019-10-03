<?php
namespace Oxzion\Model;

use Oxzion\ValidationException;
use Oxzion\Model\Entity;

class Field extends Entity
{
    protected $data = array(
        'id'=>0,
        'app_id'=>0,
        'name'=>null,
        'text'=>null,
        'workflow_id'=>0,
        'entity_id'=>0,
        'data_type'=>null,
        'options'=>null,
        'template'=>null,
        'constraints'=>null,
        'properties'=>null,
        'dependson'=>null,
        'required'=>null,
        'readonly'=>null,
        'expression'=>null,
        'validationtext'=>null,
        'helpertext'=>null,
        'sequence'=>null,
        'created_by'=>null,
        'modified_by'=>null,
        'date_created'=>null,
        'date_modified'=>null,
    );

    public function validate()
    {
        $required = array('app_id','name','data_type');
        $this->validateWithParams($required);
    }
}
