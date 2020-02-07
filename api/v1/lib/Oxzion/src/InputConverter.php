<?php
namespace Oxzion;
use Oxzion\InvalidInputException;
use Zend\Validator\Uuid;
use \Datetime;

class InputConverter {
	public static function checkType($fieldname,$parameters, $name, $defaultValue=null, $type='string') {
		if (!isset($parameters) || !isset($parameters[$name])) {
			if (isset($defaultValue)) {
				return $defaultValue;
			}
			else {
				return null;
			}
		}
		$value = $parameters[$name];
		switch ($type) {
			case 'int':
				if (is_numeric($value)) {
					$numericValue = intval($value); //Convert to numeric value.
					return $numericValue;
				}
				else {
					throw new InvalidInputException("Parameter ${fieldname}='${value}' not an integer.","err.${fieldname}.invalid");
				}
			break;
			case 'float':
				if (is_numeric($value)) {
					$numericValue = floatval($value); //Convert to numeric value.
					return $value;
				}
				else {
					throw new InvalidInputException("Parameter ${fieldname}='${value}' not an float value.","err.${fieldname}.invalid");
				}
			break;
			case 'datetype':
				if (DateTime::createFromFormat('Y-m-d', $value)) {
					return $value; //Convert to numeric value.
				}
				else {
					throw new InvalidInputException("Parameter ${fieldname}='${value}' not an datetype value.","err.${fieldname}.invalid");
				}
			break;
			case 'uuid':
				$validator = new Uuid();
				if ($validator->isValid($value)) {
					return $value; //Convert to numeric value.
				}
				else {
					throw new InvalidInputException("Parameter ${fieldname}='${value}' not an UUID value.","err.${fieldname}.invalid");
				}
			break;
			case 'string':
				if (is_string($value)) {
					return $value; //Convert to numeric value.
				}
				else {
					throw new InvalidInputException("Parameter ${fieldname}='${value}' not a string value.", "err.${fieldname}.invalid");
				}
			break;
			case 'timestamp':
				if (\DateTime::createFromFormat('Y-m-d H:i:s', $value)) {
					return $value; //Convert to numeric value.
				}
				else {
					throw new InvalidInputException("Parameter ${fieldname}='${value}' not a proper timestamp value.", "err.${fieldname}.invalid");
				}
			break;
			case 'boolean':
				if(is_string($value))
				{
					if($value === '0' || $value === '1')
						$value = (int)$value;
				}
				if ((is_bool($value)) || (is_int($value) && ($value == 0)) || (is_int($value) && ($value == 1)) ) {
					return $value; //Convert to numeric value.
				}
				else {
					throw new InvalidInputException("Parameter ${fieldname}='${value}' not a boolean value.", "err.${fieldname}.invalid");
				}
			break;
			default:
				throw new InvalidInputException("Parameter ${fieldname}='${value}' is not a correct type.", "err.${fieldname}.invalid");
			break;
		}
	}
}
?>