<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Utils\XMLUtils;
class CamundaForm{
	protected $data;
	public function __construct($form,$appId,$processId) {
		$this->data['name'] = $form->getAttribute('id');
		$this->data['app_id'] = $appId;
		$this->data['process_id'] = $processId;
		$extensionElements = $form->getElementsByTagNameNS(Config::bpmnSpec,'extensionElements');
		$bs = XMLUtils::domToArray($form);
		if($bs['bpmn2:extensionElements']){
			$properties = $bs['bpmn2:extensionElements']['camunda:properties'];
			if($properties['camunda:property']){
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