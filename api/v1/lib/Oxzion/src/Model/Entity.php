<?php
namespace Oxzion\Model;

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

    public function validate($data) {
        $data = $this->_convert($data);
        return true;
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

    protected function _convert($data) {
        if (is_array($data)) {
            return $data;
        } elseif (is_object($data)) {
            return (array) $data;
        } else {
            throw new Exception('Data must be array or object');
        }
    }
    
    public function exchangeArray($data) {
        foreach ($data as $key => $value)
        {
            if (!array_key_exists($key, $this->data)) {
                continue;//throw new \Exception("$key field does not exist in " . __CLASS__);
            }
            $this->data[$key] = $value;
        }
    }
}