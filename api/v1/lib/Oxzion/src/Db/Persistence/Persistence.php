<?php

namespace Oxzion\Db\Persistence;

use Oxzion\Service\AbstractService;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Table;
use Oxzion\Utils\FileUtils;
use PHPSQLParser\PHPSQLParser;
use PHPSQLParser\PHPSQLCreator;


class Persistence extends AbstractService {

    /**
     * Persistence constructor.
     * @param $config
     * @param $database
     */
    public function __construct($config, $database) {
        $this->database = $database;
        $this->config = $config;
        $config = $config['db'];
        $config['dsn'] = 'mysql:dbname=' . $this->database . ';host=' . $config['host'] . ';charset=utf8;username=' . $config["username"] . ';password=' . $config["password"] . '';
        $this->adapter = new Adapter($config);
        parent::__construct($config, $this->adapter);
    }

    /**
     * @param $sqlQuery
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function insertQuery($sqlQuery) {
        $adapter = $this->adapter;
        $parsedData = new PHPSQLParser($sqlQuery['query']);
        $parsedArray = $parsedData->parsed;
        if(!empty($parsedArray['INSERT'])) {
            foreach ($parsedArray['INSERT'] as $key => $insertArray) {
                if($insertArray['expr_type'] === 'table') {
                    $tableName = $insertArray['table'];
                    $columnResult = $adapter->query("SELECT TABLE_NAME, GROUP_CONCAT(COLUMN_NAME) as column_list FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name LIKE '$tableName'");
                    $resultSet1 = $columnResult->execute();
                    while ($resultSet1->next()) {
                        $resultTableName = $resultSet1->current();
                        $columnList = explode(",", $resultTableName['column_list']);
                        if (!in_array('ox_app_org_id', $columnList)) {
                            $tableResult = $adapter->query("ALTER TABLE " . $tableName . " ADD `ox_app_org_id` INT(11) NOT NULL");
                            $tableResult->execute();
                        }
                    }
                }
                if($insertArray['expr_type'] === 'column-list') {
//                            print_r($insertArray);exit;
                    if (strpos($insertArray['base_expr'], 'ox_app_org_id') !== true) {
                        $fieldsStringAfterTrim = substr(substr($insertArray['base_expr'], 0, -1), 1);
                        $fieldValue = explode(",", $fieldsStringAfterTrim);
                        array_push($fieldValue, "`ox_app_org_id`");
                        $fieldValueWithOrg = implode(",", $fieldValue);
                        array_push($parsedArray['INSERT'][$key]['sub_tree'],
                            Array (
                                "expr_type" => "colref",
                                "base_expr" => "`ox_app_org_id`",
                                "no_quotes" => Array
                                (
                                    "delim" => "",
                                    "parts" => Array
                                    (
                                        "0" => "ox_app_org_id"
                                    )
                                )
                            )
                        );
                        $parsedArray['INSERT'][$key]['base_expr'] = $fieldValueWithOrg;
                    }
                }
            }
        }
        if (!empty($parsedArray['VALUES'])) {
            foreach ($parsedArray['VALUES'] as $key => $insertArray) {
                $fieldsStringAfterTrim = substr(substr($insertArray['base_expr'], 0, -1), 1);
                $fieldValue = explode(",", $fieldsStringAfterTrim);
                array_push($fieldValue, 1);
                $fieldValueWithOrg = implode(",", $fieldValue);
                array_push($parsedArray['VALUES'][$key]['data'],
                    Array (
                        "expr_type" => "const",
                        "base_expr" => 1,
                        "sub_tree" => ""
                    )
                );
                $parsedArray['VALUES'][$key]['base_expr'] = "(" . $fieldValueWithOrg . ")";
            }
        }
        // build query again from an array of object(PhpMyAdmin\SqlParser\Statements\SelectStatement) to a string
        $statement = new PHPSQLCreator($parsedArray);
        $statement3 = $adapter->query($statement->created);
        return $statement3->execute();
    }

    /**
     * @param $sqlQuery
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function updateQuery($sqlQuery) {
        $adapter = $this->adapter;
        $parsedData = new PHPSQLParser($sqlQuery['query']);
        $parsedArray = $parsedData->parsed;
        if(!empty($parsedArray['UPDATE'])) {
            foreach ($parsedArray['UPDATE'] as $key => $updateArray) {
                if ($updateArray['expr_type'] === 'table') {
                    $tableName = $updateArray['table'];
                    $columnResult = $adapter->query("SELECT TABLE_NAME, GROUP_CONCAT(COLUMN_NAME) as column_list FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name LIKE '$tableName'");
                    $resultSet1 = $columnResult->execute();
                    while($resultSet1->next()) {
                        $resultTableName = $resultSet1->current();
                        $columnList = explode(",", $resultTableName['column_list']);
                        if(in_array('ox_app_org_id', $columnList)) {
                            $exp_and_operator = Array ("expr_type" => "operator", "base_expr" => "and", "sub_tree" => "");
                            $exp_const = Array ("expr_type" => "const", "base_expr" => "1", "sub_tree" => "");
                            $exp_operator = Array ("expr_type" => "operator", "base_expr" => "=", "sub_tree" => "");
                            $exp_colref = Array ("expr_type" => "colref", "base_expr" => "ox_app_org_id", "sub_tree" => "", "no_quotes" =>
                                Array( "delim" => "", "parts" => Array ("0" => "ox_app_org_id") )
                            );
                            array_push($parsedArray['WHERE'], $exp_and_operator, $exp_colref, $exp_operator, $exp_const);
                        }
                    }
                }
            }
        }
        $statement = new PHPSQLCreator($parsedArray);
        $statement3 = $adapter->query($statement->created);
        return $statement3->execute();
    }

    /**
     * @param $sqlQuery
     * @return array
     */
    public function selectQuery($sqlQuery) {
        $adapter = $this->adapter;
        $parsedData = new PHPSQLParser($sqlQuery['query']);
        $parsedArray = $parsedData->parsed;

        if(!empty($parsedArray['FROM'])) {
            foreach ($parsedArray['FROM'] as $key => $updateArray) {
                if ($updateArray['expr_type'] === 'table') {
                    $tableName = $updateArray['table'];
                    $columnResult = $adapter->query("SELECT TABLE_NAME, GROUP_CONCAT(COLUMN_NAME) as column_list FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name LIKE '$tableName'");
                    $resultSet1 = $columnResult->execute();
                    while($resultSet1->next()) {
                        $resultTableName = $resultSet1->current();
                        $columnList = explode(",", $resultTableName['column_list']);
                        if(in_array('ox_app_org_id', $columnList)) {
                            $exp_and_operator = Array ("expr_type" => "operator", "base_expr" => "and", "sub_tree" => "");
                            $exp_const = Array ("expr_type" => "const", "base_expr" => "1", "sub_tree" => "");
                            $exp_operator = Array ("expr_type" => "operator", "base_expr" => "=", "sub_tree" => "");
                            $exp_colref = Array ("expr_type" => "colref", "base_expr" => "ox_app_org_id", "sub_tree" => "", "no_quotes" =>
                                Array( "delim" => "", "parts" => Array ("0" => "ox_app_org_id") )
                            );
                            array_push($parsedArray['WHERE'], $exp_and_operator, $exp_colref, $exp_operator, $exp_const);
                        }
                    }
                }
            }
        }
        $statement = new PHPSQLCreator($parsedArray);
        $statement3 = $adapter->query($statement->created);
        return $statement3->execute();
    }

