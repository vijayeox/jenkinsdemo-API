<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Utils\StringUtils;
use Zend\Db\Adapter\ParameterContainer;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;

abstract class AbstractService extends AbstractBaseService
{

    protected function __construct($config, $dbAdapter)
    {
        parent::__construct($config, $dbAdapter);
    }

    protected function getBaseUrl()
    {
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'];
    }

    protected function getIdFromUuid($table, $uuid, $filter = array())
    {
        $filter['uuid'] = $uuid;
        $sql = $this->getSqlObject();
        $getID = $sql->select();
        $getID->from($table)
            ->columns(array("id"))
            ->where($filter);
        $responseID = $this->executeQuery($getID)->toArray();
        if ($responseID) {
            return $responseID[0]['id'];
        } else {
            return 0;
        }
    }

    protected function getUuidFromId($table, $id)
    {
        $sql = $this->getSqlObject();
        $getID = $sql->select();
        $getID->from($table)
            ->columns(array("uuid"))
            ->where(array('id' => $id));
        $responseID = $this->executeQuery($getID)->toArray();
        if ($responseID) {
            return $responseID[0]['uuid'];
        } else {
            return 0;
        }
    }

    protected function ExpressionObject($expression)
    {
        return new Expression($expression);
    }

    protected function executeUpdate($query)
    {
        $statement = $this->sql->prepareStatementForSqlObject($query);
        return $statement->execute();
    }

    protected function executeQuery($query)
    {
        $statement = $this->sql->prepareStatementForSqlObject($query);
        $result = $statement->execute();
        // build result set
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;
    }

    protected function executeInsert($query)
    {
        if (StringUtils::startsWith($query, 'INSERT')) {
            $result = $this->executeQueryInternal($query);
            if ($result->getAffectedRows() > 0) {
                return $result->getGeneratedValue();
            }
        }
        return 0;
    }

