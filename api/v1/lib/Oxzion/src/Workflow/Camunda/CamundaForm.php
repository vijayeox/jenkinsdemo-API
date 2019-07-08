<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Utils\XMLUtils;
class CamundaForm{
	protected $data;
	public function __construct($form,$appId,$workflowId) {
		$this->data['task_id'] = $form->getAttribute('id');
		$this->data['name'] = $form->getAttribute('name');
		$this->data['app_id'] = $appId;
		$this->data['workflow_id'] = $workflowId;
		$extensionElements = $form->getElementsByTagNameNS(Config::bpmnSpec,'extensionElements');
		$bs = XMLUtils::domToArray($form);
		if(isset($bs['bpmn2:extensionElements'])&&isset($bs['bpmn2:extensionElements']['camunda:properties'])){
			$properties = $bs['bpmn2:extensionElements']['camunda:properties'];
			if(isset($properties['camunda:property'])){
				$formProperties = array();
				foreach ($properties['camunda:property'] as $propertyItem) {
					$formProperties[] = array($propertyItem['name']=>$propertyItem['value']);
				}
				$this->data['properties'] = json_encode($formProperties);
			}
		}
	}
	public function toArray(){
		return $this->data;
	}
}
?>