<?php
namespace Oxzion\FormEngine\Formio;
use Oxzion\FormEngine\Engine;

class EngineImpl implements Engine {
	
	public function parseForm($form){
		$template = json_decode($form,true);
		if(isset($template)){
			$formTemplate['form']['name'] = isset($template['name'])?$template['name']:null;
			$formTemplate['form']['description'] = $template['title'];
			$formTemplate['form']['template'] = json_encode($template);
			$itemslist = array();
			$fieldList = $this->searchNodes($itemslist,$template['components']);
			$oxFieldArray = array();
			if($fieldList){
				foreach ($fieldList as $field) {
					$oxFieldArray[] = $this->generateField($field);
				}
				$formTemplate['fields'] = $oxFieldArray;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
		return $formTemplate;
	}
	protected function searchNodes($itemlist =array(),$items){
		foreach ($items as $item) {
			if(isset($item['input']) && $item['input']==1){
				if(isset($item['tree']) && $item['tree']){
					return $this->searchNodes($itemlist,$item['components']);
				} else {
					if(isset($item['type']) && $item['type']!='button'){
						$itemlist[] = $item;
					} else {
						break;
					}
				}
			} else {
				$flag =0;
				if(isset($item['components'])){
					$flag =1;
					$itemlist = $this->searchNodes($itemlist,$item['components']);
				}
				if(isset($item['columns']) && is_array($item['columns'])){
					$itemlist = $this->searchNodes($itemlist,$item['columns']);
					$flag =1;
				}
				if(isset($item['rows']) && is_array($item['rows'])){
					$itemlist = $this->searchNodes($itemlist,$item['rows']);
					$flag =1;
				}
				if(isset($item['rows']) && is_array($item['rows'])){
					$flag =1;
					$itemlist = $this->searchNodes($itemlist,$item['rows']);
				}
				if(!$flag){
					if(isset($item['input']) && $item['input'] && isset($item['type']) && $item['type']!='button'){
						$itemlist[] = $item;
					} else {
						if(!isset($item['type'])){
							if(is_array($item)){
								$itemlist = $this->searchNodes($itemlist,$item);
							} else {
								return $itemlist;
							}
						} else {
							// print_r($item);exit;
						}
					}
				}
			}
		}
		return $itemlist;
	}
	protected function generateField($field){
		$field = new FormioField($field);
		return $field->toArray();
	}
}
?>