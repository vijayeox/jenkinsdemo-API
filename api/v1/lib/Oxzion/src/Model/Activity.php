<?php
namespace Oxzion\Model;

use Oxzion\ValidationException;
use Oxzion\Model\Entity;

class Activity extends Entity
{
    protected $data = array(
        'id'=>null,
        'name'=>null,
        'app_id'=>null,
        'task_id'=>null,
        'workflow_deployment_id'=>null,
        'entity_id'=>null,
        'created_by'=>null,
        'modified_by'=>null,
        'date_created'=>null,
        'date_modified'=>null,
        'isdeleted' => 0,
    );
    public function validate()
    {
        $required = array('name');
        $this->validateWithParams($required);
    }
}
