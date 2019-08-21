<?php
namespace Oxzion\Workflow\Camunda;

class CamundaActivity
{
    protected $data;
    public function __construct($form, $appId, $workflowId)
    {
        $this->data['task_id'] = $form->getAttribute('id');
        $this->data['name'] = $form->getAttribute('name');
        $this->data['app_id'] = $appId;
        $this->data['workflow_id'] = $workflowId;
    }
    public function toArray()
    {
        return $this->data;
    }
}
