<?php

namespace Oxzion;

use Datetime;
use Zend\Validator\Uuid;
use Oxzion\InvalidInputException;

class Type {
    const INTEGER = 'integer';
    const FLOAT = 'float';
    const DATE = 'datetype';
    const UUID = 'uuid';
    const TIMESTAMP = 'timestamp';
    const STRING = 'string';
    const BOOLEAN = 'boolean';

	public static function convert($value = NULL, $type = self::STRING) {
		if (!isset($value) || is_null($value)) {
			return NULL;
		}
		if (!isset($type) || is_null($type)) {
			throw new Exception('type parameter is required, it cannot be NULL.');
		}
		switch($type) {
			case self::INTEGER:
				if ('integer' == gettype($value)) {
					return $value;
				}
				if (('string' == gettype($value)) && 
					!strpos($value, '.')  && 
					is_numeric($value)) {
						return intval($value);
				}
				throw new InvalidInputException("'${value}' is not valid integer value.", NULL);
			break;
			case self::FLOAT:
				if (('double' == gettype($value)) || ('float' == gettype($value))) {
					return $value;
				}
				if (is_numeric($value)) {
					return floatval($value);
				}
				else {
					throw new InvalidInputException("'${value}' is not valid float value.", NULL);
				}
			break;
			case self::DATE:
				if ('string' == gettype($value)) {
					$dateTime = DateTime::createFromFormat('Y-m-d', $value);
					if ($dateTime) {
						$converted = $dateTime->format('Y-m-d');
						if ($converted == $value) {
							return $converted;
						}
					}
				}
				else {
					if ('DateTime' == get_class($value)) {
						return $value->format('Y-m-d');
					}	
				}
				throw new InvalidInputException("'${value}' is not valid date value.", NULL);
			break;
			case self::UUID:
				if ('string' == gettype($value)) {
					$uuidValidator = new Uuid();
					if ($uuidValidator->isValid($value)) {
						return $value;
					}
				}
				throw new InvalidInputException("'${value}' is not valid UUID value.", NULL);
			break;
			case self::STRING:
				if (is_string($value)) {
					return $value;
				}
				switch(gettype($value)) {
					case 'integer':
					case 'double':
						return strval($value);
					break;
					case 'boolean':
						return $value ? 'true' : 'false';
					break;
				}
				throw new InvalidInputException("'${value}' is not string value OR it cannot be converted to string.", NULL);
			break;
			case self::TIMESTAMP:
				if ('string' == gettype($value)) {
					$dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $value);
					if ($dateTime) {
						$converted = $dateTime->format('Y-m-d H:i:s');
						if ($converted == $value) {
							return $converted;
						}
					}
				}
				else {
					if ('DateTime' == get_class($value)) {
						return $value->format('Y-m-d H:i:s');
					}
				}
				throw new InvalidInputException("'${value}' is not valid timestamp value.", NULL);
			break;
			case self::BOOLEAN:
				if (is_bool($value)) {
					return $value;
				}
				if (is_int($value)) {
					return (0 == $value) ? FALSE : TRUE;
				}
				if(is_string($value))
				{
					$value = strtoupper($value);
					switch($value) {
						case '1':
						case 'TRUE':
						case 'ON':
						case 'YES':
							return TRUE;
						break;
						case '0':
						case 'FALSE':
						case 'OFF':
						case 'NO':
							return FALSE;
						break;
					}
				}
				throw new InvalidInputException("'${value}' is not valid boolean value.", NULL);
			break;
			default:
				throw new InvalidInputException("'${type}' is not handled. Please contact the developers.", NULL);
		}
	}
}