    /**
     * @param $sqlQuery
     * @return \Zend\Db\Adapter\Driver\ResultInterface
     */
    public function deleteQuery($sqlQuery) {
        $adapter = $this->adapter;
        $parsedData = new PHPSQLParser($sqlQuery['query']);
        $parsedArray = $parsedData->parsed;
        if(!empty($parsedArray['FROM'])) {
            foreach ($parsedArray['FROM'] as $key => $updateArray) {
                if ($updateArray['expr_type'] === 'table') {
                    $tableName = $updateArray['table'];
                    $columnResult = $adapter->query("SELECT TABLE_NAME, GROUP_CONCAT(COLUMN_NAME) as column_list FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name LIKE '$tableName'");
                    $resultSet1 = $columnResult->execute();
                    while($resultSet1->next()) {
                        $resultTableName = $resultSet1->current();
                        $columnList = explode(",", $resultTableName['column_list']);
                        if(in_array('ox_app_org_id', $columnList)) {
                            $exp_and_operator = Array ("expr_type" => "operator", "base_expr" => "and", "sub_tree" => "");
                            $exp_const = Array ("expr_type" => "const", "base_expr" => "1", "sub_tree" => "");
                            $exp_operator = Array ("expr_type" => "operator", "base_expr" => "=", "sub_tree" => "");
                            $exp_colref = Array ("expr_type" => "colref", "base_expr" => "ox_app_org_id", "sub_tree" => "", "no_quotes" =>
                                Array( "delim" => "", "parts" => Array ("0" => "ox_app_org_id") )
                            );
                            array_push($parsedArray['WHERE'], $exp_and_operator, $exp_colref, $exp_operator, $exp_const);
                        }
                    }
                }
            }
        }

        $statement = new PHPSQLCreator($parsedArray);
        $statement3 = $adapter->query($statement->created);
        return $statement3->execute();
    }

}