    /**
     * Query builder: Code that combines the required parameter to build the query.
     * Author: Rakshith
     * Function Name: executeQuerywithParams()
     */
    public function executeQuerywithParams($queryString, $where = null, $group = null, $order = null, $limit = null)
    {
        $result = $this->executeQueryInternal($queryString, $where, $group, $order, $limit);
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    private function executeQueryInternal($queryString, $where = null, $group = null, $order = null, $limit = null)
    {
        //Passing the required parameter to the query statement
        $adapter = $this->getAdapter();
        $query_string = $queryString . " " . $where . " " . $group . " " . $order . " " . $limit; //Combining all the parameters required to build the query statement. We will add more fields to this in the future if required.
        //        echo $query_string;exit;
        $statement = $adapter->query($query_string);
        $result = $statement->execute();
        return $result;
    }

    protected function executeUpdateWithBindParameters($queryString, $parameters = null)
    {
        return $this->executeQueryWithBindParametersInternal($queryString, $parameters);
    }

    protected function executeQueryWithBindParameters($queryString, $parameters = null)
    {
        $result = $this->executeQueryWithBindParametersInternal($queryString, $parameters);
        $resultSet = new ResultSet();
        return $resultSet->initialize($result);
    }

    private function executeQueryWithBindParametersInternal($queryString, $parameters)
    {
        $adapter = $this->getAdapter();
        $statement = $adapter->query($queryString);
        $result = $statement->execute($parameters ? $parameters : array());
        return $result;
    }

    public function create(&$data, $commit = true)
    {
        $this->modelClass->exchangeArray($data);
        $this->modelClass->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($this->modelClass);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $data['id'] = $this->table->getLastInsertValue();
            if ($commit) {
                $this->commit();
            }
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    /**
     * Gets the data by parameters.
     *
     * @param      string|array     $tableName   The table name
     * @param      array            $fieldArray  The field array
     * @param      array            $where       The where
     * @param      array            $joins       The joins
     * @param      string           $sortby      The sortby
     * @param      array            $groupby     The groupby
     * @param      integer          $limit       The limit
     * @param      integer          $offset      The offset
     * @param      boolean          $debug       flag to print select if needed from any nested function call
     *
     * @return     array   The data by parameters.
     */
    protected function getDataByParams($tableName, $fieldArray = array(), $where = array(), $joins = array(), $sortby = null, $groupby = array(), $limit = null, $offset = 0, $debug = false)
    {
        $select = $this->sql->select($tableName);
        if ($fieldArray) {
            $select->columns($fieldArray);
        }
        if ($where) {
            if (is_array($where) && array_intersect(array('OR', 'AND', 'or', 'and'), array_keys($where))) {
                foreach ($where as $op => $cond) {
                    $select->where($cond, strtoupper($op));
                }
            } else {
                $select->where($where, 'AND');
            }
        }

        /**
         * Joins.
         *
         * @param      string|array     $table          The table name      array(aliasname => tableName)
         * @param      string           $condition      The field array
         * @param      array            $fields         The where
         * @param      string           $joinMethod     The joins           join, left, right
         */
        if (isset($joins)) {
            foreach ($joins as $key => $join) {
                $select->join(
                    $join['table'],
                    $join['condition'],
                    (isset($join['fields'])) ? $join['fields'] : array(),
                    (isset($join['joinMethod'])) ? $join['joinMethod'] : 'join'
                );
            }
        }

        if ($sortby) {
            $select->order($sortby);
        }
        if ($groupby) {
            $select->group($group);
        }
        if ($limit) {
            $select->limit($limit);
        }
        if ($offset) {
            $select->offset($offset);
        }
        if ($debug) {
            echo "<pre>";
            print_r($this->sql->buildSqlString($select));
            exit();
        }
        $returnArray = $this->executeQuery($select);
        // if (!$returnArray) return array();
        return $returnArray;
    }

    /**
     * multiInsertOrUpdate: Insert or update Multiple rows as one query
     * @param array $tableName Table name to Insert fields into
     * @param array $data Insert array(array('field_name' => 'field_value'), array('field_name' => 'field_value_new'))
     * @param array $excludedColumns For excluding update columns array('field_name1', 'field_name2')
     * @return bool
     */

    public function multiInsertOrUpdate($tableName, array $data, array $excludedColumns = array())
    {
        $this->logger->info("Entering into multiInsertOrUpdate method");
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
        $this->logger->info("Update quted val -" . print_r($updateQuotedValue, true));
        /* Preparation insert data */
        $insertQuotedValue = [];
        $insertQuotedColumns = [];
        $i = 0;
        foreach ($data as $insertData) {
            $fieldName = 'field' . ++$i . '_';
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
        $query = sprintf($sqlStringTemplate, $tableName, implode(',', $insertQuotedColumns), implode(',', array_values($insertQuotedValue)), implode(',', array_values($updateQuotedValue)));
        $this->logger->info("Prepared SQL query - $query");
        $statementContainer->setSql($query);
        return $statementContainer->execute();
    }

    public function runGenericQuery($query)
    {
        $adapter = $this->getAdapter();
        $driver = $adapter->getDriver();
        $platform = $adapter->getPlatform();
        $parameterContainer = new ParameterContainer();
        $statementContainer = $adapter->createStatement();
        $statementContainer->setParameterContainer($parameterContainer);
        $statementContainer->setSql($query);
        return $statementContainer->execute();
    }

    public function updateOrganizationContext($data)
    {
        try {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
            if (isset($data['orgId'])) {
                AuthContext::put(AuthConstants::ORG_UUID, $data['orgId']);
                $orgId = $this->getIdFromUuid('ox_organization', $data['orgId']);
                AuthContext::put(AuthConstants::ORG_ID, $orgId);
                $userId = AuthContext::get(AuthConstants::USER_ID);
                $userUuid = AuthContext::get(AuthConstants::USER_UUID);
                if(isset($data['userId'])){
                    $userUuid = $data['userId'];
                    $userId = $this->getIdFromUuid('ox_user', $userUuid);
                }else if(!$userId){
                    $select = "SELECT ou.id,ou.uuid from ox_user as ou join ox_organization as org on org.contactid = ou.id where org.id = :orgId";
                    $params = array("orgId" => $orgId);
                    $result = $this->executeQueryWithBindParameters($select, $params)->toArray();
                    if (isset($result[0])) {
                        $userId = $result[0]['id'];
                        $userUuid = $result[0]['uuid'];
                    }
                }
                if (isset($userId)) {
                    AuthContext::put(AuthConstants::USER_ID, $userId);
                    AuthContext::put(AuthConstants::USER_UUID, $userUuid);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function toArray($result)
    {
        $returnArray = array();
        if ($result->count() > 0) {
            while ($result->next()) {
                $returnArray[] = $result->current();
            }
        }
        return $returnArray;
    }

    public function setSessionProperties($properties){
        foreach($properties as $key => $value){
            if(is_string($value['value'])){
                $value['value'] = "'".$value['value']."'";    
            }
            $query = "SET SESSION ".$value['name']." = ".$value['value'];
            $this->executeQuerywithParams($query);
        }
    }
}
