<?php

namespace Metaform\Model;

use Oxzion\Model\Entity;

class Metafield extends Entity{
	protected $data = array(
		'id' => NULL,
		'name' => NULL,
		'columnname' => NULL,
		'text' => NULL,
		'helpertext' => NULL,
		'type' => NULL,
		'options' => NULL,
		'color' => NULL,
		'regexpvalidator' => NULL,
		'validationtext' => NULL,
		'specialvalidator' => NULL,
		'expression' => NULL,
		'condition' => NULL,
		'disablejavascript'=>NULL,
		'premiumname' => NULL,
		'xflat_parameter' => NULL,
		'esign_parameter' => NULL,
		'field_type' => NULL,
		'category' => 0,
		'sequence'=>NULL,
		'formid'=>0,
		'dependson'=>NULL,
		'required'=>NULL,
		'readonly'=>NULL,
		'canbehidden'=>NULL,
		'onlyownrcanchng'=>NULL,
		'dateordependson'=>NULL,
	);
	
}

