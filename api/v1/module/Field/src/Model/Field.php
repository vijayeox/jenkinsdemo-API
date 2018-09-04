<?php
namespace Field\Model;
use Oxzion\Model\Entity;

class Field extends Entity{

	protected $data = array(
		'id'=>0,
		'name'=>NULL,
		'columnname'=>NULL,
		'text'=>NULL,
		'helpertext'=>NULL,
		'type'=>NULL,
		'options'=>NULL,
		'color'=>NULL,
		'regexpvalidator'=>NULL,
		'validationtext'=>NULL,
		'specialvalidator'=>NULL,
		'expression'=>NULL,
		'condition'=>NULL,
		'premiumname'=>NULL,
		'xflat_parameter'=>NULL,
		'esign_parameter'=>NULL,
		'field_type'=>NULL,
		'category'=>NULL,
    );

}