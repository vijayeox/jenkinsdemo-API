<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Utils\XMLUtils;

class CamundaForm
{
    protected $data;
    public function __construct($form, $appId, $workflowId)
    {
        $this->data['task_id'] = $form->getAttribute('id');
        $this->data['name'] = $form->getAttribute('name');
        $this->data['app_id'] = $appId;
        $this->data['workflow_id'] = $workflowId;
        $extensionElements = $form->getElementsByTagNameNS(Config::bpmnSpec, 'extensionElements');
        $bs = XMLUtils::domToArray($form);
        if ((isset($bs['bpmn2:extensionElements'])&&isset($bs['bpmn2:extensionElements']['camunda:properties'])) || (isset($bs['bpmn:extensionElements'])&&isset($bs['bpmn:extensionElements']['camunda:properties']))) {
            $extenstionElements = isset($bs['bpmn2:extensionElements'])?$bs['bpmn2:extensionElements']:$bs['bpmn:extensionElements'];
            $properties = $extenstionElements['camunda:properties'];
            if (isset($properties['camunda:property'])) {
                $formProperties = array();
                    if(isset($properties['camunda:property'])){
                        foreach ($properties['camunda:property'] as $property) {
                            $formProperties[$property['name']] = $property['value'];
                        }
                    }
                $this->data['properties'] = json_encode($formProperties);
            }
        }
    }
    public function toArray()
    {
        return $this->data;
    }
}
