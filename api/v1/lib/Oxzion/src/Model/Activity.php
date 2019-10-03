<?php
namespace Oxzion\Model;

use Oxzion\ValidationException;
use Oxzion\Model\Entity;

class Activity extends Entity
{
    protected $data = array(
        'id'=>0,
        'name'=>null,
        'app_id'=>0,
        'task_id'=>0,
        'workflow_id'=>0,
        'entity_id'=>0,
        'created_by'=>null,
        'modified_by'=>null,
        'date_created'=>null,
        'date_modified'=>null,
    );
    public function validate()
    {
        $required = array('name');
        $this->validateWithParams($required);
    }
}
