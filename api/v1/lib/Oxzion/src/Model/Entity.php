<?php
namespace Oxzion\Model;

use Oxzion\Utils\ValidationResult;
use Oxzion\ValidationException;
use Countable;

abstract class Entity implements Countable{
    protected $data = array();

    public function __construct() {
        // $this->import($data);
    }

    public function count(){
        return 1;
    }
    public function __set($key, $val) {
        if (array_key_exists($key, $this->data)) {
            $this->data[$key] = ($val === '') ? NULL : $val;
        }
    }

    public function setParseData($val) {
        $this->parsedata=$val;
    }

    public function __get($key) {
        if (array_key_exists($key, $this->data)) {
            if ($this->parsedata) {
     			//return VA_Service_Utils::parseInstanceExpression ($this->data[$key]);
            }
            else {
                return $this->data[$key];
            }

        }
    }

    public function __isset($key) {
        return (array_key_exists($key, $this->data)) ? isset($this->data[$key]) : false;
    }

    public function __unset($key) {
        if (array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
        } else {
            return null;
        }
    }

    public function getKeyArray() {
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
    public function validate(){

    }

    public function import($data) {
        foreach ($data as $key => $val) {
            $this->__set($key, $val);
        }
        return $this;
    }

    public function toArray() {
        return $this->data;
    }

    public function exchangeArray($data) {
        // $this->data = array_intersect_key($this->data, $data);
        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $this->data)) {
                continue;//throw new \Exception("$key field does not exist in " . __CLASS__);
            }
            $this->data[$key] = $value;
        }
    }

//This function is to check if the data passed through the post command has NULL, "" and empty Value. If it has any of these then an error message is shown
    public function validateWithParams($dataArray) {
        $errors = array();
        foreach($dataArray as $data) {
            if ($this->data[$data] === null || $this->data[$data] === "" || empty($this->data[$data])) {
                $errors[$data] = 'required';
            }
        }
        if (count($errors) > 0) {
            $validationException = new ValidationException();
            $validationException->setErrors($errors);
            throw $validationException;
        }
    }
}