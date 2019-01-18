<?php
namespace Oxzion\Workflow\Camunda;

class CamundaField{
	protected $data;
	public function __construct($field) {
		$this->data['name'] = $field->getAttribute('id');
		$this->data['text'] = $field->getAttribute('label');
		$this->data['data_type'] = $field->getAttribute('type');
		$validation = $field->getElementsByTagNameNS(Config::camundaSpec,'validation');
		if($validation->count()){
			foreach ($validation as $validationProperty) {
				$constraints = $validationProperty->getElementsByTagNameNS(Config::camundaSpec,'constraint');
				if($constraints){
					$fieldConstraints = array();
					foreach ($constraints as $constraint) {
						$fieldConstraints[] = array('name'=>$constraint->getAttribute('name'),'config'=>$constraint->getAttribute('config'));
					}
				}
			}
			$this->data['constraints'] = json_encode($fieldConstraints);
		}
		$properties = $field->getElementsByTagNameNS(Config::camundaSpec,'properties');
		if($properties->count()){
			$fieldProperties = array();
			foreach ($properties as $propertyItem) {
				$propertiesList = $propertyItem->getElementsByTagNameNS(Config::camundaSpec,'property');
				foreach ($propertiesList as $property) {
					$fieldProperties[] = array('id'=>$property->getAttribute('id'),'value'=>$property->getAttribute('value'));
				}
			}
			$this->data['properties'] = json_encode($fieldProperties);
		}
	}
	public function toArray(){
		return $this->data;
	}
}
?>