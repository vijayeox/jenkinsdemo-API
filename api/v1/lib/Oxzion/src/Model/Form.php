<?php
namespace Oxzion\Model;

use Oxzion\ValidationException;
use Oxzion\Model\Entity;

class Form extends Entity
{
    protected $data = array(
        'id'=>null,
        'app_id'=>null,
        'name'=>null,
        'uuid' => null,
        'description'=>null,
        'entity_id'=>null,
        'template'=>null,
        'created_by'=>null,
        'modified_by'=>null,
        'date_created'=>null,
        'date_modified'=>null,
    );
    public function validate()
    {
        $required = array('app_id','name', 'uuid', 'template', 'created_by', 'entity_id');
        $this->validateWithParams($required);
    }
}
