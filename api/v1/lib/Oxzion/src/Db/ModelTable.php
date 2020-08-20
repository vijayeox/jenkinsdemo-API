<?php

namespace Oxzion\Db;

use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Oxzion\VersionMismatchException;
use Oxzion\InsertFailedException;
use Oxzion\UpdateFailedException;
use Oxzion\ServiceException;
use Oxzion\MultipleRowException;
use Oxzion\ParameterRequiredException;
use Oxzion\Utils\UuidUtil;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Exception;

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
        if (0 == count($rowset)) {
            return NULL;
        }
        if (count($rowset) > 1) {
            throw new MultipleRowException('Multiple rows found when queried by UUID.', 
                ['table' => $this->tableGateway->getTable(), 'uuid' => $uuid]);
        }
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
            throw new ServiceException($e->getMessage(),'save.error');
        }
    }

    private function setCreatedByAndDate(&$data) {
        if (array_key_exists('created_by', $data) && empty($data['created_by'])) {
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        }
        if (array_key_exists('date_created', $data) && empty($data['date_created'])) {
            $data['date_created'] = date('Y-m-d H:i:s');
        }
    }

    private function setModifiedByAndDate(&$data) {
        if (array_key_exists('modified_by', $data) && empty($data['mdified_by'])) {
            $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        }
        if (array_key_exists('date_modified', $data) && empty($data['date_modified'])) {
            $data['date_modified'] = date('Y-m-d H:i:s');
        }
    }

    private function checkAndIncrementVersion(&$data) {
        $version = $data['version'];
        if(!isset($version) || is_null($version)) {
            throw new ParameterRequiredException('Version number is required.', ['version']);
        }
        try {
            $recordFromDb = $this->get($data['id'], array())->toArray();
        }
        catch(Exception $e) {
            throw new UpdateFailedException('Database update failed.', 
                ['table' => $this->tableGateway->getTable(), 'data' => $data], 
                UpdateFailedException::ERR_CODE_INTERNAL_SERVER_ERROR, UpdateFailedException::ERR_TYPE_ERROR, $e);
        }
        if ($recordFromDb['version'] != $version) {
            throw new VersionMismatchException($recordFromDb);
        }
        $data['version'] = $version + 1;
    }

    public function internalSave2(array $inputData)
    {
        $data = array();
        foreach ($inputData as $key => $value) {
            if (is_array($value)) {
                $v = $value['value'];
            }
            else {
                $v = $value;
            }
            $data[$key] = $v;
        }

        $this->init();
        $id = NULL;
        if (array_key_exists('id', $data) && isset($data['id'])) {
            $id = $data['id'];
        }

        if (!isset($id) || is_null($id) || (0 == $id) || empty($id)) {
            $data['uuid'] = UuidUtil::uuid();
            if (array_key_exists('version', $data)) {
                $data['version'] = 1; //Starting version number when the row is inserted in the database.
            }
            $this->setCreatedByAndDate($data);
            try {
                $rows = $this->tableGateway->insert($data);
            }
            catch(Exception $e) {
                throw new InsertFailedException('Database insert failed.', 
                    ['table' => $this->tableGateway->getTable(), 'data' => $data],
                    InsertFailedException::ERR_CODE_INTERNAL_SERVER_ERROR, InsertFailedException::ERR_TYPE_ERROR, $e);
            }
            if(!isset($rows) || (1 != $rows)) {
                throw new InsertFailedException('Database insert failed.', 
                    ['table' => $this->tableGateway->getTable(), 'data' => $data]);
            }
            $this->lastInsertValue = $this->tableGateway->getLastInsertValue();
            $data['id'] = $this->lastInsertValue;
            return $data;
        }
        else {
            $whereCondition = ['id' => $id];
            if (array_key_exists('version', $data)) {
                //IMPORTANT: version property in $whereCondition should be set before calling checkAndIncrementVersion
                $whereCondition['version'] = $data['version'];
                $this->checkAndIncrementVersion($data);
            }
            $this->setModifiedByAndDate($data);
            try {
                $rows = $this->tableGateway->update($data, $whereCondition);
            }
            catch(Exception $e) {
                throw new UpdateFailedException('Database update failed.', 
                    ['table' => $this->tableGateway->getTable(), 'data' => $data], 
                    UpdateFailedException::ERR_CODE_INTERNAL_SERVER_ERROR, UpdateFailedException::ERR_TYPE_ERROR, $e);
            }
            if (!isset($rows) || (1 != $rows)) {
                throw new UpdateFailedException('Database update failed.', 
                    ['table' => $this->tableGateway->getTable(), 'data' => $data]);
            }
            return $data;
        }
    }
}

