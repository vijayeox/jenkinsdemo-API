<?php
namespace Oxzion\Model;

use Countable;
use Oxzion\InputConverter;
use Oxzion\InvalidInputException;
use Oxzion\ValidationException;

abstract class Entity implements Countable
{
    protected $data;
    const INTVAL = 'int';
    const FLOATVAL = 'float';
    const DATETYPEVAL = 'datetype';
    const UUIDVAL = 'uuid';
    const TIMESTAMPVAL = 'timestamp';
    const STRINGVAL = 'string';
    const BOOLEANVAL = 'boolean';

    public function __construct()
    {
        // $this->import($data);
    }

    public function count()
    {
        return 1;
    }
    public function __set($key, $val)
    {
        if (array_key_exists($key, $this->data)) {
            if(isset($this->data['id']) && is_array($this->data['id'])){
                $this->data[$key]['value'] = ($val === '') ? null : $val;
            }
            $this->data[$key] = ($val === '') ? null : $val;
        }
    }

    public function setParseData($val)
    {
        $this->parsedata = $val;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->data)) {
            if ($this->parsedata) {
                //return VA_Service_Utils::parseInstanceExpression ($this->data[$key]);
            } else {
                if(isset($this->data['id']) && is_array($this->data['id'])){
                    return $this->data[$key]['value'];
                }
                return $this->data[$key];
            }
        }
    }

    public function __isset($key)
    {
        return (array_key_exists($key, $this->data)) ? isset($this->data[$key]) : false;
    }

    public function __unset($key)
    {
        if (array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
        } else {
            return null;
        }
    }

    public function getKeyArray()
    {
        $data = array();
        foreach ($this->data as $key => $val) {
            $data[] = $key;
        }
        return $data;
    }

    /**
     * This method should be overridden by base classes to perform field level validations
     * this method should throw ValidationException if there are any errors
     */
    public function validate()
    {
    }

    public function import($data)
    {
        foreach ($data as $key => $val) {
            $this->__set($key, $val);
        }
        return $this;
    }

    public function toArray()
    {
        $result = $this->data;
        if(is_array($result['id'])){
            $result = array();
            foreach ($this->data as $key => $value) {
                $result[$key] = $value['value'];
            }
        }
        return $result;
    }

    public function exchangeArray($data)
    {
        // $this->data = array_intersect_key($this->data, $data);
        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $this->data)) {
                continue; //throw new \Exception("$key field does not exist in " . __CLASS__);
            }
            $this->data[$key] = $value;
        }
    }

    public function exchangeWithSpecificKey($data, $keyname, $validation = false)
    {
        $errors = array();
        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $this->data)) {
                continue;
            }
            if ($validation == true) {
                if (array_key_exists('readonly', $this->data[$key])) {
                    $this->data[$key][$keyname] = $value;
                } else {
                    $errors[$key] = 'readonly';
                }

            } else {
                $this->data[$key][$keyname] = $value;
            }
        }
        if (count($errors) > 0) {
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        } else {
            return;
        }
    }

    //This function is to check if the data passed through the post command has NULL, "" and empty Value. If it has any of these then an error message is shown
    public function validateWithParams($dataArray)
    {
        $errors = array();
        foreach ($dataArray as $data) {
            if ($this->data[$data] === null || $this->data[$data] === "" || empty($this->data[$data])) {
                $errors[$data] = 'required';
            }
        }
        if (count($errors) > 0) {
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        } else {
            return;
        }
    }

    public function completeValidation()
    {
        $this->typeChecker();
        $this->checkRequireFields();
    }

    public function typeChecker()
    {
        $data = $this->data;
        $errors = array();
        $inputConverter = new InputConverter();
        foreach ($data as $key => $value) {
            try {
                if ($data[$key]['type'] == null || $data[$key]['type'] == " " || empty($data[$key]['type'])) {
                    throw new InvalidInputException("Parameter ${key} is not Not specified.", "err.${key}.invalid");
                } else {
                    switch ($data[$key]['type']) {
                        case 'int':
                            $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::INTVAL);
                            break;
                        case 'float':
                            $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::FLOATVAL);
                            break;
                        case 'datetype':
                            $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::DATETYPEVAL);
                            break;
                        case 'uuid':
                            $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::UUIDVAL);
                            break;
                        case 'timestamp':
                            $data[$key]['value'] = date_format(date_create($data[$key]['value']),'Y-m-d H:i:s');
                            $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::TIMESTAMPVAL);
                            break;
                        case 'string':
                            $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::STRINGVAL);
                            break;
                        case 'boolean':
                            $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::BOOLEANVAL);
                            break;
                        default:
                            throw new InvalidInputException("Parameter ${key} is not a proper value.", "err.${key}.invalid");
                            break;
                    }
                }
            } catch (InvalidInputException $e) {
                // print_r($e->getMessage());exit;
                array_push($errors, array('message' => $e->getMessage(), 'messageCode' => $e->getMessageCode()));
            }
        }
        if (count($errors) > 0) {
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }

    public function checkRequireFields()
    {
        $data = $this->data;
        $errors = $dataArray = array();
        foreach ($data as $key => $value) {
            if ($data[$key]['required'] == true && empty($data[$key]['value'])) {
                $errors[$key] = 'required';
            }
        }
        if (count($errors) > 0) {
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        } else {
            return;
        }
    }
}
