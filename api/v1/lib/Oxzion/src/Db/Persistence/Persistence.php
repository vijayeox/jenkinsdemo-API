<?php

namespace Oxzion\Db\Persistence;

use Oxzion\Service\AbstractService;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Table;
use Oxzion\Utils\FileUtils;
use PHPSQLParser\PHPSQLParser;
use PHPSQLParser\PHPSQLCreator;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Zend\Db\Adapter\ParameterContainer;

class Persistence extends AbstractService
{
    private $database;

    /**
     * Persistence constructor.
     * @param $config
     * @param $appname
     * @param @appId
     */
    public function __construct($config, string $appName, string $appId)
    {
        $this->database = $appName.'___'.$appId;
        $this->database = str_replace('-', '', $this->database);
        $dbConfig = array_merge(array(), $config['db']);
        $dbConfig['dsn'] = 'mysql:dbname=' . $this->database . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' . $dbConfig["username"] . ';password=' . $dbConfig["password"] . '';
        $dbConfig['database'] = $this->database;
        $adapter = new Adapter($dbConfig);
        parent::__construct($config, $adapter);
    }

    //this method is used only for phpunit tests. Not required to be called otherwise
    public function setAdapter($adapter){
        $this->dbAdapter = $adapter;
    }

    /**
     * @param $sqlQuery
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function insertQuery($sqlQuery)
    {
        $parsedData = new PHPSQLParser($sqlQuery);
        $parsedArray = $parsedData->parsed;
        $adapter=$this->dbAdapter;
        try {
            if (!empty($parsedArray['INSERT'])) {
                foreach ($parsedArray['INSERT'] as $key => $insertArray) {
                    if ($insertArray['expr_type'] === 'column-list') {
                        if (strpos($insertArray['base_expr'], 'ox_app_org_id') !== true) {
                            $fieldsStringAfterTrim = substr(substr($insertArray['base_expr'], 0, -1), 1);
                            $fieldValue = explode(",", $fieldsStringAfterTrim);
                            array_push($fieldValue, "`ox_app_org_id`");
                            $fieldValueWithOrg = implode(",", $fieldValue);
                            array_push(
                                $parsedArray['INSERT'][$key]['sub_tree'],
                                array(
                                    "expr_type" => "colref",
                                    "base_expr" => "`ox_app_org_id`",
                                    "no_quotes" => array (
                                        "delim" => "",
                                        "parts" => array (
                                            "0" => "ox_app_org_id"
                                        )
                                    )
                                )
                            );
                            $parsedArray['INSERT'][$key]['base_expr'] = "(" . $fieldValueWithOrg . ")";
                        }
                    }
                }
            }
            if (!empty($parsedArray['SELECT'])) {
                $SelectArrayKeys = array_keys($parsedArray['SELECT']);
                $lastElementInSelectList = end($SelectArrayKeys);
                $parsedArray['SELECT'][$lastElementInSelectList]['delim'] = ","; 
                $orgId = AuthContext::get(AuthConstants::ORG_ID) ? AuthContext::get(AuthConstants::ORG_ID) : 0;              
                $selectExpressionOperator = array(
                    "expr_type" => "const",
                    "base_expr" => $orgId,
                    "sub_tree" => "",
                    "delim" => "");
                array_push($parsedArray['SELECT'], $selectExpressionOperator);
                $parsedArray = $this->processParsedArrayForOrgID($parsedArray,'FROM');
            }
            if (!empty($parsedArray['WHERE'])) {
                $tableArrayList = $this->getTableList($parsedArray['FROM']);
                $parsedArray = $this->processParsedArrayForOrgID($parsedArray,'WHERE',$tableArrayList);
            }
            if (!empty($parsedArray['VALUES'])) {
                foreach ($parsedArray['VALUES'] as $key => $insertArray) {
                    $fieldsStringAfterTrim = substr(substr($insertArray['base_expr'], 0, -1), 1);
                    $fieldValue = explode(",", $fieldsStringAfterTrim);
                    array_push($fieldValue, 1);
                    $fieldValueWithOrg = implode(",", $fieldValue);
                    array_push(
                        $parsedArray['VALUES'][$key]['data'],
                        array(
                            "expr_type" => "const",
                            "base_expr" => AuthContext::get(AuthConstants::ORG_ID),
                            "sub_tree" => ""
                        )
                    );
                    $parsedArray['VALUES'][$key]['base_expr'] = "(" . $fieldValueWithOrg . ")";
                }
            }
        } catch (Exception $e) {
            return 0;
        }
        return $queryExecute = $this->generateSQLFromArray($parsedArray);
    }

    /**
     * @param $sqlQuery
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function updateQuery($sqlQuery)
    {
        $parsedData = new PHPSQLParser($sqlQuery);
        $parsedArray = $parsedData->parsed;
        $adapter=$this->dbAdapter;

        try {
           $parsedArray = $this->processParsedArrayForOrgID($parsedArray,'UPDATE');
        } catch (Exception $e) {
            return 0;
        }

        return $queryExecute = $this->generateSQLFromArray($parsedArray);
    }


    /**
     * @param $sqlQuery
     * @param $returnArray
     * @return array
     */
    public function selectQuery($sqlQuery, $returnArray = false)
    {
        $parsedData = new PHPSQLParser($sqlQuery);
        $parsedArray = $parsedData->parsed;
        $adapter=$this->dbAdapter;
        try {
            $parsedArray = $this->processParsedArrayForOrgID($parsedArray,'FROM');
        } catch (Exception $e) {
            return 0;
        }
        $queryExecute = $this->generateSQLFromArray($parsedArray);
        if ($returnArray === true) {
            return $this->toArray($queryExecute);
        } else {
            return $queryExecute;
        }
    }

