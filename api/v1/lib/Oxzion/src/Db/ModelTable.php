<?php

namespace Oxzion\Db;

use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Model;
use Oxzion\Model\Entity;
use Oxzion\Db\Config;
use Zend\Db\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

abstract class ModelTable {
	public $tableGateway;
    public $tablename;
    private $lastInsertValue;

    abstract public function save(Model $data);

    public function __construct($entity) {
        $conf = new Config();
        $config = $conf->getConfig();
        $dbAdapter = new Adapter\Adapter($config['db']);
        $resultSetPrototype = new ResultSet();
        if($entity){
            $resultSetPrototype->setArrayObjectPrototype($entity);
        }
        $this->tableGateway = new TableGateway($this->tablename, $dbAdapter, null, $resultSetPrototype);
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

    public function get($id, array $filter = null){
        $this->init();
    	$id = (int) $id;
        if(is_null($filter)){
            $filter = array();
        }

        $filter['id'] = $id;
        $rowset = $this->tableGateway->select($filter);

        $row = $rowset->current();
        
        return $row;
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

    public function delete($id, array $filter = null)
    {
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

}