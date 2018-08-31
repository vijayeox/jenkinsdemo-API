<?php

namespace Metaform\Model;

use Oxzion\Model\Model;

class Metafield extends Model{
	public $id;
	public $sequence;
	public $formid;
	public $dependson;
	public $disablejavascript;
	public $required;
	public $readonly;
	public $canbehidden;
	public $onlyownrcanchng;
	public $dateordependson;

	public $name;
	public $columnname;
	public $text;
	public $helpertext;
	public $type;
	public $options;
	public $color;
	public $regexpvalidator;
	public $validationtext;
	public $specialvalidator;
	public $expression;
	public $condition;
	public $premiumname;
	public $xflat_parameter;
	public $esign_parameter;
	public $field_type;
	public $category;

	public function exchangeArray($data){
		$this->id = !empty($data['id']) ? $data['id'] : 0;
		$this->sequence = !empty($data['sequence']) ? $data['sequence'] : null;
		$this->formid = !empty($data['formid']) ? $data['formid'] : null;
		$this->dependson = !empty($data['dependson']) ? $data['dependson'] : null;
		$this->disablejavascript = !empty($data['disablejavascript']) ? $data['disablejavascript'] : null;
		$this->required = !empty($data['required']) ? $data['required'] : null;
		$this->readonly = !empty($data['readonly']) ? $data['readonly'] : null;
		$this->canbehidden = !empty($data['canbehidden']) ? $data['canbehidden'] : null;
		$this->onlyownrcanchng = !empty($data['onlyownrcanchng']) ? $data['onlyownrcanchng'] : null;
		$this->dateordependson = !empty($data['dateordependson']) ? $data['dateordependson'] : null;


		$this->name = !empty($data['name']) ? $data['name'] : null;
		$this->columnname = !empty($data['columnname']) ? $data['columnname'] : null;
		$this->text = !empty($data['text']) ? $data['text'] : null;
		$this->helpertext = !empty($data['helpertext']) ? $data['helpertext'] : null;
		$this->type = !empty($data['type']) ? $data['type'] : null;
		$this->options = !empty($data['options']) ? $data['options'] : null;
		$this->color = !empty($data['color']) ? $data['color'] : null;
		$this->disablejavascript = !empty($data['disablejavascript']) ? $data['disablejavascript'] : null;
		$this->regexpvalidator = !empty($data['regexpvalidator']) ? $data['regexpvalidator'] : null;
		$this->validationtext = !empty($data['validationtext']) ? $data['validationtext'] : null;
		$this->specialvalidator = !empty($data['specialvalidator']) ? $data['specialvalidator'] : null;
		$this->expression = !empty($data['expression']) ? $data['expression'] : null;
		$this->condition = !empty($data['condition']) ? $data['condition'] : null;
		$this->premiumname = !empty($data['premiumname']) ? $data['premiumname'] : null;
		$this->xflat_parameter = !empty($data['xflat_parameter']) ? $data['xflat_parameter'] : null;
		$this->esign_parameter = !empty($data['esign_parameter']) ? $data['esign_parameter'] : null;
		$this->field_type = !empty($data['field_type']) ? $data['field_type'] : null;
		$this->category = !empty($data['category']) ? $data['category'] : null;


	}

	public function toArray(){
		$data = array();
		$data['id'] = $this->id;
		$data['sequence'] = $this->sequence;
		$data['formid'] = $this->formid;
		$data['dependson'] = $this->dependson;
		$data['disablejavascript'] = $this->disablejavascript;
		$data['required'] = $this->required;
		$data['readonly'] = $this->readonly;
		$data['canbehidden'] = $this->canbehidden;
		$data['onlyownrcanchng'] = $this->onlyownrcanchng;
		$data['dateordependson'] = $this->dateordependson;

		$data['name'] = $this->name;
		$data['columnname'] = $this->columnname;
		$data['text'] = $this->text;
		$data['helpertext'] = $this->helpertext;
		$data['type'] = $this->type;
		$data['options'] = $this->options;
		$data['color'] = $this->color;
		$data['regexpvalidator'] = $this->regexpvalidator;
		$data['validationtext'] = $this->validationtext;
		$data['specialvalidator'] = $this->specialvalidator;
		$data['expression'] = $this->expression;
		$data['condition'] = $this->condition;
		$data['premiumname'] = $this->premiumname;
		$data['xflat_parameter'] = $this->xflat_parameter;
		$data['esign_parameter'] = $this->esign_parameter;
		$data['field_type'] = $this->field_type;
		$data['category'] = $this->category;

		return $data;
	}
}

