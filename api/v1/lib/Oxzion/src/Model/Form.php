<?php
namespace Oxzion\Model;

use Oxzion\ValidationException;
use Oxzion\Model\Entity;

class Form extends Entity
{
    protected $data = array(
        'id'=>0,
        'app_id'=>0,
        'name'=>null,
        'description'=>null,
        'entity_id'=>0,
        'template'=>null,
        'created_by'=>null,
        'modified_by'=>null,
        'date_created'=>null,
        'date_modified'=>null,
    );
    public function validate()
    {
        $required = array('app_id','name');
        $this->validateWithParams($required);
    }
}
