<?php
namespace Oxzion\Model;

use Countable;
use Oxzion\InputConverter;
use Oxzion\InvalidInputException;
use Oxzion\ValidationException;
use Oxzion\EntityNotFoundException;
use Oxzion\DataCorruptedException;

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

    protected $table;

    public function __construct($table = NULL)
    {
        $this->table = $table;
    }

    public function count()
    {
        return 1;
    }

    public function __set($key, $val)
    {
        if (array_key_exists($key, $this->data)) {
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
        return $this->data;
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
                if (!array_key_exists($key, $data)) {
                    throw new InvalidInputException("Property ${key} is not specified in the form.", "err.${key}.invalid");
                }

                switch ($data[$key]['type']) {
                    case Entity::INTVAL:
                        $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::INTVAL);
                        break;
                    case Entity::FLOATVAL:
                        $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::FLOATVAL);
                        break;
                    case Entity::DATETYPEVAL:
                        $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::DATETYPEVAL);
                        break;
                    case Entity::UUIDVAL:
                        $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::UUIDVAL);
                        break;
                    case Entity::TIMESTAMPVAL:
                        $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::TIMESTAMPVAL);
                        break;
                    case Entity::STRINGVAL:
                        $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::STRINGVAL);
                        break;
                    case Entity::BOOLEANVAL:
                        $data[$key]['value'] = $inputConverter->checkType($key, $data[$key], 'value', $data[$key]['value'], self::BOOLEANVAL);
                        break;
                    default:
                        throw new InvalidInputException("Parameter ${key} is not a proper value.", "err.${key}.invalid");
                        break;
                }
            }
            catch (InvalidInputException $e) {
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
        $errors = array();
        foreach ($data as $key => $element) {
            if ($element['required']) {
                $value = $element['value'];
                if (!isset($value) || is_null($value)) {
                    $errors[$key] = 'required';
                    continue;
                }
                //These types need special handling because PHP considers values like 0 (int), 0.0 (float), "0" (string) as empty.
                switch($element['type']) {
                    case Entity::INTVAL:
                    case Entity::FLOATVAL:
                    case Entity::BOOLEANVAL:
                        $expv = var_export($value, true);
                        if (0 == strlen($expv)) {
                            $errors[$key] = 'required';
                        }
                    break;
                    case Entity::STRINGVAL:
                        if ((0 == strlen($value)) || (0 == strlen(trim($value, " \t\n\r\0\x0B")))) {
                            $errors[$key] = 'required';
                        }
                    break;
                    default:
                        if (empty($value)) {
                            $errors[$key] = 'required';
                        }
                }
            }
        }
        if (count($errors) > 0) {
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }

    /*
     * Loads data from database using UUID.
     */
    public function loadByUuid($uuid) {
        $tableObj = $this->table->getByUuid($uuid);
        if (is_null($tableObj) || (0 == count($tableObj))) {
            throw new EntityNotFoundException('Entity not found.', ['entity' => $this->table->getTableGateway()->getTable(), 'uuid' => $uuid]);
        }
        $this->assignInternal($tableObj->toArray(), false);
        return $this;
    }

    /*
     * Assigns values from $input to form. Honours 'readonly' flag.
     * Keys in $input that do not exist in the form are ignored.
     */
    public function assign($input) {
        $this->assignInternal($input, true);
    }

    /*
     * Assigns generated/modified values id, UUID and version to given array.
     * id - is assigned only if the input array has no valid id.
     *      Throws exception if the input array has valid id and it does not match the value in the form.
     * uuid - is assigned only if the input array has no valid UUID.
     *      Throws exception if the input array has valid UUID and it does not match the value in the form.
     * version - is assigned always.
     */
/*    public function assignBack(&$input, $includeId = false) {
        if ($includeId) {
            $this->assignBackImmutable($input, 'id');
        }
        $this->assignBackImmutable($input, 'uuid');
        if (array_key_exists('version', $this->data)) {
            $input['version'] = $this->getProperty('version');
        }
    }

    private function assignBackImmutable(&$input, $key) {
        $formValue = $this->getProperty($key);
        $inputValue = array_key_exists($key, $input) ? $input[$key] : NULL;
        if (isset($inputValue) && ($formValue != $inputValue)) {
            throw new DataCorruptedException('Data corrupted.', 
                ['entity' => $this->table->getTableGateway()->getTable(), 'property' => $key]);
        }
        $input[$key] = $formValue;
    }
*/

    public function getGenerated($includeId = false) {
        $arr = array();
        if ($includeId) {
            if (array_key_exists('id', $this->data)) {
                $arr['id'] = $this->getProperty('id');
            }
        }
        if (array_key_exists('uuid', $this->data)) {
            $arr['uuid'] = $this->getProperty('uuid');
        }
        if (array_key_exists('version', $this->data)) {
            $arr['version'] = $this->getProperty('version');
        }
        return $arr;
    }

    /*
     * Assigns values from $input to form. Allows caller to control whether to honour 'readonly' flag.
     * Keys in $input that do not exist in the form are ignored.
     * By default readonly properties are not set. Caller can force setting readonly values 
     * by seting $skipReadonly to FALSE.
     */
    private function assignInternal($input, $skipReadOnly = true) {
        foreach ($input as $key => $value) {
            //Ignore keys in input which don't exist in the form.
            if (!array_key_exists($key, $this->data)) {
                continue;
            }
            $property = &$this->data[$key];
            if (is_array($property)) {
                if ($skipReadOnly && array_key_exists('readonly', $property) && $property['readonly']) {
                    continue;
                }
                else {
                    $property['value'] = $value;
                }
            }
            else {
                $property = $value;
            }
        }
    }

    private function setProperty($key, $value) {
        $property = &$this->data[$key];
        if (is_array($property)) {
            $property['value'] = $value;
        }
        else {
            $property = $value;
        }
    }

    public function getProperty($key) {
        $property = $this->data[$key];
        if (is_array($property)) {
            return $property['value'];
        }
        else {
            return $property;
        }
    }

    public function getProperties($keyArray) {
        $returnArray = array();
        foreach($keyArray as $key) {
            $returnArray[$key] = $this->getProperty($key);
        }
        return $returnArray;
    }

    public function save2() {
        $data = $this->table->save2($this);
        $id = $this->getProperty('id');
        if (!isset($id) || empty($id)) {
            $this->setProperty('id', $data['id']);
        }
        $uuid = $this->getProperty('uuid');
        if (!isset($uuid) || empty($uuid)) {
            $this->setProperty('uuid', $data['uuid']);
        }
        $this->setProperty('version', $data['version']);
        return $data;
    }

    public function setForeignKey($key, $value) {
        $existingValue = $this->getProperty($key);
        if (!isset($existingValue) || empty($existingValue)) {
            $this->setProperty($key, $value);
        }
        else if ($existingValue != $value) {
            throw new DataCorruptedException('Data corrupted.', 
                ['entity' => $this->table->getTableGateway()->getTable(), 'property' => $key, 
		'existingValue' => $existingValue, 'newValue' => $newValue]);
        }
    }

    public function setModifiedBy($value, $key = 'modified_by') {
        $this->setProperty($key, $value);
    }

    public function setModifiedDate($value, $key = 'date_modified') {
        $this->setProperty($key, $value);
    }

    // public function setModifiedByAndDate($modifiedBy, $modifiedDate) {
    //     $this->setModifiedBy($modifiedBy);
    //     $this->setModifiedDate($modifiedDate);
    // }

    public function setCreatedBy($value, $key = 'created_by') {
        $this->setProperty($key, $value);
    }
    
    public function setCreatedDate($value, $key = 'date_created') {
        $this->setProperty($key, $value);
    }

    // public function setCreatedByAndDate($createdBy, $createdDate) {
    //     $this->setCreatedBy($createdBy);
    //     $this->setCreatedDate($createdDate);
    // }
}

