<?php

namespace Oxzion\Db;

use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Model;

abstract class ModelTable {
	protected $tableGateway;

    private $lastInsertValue;

    abstract public function save(Model $data);

    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
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