<?php

namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\ValidationException;

class WorkflowInstance extends Entity
{
    protected $data = array(
        'id' => null,
        'workflow_deployment_id' => null,
        'app_id' => 0,
        'org_id' => 0,
        'process_instance_id' => null,
        'status' => null,
        'date_created' => null,
        'date_modified' => null,
        'created_by' => null,
        'modified_by' => null,
        'parent_workflow_instance_id' => null,
        'file_id' => 0,
        'start_data' => null,
        'completion_data' => null,
        'entity_id' => null,
        'isdeleted' => 0,
    );

    public function validate()
    {
        $dataArray = array("workflow_deployment_id", "app_id", "org_id", "date_created","created_by","status");
        $this->validateWithParams($dataArray);
    }
}
