<?php
namespace Oxzion\Model;

use Oxzion\Model\Entity;
use Oxzion\Model\Model;
use Oxzion\Db\Config;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Db\ModelTable;
use Zend\Db\Sql\Sql;

abstract class Entity{
    protected $data = array();
    protected $parsedata;
    protected $tablename;
    public $tableGateway;
    protected $sql;
    protected $adapter;

    public function __construct($data=null,$obj=null) {
        if($obj){
            $conf = new Config();
            $config = $conf->getConfig();
            $dbAdapter = new Adapter($config['db']);
            $resultSetPrototype = new ResultSet();
            if($obj){
                $resultSetPrototype->setArrayObjectPrototype($obj);
            }
            $this->tableGateway = new TableGateway($this->tablename, $dbAdapter, null, $resultSetPrototype);
            $this->adapter = $this->tableGateway->getAdapter();
            $this->sql = new Sql($this->adapter);
            if ($data!=0 && $data!=null) {
                if (!is_array($data)) {
                    if(is_string($data)){
                        $obj = $this->get($data)->toArray();
                        if ($obj) {
                            $data = $obj;
                        } else {
                            throw new Exception("Error creating ".get_class($this)." with id ".$data." The id not found in database.");
                        }
                    }
                }
                $this->import($data);
            }
        }
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
     //               return VA_Service_Utils::parseInstanceExpression ($this->data[$key]);
         }   else {
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
    public function get($id, array $filter = null){
        $id = (int) $id;
        if(is_null($filter)){
            $filter = array();
        }
        $filter['id'] = $id;
        $rowset = $this->tableGateway->select($filter);
        $row = $rowset->current();
        return $row;
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
    public function queryExecute($select){
        $selectString = $this->sql->getSqlStringForSqlObject($select);
        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        return $results->toArray();
    }
    public function init(){
        $this->lastInsertValue = null;
    }
    public function fetchAll(array $filter = null) {
        $this->init();
        $result = $this->tableGateway->select($filter);
        $data = array();
        while ($result->valid()) {
            $value = $result->current();
            $data[] = $value->toArray();
            $result->next();
        }
        return $data;
    }

    protected function internalSave(array $data)
    {
        $this->init();
        $id = null;
        if(!empty($data['id'])) {
            $id = $data['id'];
        }

        if ( is_null($id) || $id === 0 || empty($id)) {
            $rows = $this->tableGateway->insert($data);
            $rows = $rows->current();
            if(!isset($rows)){
                return 0;
            }
            $this->lastInsertValue = $this->tableGateway->getLastInsertValue();
            return count($rows);
        }

        return $this->tableGateway->update($data, ['id' => $id]);
    }

    public function delete($id, array $filter = null) {
        $this->init();
        if(is_null($filter)){
            $filter = array();
        }
        $filter['id'] = $id;
        return $this->tableGateway->delete($filter);
    }

    public function getLastInsertValue(){
        return $this->lastInsertValue;
    }
    public function getDatabyParams($fieldarray, $where=null, $sortby=null, $groupby=null, $limit=null,$offset=0, $join=array()){
        $select = $this->tableGateway->select();
        $select->from($this->tableGateway,$fieldarray);
        if(!empty($join)){
            foreach ($join as $key => $value) {
                $joinmethod = ($value['joinmethod']) ? $value['joinmethod'] : 'join';
                $select->$joinmethod($value['jointable'], $value['condition'], $value['joinfields']);
            }
        }
        if($where){
            $select->where($where);
        }
        if($sortby){
            $select->order($sortby);
        }
        if($groupby){
            $select->group($groupby);
        }
        if($limit){
            $select->limit($limit,$offset);
        }
        try{
            $rows = $this->tableGateway->fetchAll($select);
        } catch (Exception $e) {
            echo $select."\n";
            echo "<pre>";debug_print_backtrace();
            echo "\n<pre>";print_r($e->getMessage());
            exit();
        }
        if (empty($rows)) { return null; }
        return $rows->toArray();
    }
}