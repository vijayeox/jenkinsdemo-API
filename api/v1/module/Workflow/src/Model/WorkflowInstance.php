<?php

namespace Workflow\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class WorkflowInstance extends Entity
{
    protected $data = array(
        'id' => 0,
        'workflow_id' => null,
        'app_id' => 0,
        'org_id' => 0,
        'process_instance_id' => null,
        'status' => null,
        'data' => null,
        'date_created' => null,
        'date_modified' => null,
        'created_by' => null,
        'modified_by' => null,
        'parent_workflow_instance_id' => null
    );
    
    public function validate()
    {
        $dataArray = array("workflow_id", "app_id", "org_id", "date_created","created_by","status");
        $this->validateWithParams($dataArray);
    }
}
