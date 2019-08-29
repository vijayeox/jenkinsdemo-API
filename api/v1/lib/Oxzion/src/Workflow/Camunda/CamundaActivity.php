<?php
namespace Oxzion\Workflow\Camunda;
use Oxzion\Utils\XMLUtils;

class CamundaActivity
{
    protected $data;
    public function __construct($activity, $appId, $workflowId)
    {
        $this->data['task_id'] = $activity->getAttribute('id');
        $this->data['name'] = $activity->getAttribute('name');
        $this->data['app_id'] = $appId;
        $this->data['workflow_id'] = $workflowId;
        $extensionElements = $activity->getElementsByTagNameNS(Config::bpmnSpec, 'extensionElements');
        $bs = XMLUtils::domToArray($activity);
        if ((isset($bs['bpmn2:extensionElements'])&&isset($bs['bpmn2:extensionElements']['camunda:properties'])) || (isset($bs['bpmn:extensionElements'])&&isset($bs['bpmn:extensionElements']['camunda:properties']))) {
            $extenstionElements = isset($bs['bpmn2:extensionElements'])?$bs['bpmn2:extensionElements']:$bs['bpmn:extensionElements'];
            $properties = $extenstionElements['camunda:properties'];
            if (isset($properties['camunda:property'])) {
                $formProperties = array();
                    if(isset($properties['camunda:property'])){
                        if(isset($properties['camunda:property']['name'])){
                            $formProperties[$properties['camunda:property']['name']] = $properties['camunda:property']['value'];
                        } else {
                            foreach ($properties['camunda:property'] as $property) {
                                if(isset($property['name'])){
                                    $formProperties[$property['name']] = $property['value'];
                                }
                            }
                        }
                    }
                $this->data['form'] = json_encode($formProperties);
            }
        }
    }
    public function toArray()
    {
        return $this->data;
    }
}
