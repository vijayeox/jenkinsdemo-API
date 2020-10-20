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
				if (is_numeric($value)) {
					return intval($value);
				}
				else {
					throw new InvalidInputException("'${value}' is not integer value.", NULL);
				}
			break;
			case self::FLOAT:
				if (is_numeric($value)) {
					return floatval($value);
				}
				else {
					throw new InvalidInputException("'${value}' is not float value.", NULL);
				}
			break;
			case self::DATE:
				if (DateTime::createFromFormat('Y-m-d', $value)) {
					return $value;
				}
				else {
					throw new InvalidInputException("'${value}' is not date value.", NULL);
				}
			break;
			case self::UUID:
				$uuidValidator = new Uuid();
				if ($uuidValidator->isValid($value)) {
					return $value;
				}
				else {
					throw new InvalidInputException("'${value}' is not UUID value.", NULL);
				}
			break;
			case self::STRING:
				if (is_string($value)) {
					return $value;
				}
				else {
					throw new InvalidInputException("'${value}' is not string value.", NULL);
				}
			break;
			case self::TIMESTAMP:
				if (DateTime::createFromFormat('Y-m-d H:i:s', $value)) {
					return $value;
				}
				else {
					throw new InvalidInputException("'${value}' is not timestamp value.", NULL);
				}
			break;
			case self::BOOLEAN:
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
						default:
							throw new InvalidInputException("'${value}' is not boolean value.", NULL);
					}
				}
				if (is_bool($value)) {
					return $value;
				}
				if (is_int($value)) {
					return (0 == $value) ? FALSE : TRUE;
				}
				throw new InvalidInputException("'${value}' is not boolean value.", NULL);
			break;
			default:
				throw new InvalidInputException("'${type}' is not handled.", NULL);
		}
	}
}