<?php
namespace Field\Model;
use Oxzion\Model\Entity;

class Field extends Entity{

	protected $data = array(
		'id' => 0,
		'name' => null,
		'columnname' => null,
		'text' => null,
		'helpertext' => null,
		'type' => null,
		'options' => null,
		'color' => null,
		'disablejavascript' => null,
		'regexpvalidator' => null,
		'validationtext' => null,
		'specialvalidator' => null,
		'expression' => null,
		'condition' => null,
		'premiumname' => null,
		'xflat_parameter' => null,
		'esign_parameter' => null,
		'field_type' => null,
		'category' => null,
    );

}