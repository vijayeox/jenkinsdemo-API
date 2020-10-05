<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;

class WorkflowDeployment extends Entity
{
    protected $data = array(
        'id' => null,
        'workflow_id' => null,
        'process_definition_id' => null,
        'form_id'=> null,
        'latest' => 1,
        'created_by' => null,
        'date_created' => null,
        'fields' => null
    );
    public function validate()
    {
        $required = array('workflow_id','process_definition_id');
        $this->validateWithParams($required);
    }
}
