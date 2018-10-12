<?php
namespace Oxzion\Service;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class AbstractService{
    protected $config;
    private $dbAdapter;
    private $sql;

    protected function __construct($config, $dbAdapter){
        $this->config = $config;
        $this->dbAdapter = $dbAdapter;
        if($dbAdapter){
            $this->sql = new Sql($this->dbAdapter);
        }
    }

    protected function beginTransaction(){
        $this->dbAdapter->getDriver()->getConnection()->beginTransaction();
    }

    protected function commit(){
        $this->dbAdapter->getDriver()->getConnection()->commit();
    }

    protected function rollback(){
        $this->dbAdapter->getDriver()->getConnection()->rollback();
    }

    protected function getSqlObject(){
        return $this->sql;
    }

    protected function getAdapter(){
        return $this->dbAdapter;
    }

    protected function executeUpdate($query){
        $statement = $this->sql->prepareStatementForSqlObject($query);
        return $statement->execute();
    }

    protected function executeQuery($query){
        $statement = $this->sql->prepareStatementForSqlObject($query);
        $result = $statement->execute();
        // build result set
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;
    }

}
?>