    /**
     * @param $sqlQuery
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function deleteQuery($sqlQuery)
    {
        $parsedData = new PHPSQLParser($sqlQuery);
        $parsedArray = $parsedData->parsed;
        $adapter=$this->dbAdapter;
        try {
            if (!empty($parsedArray['FROM'])) {
                foreach ($parsedArray['FROM'] as $key => $updateArray) {
                    if ($updateArray['expr_type'] === 'table') {
                        $tableName = $updateArray['table'];
                        $parsedArray = $this->additionOfOrgIdColumn($parsedArray,$tableName);
                    }
                }
            }
        } catch (Exception $e) {
            return 0;
        }
        return $queryExecute = $this->generateSQLFromArray($parsedArray);
    }

    private function getReferenceClause($parsedArray, $key, $data, $tableArrayList, $queryStatement)
    {
        $adapter=$this->dbAdapter;
        if (!empty($data['ref_clause'])) {
            $expAndOperator = array("expr_type" => "operator", "base_expr" => "and", "sub_tree" => "");
            array_push($parsedArray[$queryStatement][$key]['ref_clause'], $expAndOperator);
            if (count($tableArrayList) > 2)
                $tableArrayList = array(current($tableArrayList), end($tableArrayList));
            $arrayKeys = array_keys($tableArrayList);
            $lastElementInTableList = end($arrayKeys);
            foreach ($tableArrayList as $fromkey => $tableList) {
                $exp_colref = array(
                    "expr_type" => "colref",
                    "base_expr" => $tableList . ".ox_app_org_id",
                    "no_quotes" => array(
                        "delim" => ".",
                        "parts" => array("0" => $tableList, "1" => "ox_app_org_id")
                    ),
                    "sub_tree" => "",
                );
                if ($lastElementInTableList == $fromkey) {
                    array_push($parsedArray[$queryStatement][$key]['ref_clause'], $exp_colref);
                } else {
                    $exp_operator = array("expr_type" => "operator", "base_expr" => "=", "sub_tree" => "");
                    array_push($parsedArray[$queryStatement][$key]['ref_clause'], $exp_colref, $exp_operator);
                }
            }
        }
        return $parsedArray;
    }

    private function generateSQLFromArray($parsedArray)
    {
        $adapter = $this->dbAdapter;
        $statement = new PHPSQLCreator($parsedArray);
        $statement3 = $adapter->query($statement->created);
        return $statement3->execute();
    }

    private function additionOfOrgIdColumn($parsedArray,$tableName,$tableAliasName = null)
    {
        $adapter = $this->dbAdapter;
        if(!$tableAliasName){
            $tableAliasName = $tableName;
        }
        $columnResult = $adapter->query("SELECT TABLE_NAME, GROUP_CONCAT(COLUMN_NAME) as column_list FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name LIKE '$tableName' GROUP BY TABLE_NAME");
        $resultSet1 = $columnResult->execute();
        while ($resultSet1->next()) {
            $resultTableName = $resultSet1->current();
            $columnList = explode(",", $resultTableName['column_list']);
            if (in_array('ox_app_org_id', $columnList)) {
                $orgId = AuthContext::get(AuthConstants::ORG_ID);
                if($orgId){
                    $exp_const = array("expr_type" => "const", "base_expr" => $orgId, "sub_tree" => "");
                    $exp_operator = array("expr_type" => "operator", "base_expr" => "=", "sub_tree" => "");
                    $exp_colref = array("expr_type" => "colref", "base_expr" => $tableAliasName . " . ox_app_org_id", "sub_tree" => "", "no_quotes" =>
                        array( "delim" => "", "parts" => array("0" => "ox_app_org_id") )
                    );
                }
                if(!isset($parsedArray['WHERE'])){
                    $parsedArray['WHERE'] = array();
                    $expAndOperator = array("expr_type" => "operator", "base_expr" => " (", "sub_tree" => "");
                }else{
                    $expAndOperator = array("expr_type" => "operator", "base_expr" => "and (", "sub_tree" => "");
                }

                if($orgId){
                    array_push($parsedArray['WHERE'], $expAndOperator, $exp_colref, $exp_operator, $exp_const);
                    $expOrOperator = array("expr_type" => "operator", "base_expr" => "OR", "sub_tree" => "");
                    array_push($parsedArray['WHERE'], $expOrOperator);
                }else{
                    array_push($parsedArray['WHERE'], $expAndOperator);
                }
                $exp_const = array("expr_type" => "const", "base_expr" => " 0 )", "sub_tree" => "");
                $exp_operator = array("expr_type" => "operator", "base_expr" => "=", "sub_tree" => "");
                $exp_colref = array("expr_type" => "colref", "base_expr" => $tableAliasName . " . ox_app_org_id", "sub_tree" => "", "no_quotes" =>
                    array( "delim" => "", "parts" => array("0" => "ox_app_org_id") )
                );
                array_push($parsedArray['WHERE'], $exp_colref, $exp_operator, $exp_const);
            }
        }
        return $parsedArray;
    }

    public function runQueryForStoredProcedure($query, $storedProcedureName)
    {
        $checkString = $this->checkForStoredProcedure($storedProcedureName);
        if($checkString == 1){
            return $this->executeQuery($query);
        }
        return 0;
    }

    private function checkForStoredProcedure($storedProcedureName)
    {
        $query = "SHOW PROCEDURE STATUS where Db = '" . $this->database . "' and name = '". $storedProcedureName . "'";
        $statement = $this->dbAdapter->query($query);
        $result = $statement->execute();
        $rowCount = $result->count();
        if ($rowCount == 1) {
            return 1;
        }
        return 0;
    }

    protected function executeQuery($query) {
        $adapter = $this->getAdapter();
        $driver = $adapter->getDriver();
        $platform = $adapter->getPlatform();
        $parameterContainer = new ParameterContainer();
        $statementContainer = $adapter->createStatement();
        $statementContainer->setParameterContainer($parameterContainer);
        $statementContainer->setSql($query);
        return $statementContainer->execute();
    }

    private function processParsedArrayForOrgID($parsedArray,$operator,$tableArrayList = array()){
        if (!empty($parsedArray)) {
            if(isset($parsedArray[$operator])) {
                $data = $parsedArray[$operator];
            }else{
                $data =array();
            }  
            if(empty($tableArrayList)){
                $processed = 0;

                foreach ($data as $key => $updateArray) {
                    $tableAliasName = null;
                    if ($updateArray['expr_type'] === 'table') {
                        if ($updateArray['alias']) {    
                            $tableAliasName = $updateArray['alias']['name'];
                        }
                        $tableName = $updateArray['table'];
                        $tableArrayList[] = $tableAliasName  ? $tableAliasName : $tableName;
                        $parsedArray = $this->additionOfOrgIdColumn($parsedArray,$tableName,$tableAliasName);
                    }else if ($updateArray['expr_type'] === 'subquery') {
                        $parsedArray[$operator][$key]['sub_tree'] = $this->processParsedArrayForOrgID($updateArray['sub_tree'],'FROM');
                        continue;

                    }else if(count($tableArrayList) > 0 && $processed == 1){
                        $processed = 1;
                        $parsedArray = $this->additionOfOrgIdColumn($parsedArray,$tableArrayList[0]);
                    }

                    $parsedArray = $this->getReferenceClause($parsedArray, $key, $updateArray, $tableArrayList, $operator);
                }  
            }
        }
        return $parsedArray;
    }

    private function getTableList($parsedArray){
        $tableArrayList = array();
        foreach ($parsedArray as $key => $updateArray) {
            if ($updateArray['expr_type'] === 'table') {
                if ($updateArray['alias']) {
                    $tableName = $updateArray['alias']['name'];
                } else {
                    $tableName = $updateArray['table'];
                }
                $tableArrayList[] = $tableName;
            }
        }
        return $tableArrayList;
    }
}