<?php

namespace Oxzion\Db;

use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

abstract class ModelTable
{
    protected $tableGateway;
    protected $adapter;

    private $lastInsertValue;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->adapter = $tableGateway->getAdapter();
    }

    abstract public function save(Entity $data);

    public function fetchAll(array $filter = null)
    {
        $this->init();
        return $this->tableGateway->select($filter);
    }

    protected function init()
    {
        $this->lastInsertValue = null;
    }

    public function get($id, array $filter = null)
    {
        $this->init();
        if (is_null($filter)) {
            $filter = array();
        }

        $filter['id'] = (int)$id;
        $rowset = $this->tableGateway->select($filter);

        $row = $rowset->current();

        return $row;
    }

    public function getByUuid($uuid, array $filter = null)
    {
        $this->init();
        if (is_null($filter)) {
            $filter = array();
        }

        $filter['uuid'] = $uuid;

        $rowset = $this->tableGateway->select($filter);

        $row = $rowset->current();

        return $row;
    }

    public function delete($id, array $filter = null)
    {
        $this->init();
        if (is_null($filter)) {
            $filter = array();
            $filter['id'] = $id;  // You cannot have a filter and an id. If there is filter, then id is irrelavant.
        } else {
            $filter['id'] = $id;
        }
        return $this->tableGateway->delete($filter);
    }

    public function deleteByUuid($uuid, array $filter = null)
    {
        $this->init();
        if (is_null($filter)) {
            $filter = array();
            $filter['uuid'] = $uuid;  // You cannot have a filter and an id. If there is filter, then id is irrelavant.
        } else {
            $filter['uuid'] = $uuid;
        }
        return $this->tableGateway->delete($filter);
    }

    public function update(array $data, array $filter)
    {
        return $this->tableGateway->update($data, $filter);
    }

    public function getLastInsertValue()
    {
        return $this->lastInsertValue;
    }

    public function queryExecute($select, $sql)
    {
        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        return $results->toArray();
    }

    public function getSqlObject()
    {
        return (new Sql($this->adapter));
    }

    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    protected function internalSave(array $data)
    {
        $this->init();
        $id = null;
        if (!empty($data['id'])) {
            $id = $data['id'];
        }
        try {
            if (is_null($id) || $id === 0 || empty($id)) {
                $rows = $this->tableGateway->insert($data);
                if (!isset($rows)) {
                    return 0;
                }
                $this->lastInsertValue = $this->tableGateway->getLastInsertValue();
                return $rows;
            }
            return $this->tableGateway->update($data, ['id' => $id]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    protected function internalSave2(array $data)
    {
        foreach ($data as $key => $value) {
            if(!empty($value['value']))
                $data[$key] = $value['value'];
            else
                unset($data[$key]);
        }
        $this->init();
        $id = null;
        if (!empty($data['id'])) {
            $id = $data['id'];
        }
        try {
            if (is_null($id) || $id === 0 || empty($id)) {
                $rows = $this->tableGateway->insert($data);
                if (!isset($rows)) {
                    return 0;
                }
                $this->lastInsertValue = $this->tableGateway->getLastInsertValue();
                return $rows;
            }
            return $this->tableGateway->update($data, ['id' => $id]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
