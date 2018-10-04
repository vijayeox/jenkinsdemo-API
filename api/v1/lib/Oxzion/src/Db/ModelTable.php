<?php

namespace Oxzion\Db;

use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

abstract class ModelTable {
    protected $tableGateway;
    protected $adapter;

    private $lastInsertValue;

    abstract public function save(Entity $data);

    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->adapter = $tableGateway->getAdapter();
    }

    private function init(){
        $this->lastInsertValue = null;
    }
    public function fetchAll(array $filter = null) {
        $this->init();
        return $this->tableGateway->select($filter);
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
            try {
                $rows = $this->tableGateway->insert($data);
                if(!isset($rows)){
                    return 0;
                }
                $this->lastInsertValue = $this->tableGateway->getLastInsertValue();
                return $rows;
            } catch (Exception $e){
                return $e->getMessage();
            }
        }

        return $this->tableGateway->update($data, ['id' => $id]);
    }

    public function delete($id, array $filter = null) {
        $this->init();
        if(is_null($filter)){
            $filter = array();
            $filter['id'] = $id;  // You cannot have a filter and an id. If there is filter, then id is irrelavant. 
        }
        return $this->tableGateway->delete($filter);
    }

    public function update(array $data, array $filter) { 
            return $this->tableGateway->update($data,$filter);
    }

    public function getLastInsertValue(){
        return $this->lastInsertValue;
    }
    public function queryExecute($select,$sql){
        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        return $results->toArray();
    }
    public function getSqlObject(){
        return (new Sql($this->adapter));
    }

    public function getTableGateway() {
        return $this->tableGateway;
    }

}
