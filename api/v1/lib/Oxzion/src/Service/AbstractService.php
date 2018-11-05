<?php
namespace Oxzion\Service;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class AbstractService {
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

    protected function executeQueryString($query){
        $statement = $this->sql->prepareStatementForSqlObject($query);
        $result = $statement->execute();
        // build result set
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;
    }

/**
Query builder: Code that combines the required parameter to build the query.
Author: Rakshith
Function Name: executeQuerywithParams()
*/
    public function executeQuerywithParams($queryString, $where = NULL, $group = NULL, $order = NULL, $limit = NULL) { //Passing the required parameter to the query statement
     $adapter = $this->getAdapter();
       $query_string = $queryString . " " . $where . " " . $group . " " . $order . " " . $limit; //Combining all the parameters required to build the query statement. We will add more fields to this in the future if required.
       // echo $query_string;exit;
       $statement = $adapter->query($query_string); 
       $result = $statement->execute();
       $resultSet = new ResultSet();
       return $resultSet->initialize($result);
   }   

}
?>