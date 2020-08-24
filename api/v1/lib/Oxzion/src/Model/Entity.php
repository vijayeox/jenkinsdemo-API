<?php
namespace Oxzion\Model;

use Countable;
use Exception;
use ParseError;
use Oxzion\Type;
use Oxzion\InvalidInputException;
use Oxzion\ValidationException;
use Oxzion\EntityNotFoundException;
use Oxzion\DataCorruptedException;
use Oxzion\ParameterRequiredException;
use Oxzion\InvalidPropertyValueException;

abstract class Entity implements Countable
{
    protected $data = NULL;
    protected $table;

    public function __construct($table = NULL)
    {
        $this->table = $table;
        if (!isset($this->data) || is_null($this->data)) {
            $this->data = [];
            $model = &$this->getModel();
            if (!isset($model) || is_null($model)) {
                return;
            }
            foreach($model as $propName => $propDef) {
                $this->data[$propName] = array_key_exists('value', $propDef) ? $propDef['value'] : NULL;
            }
        }
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

    /*
     * Child classes must override this method. $MODEL instance should be
     * returned by reference.
     * 
     * TODO:Convert this method to abstract method when all the child classes have been migrated.
     */
    protected function &getModel() {
        return NULL;
    }

    private function runDynamicValidationIfExists($property, $value, $propDef) {
        if (!array_key_exists('dynamicValidation', $propDef)) {
            return;
        }
        $code = $propDef['dynamicValidation'];
        $data = $this->data;
        try {
            $result = eval("use \Oxzion\InvalidPropertyValueException;\r\n" . $code);
            if (isset($result) && !is_null($result)) {
                throw new InvalidPropertyValueException("Invalid value '${value}' for property '${property}'.",
                ['property' => $property, 'value' => $value, 'error' => $result]);
            }
        }
        catch(ParseError $e) {
            throw new InvalidPropertyValueException("Validator code parse error for property '${property}'.",
                ['property' => $property, 'error' => 'Validator code parse error:' . $e->getMessage()]);
        }
    }

    private function validateAndConvert($property, $value) {
        $model = &$this->getModel();
        if (!isset($model) || is_null($model)) {
            return $value; //Any value is valid when model is not set.
        }
        $propDef = $model[$property];
        if (!isset($propDef) || is_null($propDef)) {
            return $value; //Any value is valid when the property definition is not set for a property.
        }
        $this->runDynamicValidationIfExists($property, $value, $propDef);
        try {
            $convertedValue = Type::convert($value, $propDef['type']);
        }
        catch (InvalidInputException $e) {
            throw new InvalidPropertyValueException("Invalid value '${value}' for property '${property}'.",
                ['property' => $property, 'value' => $value, 'error' => 'type']);
        }
        if ($this->isRequired($property) && $this->isEmpty($convertedValue)) {
            throw new InvalidPropertyValueException("Invalid value '${value}' for property '${property}'.",
                ['property' => $property, 'value' => $value, 'error' => 'required']);
        }
        return $convertedValue;
    }

    /*
     * This method only runs 'required' checks because data type is validated when 
     * the values are assigned to $data array in this class.
     */
    public function validate() {
        $errors = array();
        foreach($this->data as $property => $value) {
            if ($this->isRequired($property) && $this->isEmpty($value)) {
                $errors[$property] = ['error' => 'required', 'value' => $this->data[$property]];
            }
        }
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    private function isEmpty($value) {
        if (!isset($value) || is_null($value)) {
            return true;
        }
        switch(gettype($value)) {
            case 'string':
                return ((0 == strlen($value)) || (0 == strlen(trim($value, " \t\n\r\0\x0B")))) ? true : false;
            break;
            case 'boolean':
                return $value;
            break;
            default:
                $strVal = print_r($value, true);
                return (0 == strlen($strVal)) ? true : false;
        }
    }

    private function isReadOnly($property) {
        $model = &$this->getModel();
        if (!isset($model) || is_null($model)) {
            return false; //Everything is read-write when model is not set.
        }
        $propDef = $model[$property];
        if (!isset($propDef) || is_null($propDef)) {
            return false; //Property is read-write when property definition is not set in the model.
        }
        return $propDef['readonly'] ? true : false;
    }

    private function isRequired($property) {
        $model = &$this->getModel();
        if (!isset($model) || is_null($model)) {
            return false; //Everything is optional when model is not set.
        }
        $propDef = $model[$property];
        if (!isset($propDef) || is_null($propDef)) {
            return false; //Property is optional when property definition is not set in the model.
        }
        return $propDef['required'] ? true : false;
    }

    /*
     * Assigns values from $input to form. Allows caller to control whether to honour 'readonly' flag.
     * Keys in $input that do not exist in the form are ignored.
     * By default readonly properties are not set. Caller can force setting readonly values 
     * by seting $skipReadonly to FALSE.
     * 
     * IMPORTANT: This method MUST BE PRIVATE to avoid problems with callers
     * setting properties without needed checks.
     */
    private function assignInternal($input, $skipReadOnly = true) {
        $errors = array();
        foreach ($input as $property => $value) {
            //Ignore properties in input which don't exist in the form.
            if (!array_key_exists($property, $this->data)) {
                continue;
            }
            //Ignore read-only properties - if $skipReadOnly is true.
            if ($skipReadOnly && $this->isReadOnly($property)) {
                continue;
            }
            try {
                $this->data[$property] = $this->validateAndConvert($property, $value);
            }
            catch (InvalidPropertyValueException $e) {
                $errors[$property] = ['value' => $value, 'error' => $e->getContextData()['error']];
            }
        }
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    /*
     * Loads data from database using UUID.
     */
    public function loadByUuid($uuid) {
        $row = $this->table->getByUuid($uuid);
        if (is_null($row) || (0 == count($row))) {
            throw new EntityNotFoundException('Entity not found.', ['entity' => $this->table->getTableGateway()->getTable(), 'uuid' => $uuid]);
        }
        $this->assignInternal($row->toArray(), false);
        return $this;
    }

    /*
     * Assigns values from $input to form properties. Honours 'readonly' flag.
     * Keys in $input that do not exist in the form are ignored.
     * Throws ParameterRequiredException if 'version' is not set in $input.
     */
    public function assign($input) {
        //Make sure 'version' is set if this is update.
        $id = $this->data['id'];
        $uuid = $this->data['uuid'];
        $isIdValid = (!is_null($id) && (0 != $id) && !empty($id));
        $isUuidValid = (!is_null($uuid) && !empty($uuid));
        $isVersionInModel = array_key_exists('version', $this->data);
        $isVersionSet = !is_null($input) && isset($input) && array_key_exists('version', $input);
        //Existence of valid id and UUID means record is being updated. Therefore version is needed.
        if ($isVersionInModel && ($isIdValid || $isUuidValid) && !$isVersionSet) {
            throw new ParameterRequiredException('Version number is required.', ['version']);
        }
        //All ok. Go ahead and assign values.
        $this->assignInternal($input, true);
    }

    /*
     * Returns generated values 'uuid' and 'version' in an array.
     * 'id' value is also returned in the array if $includeId is TRUE.
     * 'id' value is NOT returned in the array if $includeId is not set or set to FALSE.
     */
    public function getGenerated($includeId = false) {
        $arr = array();
        if ($includeId) {
            $id = $this->data['id'];
            if (isset($id) && !is_null($id)) {
                $arr['id'] = $id;
            }
        }
        $uuid = $this->data['uuid'];
        if (isset($uuid) && !is_null($uuid)) {
            $arr['uuid'] = $uuid;
        }
        if (array_key_exists('version', $this->data)) {
            $version = $this->data['version'];
            if (isset($version) && !is_null($version)) {
                $arr['version'] = $version;
            }
        }
        return $arr;
    }

    public function getProperty($property) {
        if (!array_key_exists($property, $this->data)) {
            throw new Exception("Property '${property}' is not defined in the form.");
        }
        return $this->data[$property];
    }

    /*
     * Gets properties specified in $keyArray. Gets all properties except 'id' 
     * when $keyArray is NULL or empty. Gets 'id' also if $includeId is set to TRUE.
     */
    public function getProperties($propArray = NULL, $includeId = false) {
        $returnArray = array();
        if (!isset($propArray) || is_null($propArray) || empty($propArray)) {
            foreach($this->data as $key => $value) {
                if (!$includeId && ('id' == $key)) {
                    continue;
                }
                $returnArray[$key] = $this->data[$key];
            }
        }
        else {
            foreach($propArray as $prop) {
                if (!$includeId && ('id' == $prop)) {
                    continue;
                }
                $returnArray[$prop] = $this->data[$prop];
            }
        }
        return $returnArray;
    }

    public function save() {
        $this->validate();
        $savedData = $this->table->internalSave2($this->data);
        $id = $this->data['id'];
        if (!isset($id) || is_null($id) || empty($id)) {
            $this->data['id'] = $savedData['id'];
        }
        $uuid = $this->data['uuid'];
        if (!isset($uuid) || is_null($uuid) || empty($uuid)) {
            $this->data['uuid'] = $savedData['uuid'];
        }
        if (array_key_exists('version', $this->data)) {
            $this->data['version'] = $savedData['version'];
        }
    }

    public function setForeignKey($key, $value, $force = false) {
        if (!array_key_exists($key, $this->data)) {
            throw new Exception("Property '${key}' is not defined in the form.");
        }
        $existingValue = $this->data[$key];
        $convertedValue = $this->validateAndConvert($key, $value);
        if (isset($existingValue) && !is_null($existingValue)) {
            if (!$force && ($existingValue != $convertedValue)) {
                throw new DataCorruptedException('Data corrupted.', 
                    ['entity' => $this->table->getTableGateway()->getTable(), 'property' => $key, 
                    'existingValue' => $existingValue, 'newValue' => $value]);
            }
        }
        $this->data[$key] = $convertedValue;
    }

    public function setModifiedBy($value, $property = 'modified_by') {
        if (!array_key_exists($property, $this->data)) {
            throw new Exception("Property '${property}' is not defined in the form.");
        }
        $this->data[$property] = $this->validateAndConvert($property, $value);
    }

    public function setModifiedDate($value, $property = 'date_modified') {
        if (!array_key_exists($property, $this->data)) {
            throw new Exception("Property '${property}' is not defined in the form.");
        }
        $this->data[$property] = $this->validateAndConvert($property, $value);
    }

    public function setCreatedBy($value, $property = 'created_by') {
        if (!array_key_exists($property, $this->data)) {
            throw new Exception("Property '${property}' is not defined in the form.");
        }
        $this->data[$property] = $this->validateAndConvert($property, $value);
    }
    
    public function setCreatedDate($value, $property = 'date_created') {
        if (!array_key_exists($property, $this->data)) {
            throw new Exception("Property '${property}' is not defined in the form.");
        }
        $this->data[$property] = $this->validateAndConvert($property, $value);
    }
}
