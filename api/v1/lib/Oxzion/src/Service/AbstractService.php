<?php
namespace Oxzion\Service;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\ParameterContainer;

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
    public function executeQuerywithParams($queryString, $where = NULL, $group = NULL, $order = NULL, $limit = NULL) {   
       //Passing the required parameter to the query statement
       $adapter = $this->getAdapter();
       $query_string = $queryString . " " . $where . " " . $group . " " . $order . " " . $limit; //Combining all the parameters required to build the query statement. We will add more fields to this in the future if required.
       // echo $query_string;exit;
       $statement = $adapter->query($query_string); 
       $result = $statement->execute();
       $resultSet = new ResultSet();
       return $resultSet->initialize($result);
   }   

   /**
    * multiInsertOrUpdate: Insert or update Multiple rows as one query
    * @param array $tableName Table name to Insert fields into
    * @param array $data Insert array(array('field_name' => 'field_value'), array('field_name' => 'field_value_new'))
    * @param array $excludedColumns For excluding update columns array('field_name1', 'field_name2')
    * @return bool
    */

   public function multiInsertOrUpdate($tableName,array $data, array $excludedColumns){
    $sqlStringTemplate = 'INSERT INTO %s (%s) VALUES %s ON DUPLICATE KEY UPDATE %s';
    $adapter = $this->getAdapter();
    $driver = $adapter->getDriver();
    $platform = $adapter->getPlatform();
    $parameterContainer = new ParameterContainer();
    $statementContainer = $adapter->createStatement();
    $statementContainer->setParameterContainer($parameterContainer);
    /* add columns they should be updated */
    foreach ($data[0] as $column => $value) {
        if (false === array_search($column, $excludedColumns)) {
            $updateQuotedValue[] = ($platform->quoteIdentifier($column)) . '=' . ('VALUES(' . ($platform->quoteIdentifier($column)) . ')');
        }
    }
    /* Preparation insert data */
    $insertQuotedValue = [];
    $insertQuotedColumns = [];
    $i = 0;
    foreach ($data as $insertData) {
        $fieldName = 'field'.++$i.'_';
        $oneValueData = [];
        $insertQuotedColumns = [];
        foreach ($insertData as $column => $value) {
            $oneValueData[] = $driver->formatParameterName($fieldName . $column);
            $insertQuotedColumns[] = $platform->quoteIdentifier($column);
            $parameterContainer->offsetSet($fieldName . $column, $value);
        }
        $insertQuotedValue[] = '(' . implode(',', $oneValueData) . ')';
    }
    /* Preparation sql query */
    $query = sprintf($sqlStringTemplate,$tableName,implode(',', $insertQuotedColumns),implode(',', array_values($insertQuotedValue)),implode(',', array_values($updateQuotedValue)));
    $statementContainer->setSql($query);
    return $statementContainer->execute();
}

}
?>