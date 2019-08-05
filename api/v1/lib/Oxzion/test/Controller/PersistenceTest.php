<?php
namespace Oxzion\Db\Persistence;

use Oxzion\Test\ServiceTest;
use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\ArrayUtils;
use Oxzion\Transaction\TransactionManager;
use Oxzion\Db\Migration\Migration;
use Oxzion\Service\AbstractService;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;

class PersistenceTest extends ServiceTest
{
    private static $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    protected $traceError = true;
    protected $data;
    private $database;
    private $adapter;

    public function setUp() : void
    {
        $this->loadConfig();
        $this->data =  array(
            "appName" => 'ox_app_2',
            'UUID' => 123889,
            'description' => 'FirstApp'
        );

        $config = $this->getApplicationConfig();
        $dbConfig = $config['db'];
        $this->database = $this->data['appName'] . "___" . $this->data['UUID'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $this->database . ';host=' . $dbConfig['host'] . ';charset=utf8;username='.$dbConfig["username"].';password='.$dbConfig["password"].'';
        $dbConfig['database'] = $this->database;
        $this->adapter = new Adapter($dbConfig);

        $migrationObject = new Migration($config, $this->database, $this->adapter);
        $migrationObject->initDB($this->data);
        $dataSet = array_diff(scandir(dirname(__FILE__) ."/../Migration/"), array(".", ".."));
        $migrationFolder = dirname(__FILE__) ."/../Migration/";
        $testCase = $migrationObject->migrationSql($dataSet, $migrationFolder, $this->data);

        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();

        if ($this->getName() == 'testInsertQueryWithJoinSelect' || $this->getName() == 'testSelectQuery' || $this->getName() == 'testSelectQueryWithJoin' || $this->getName() == 'testUpdateQuery' || $this->getName() == 'testUpdateQueryWithJoin') {
            $insertQuery = "INSERT INTO ox_timesheet (`name`, `client_id`, `description`, `process_id`, `date_created`,`ox_app_org_id`) VALUES ('Task1', 1, 'New Task', 2, '2019-01-02 12:00:00',1)";
            $statement = $this->adapter->query($insertQuery);
            $result = $statement->execute();
            $update="UPDATE ox_timesheet_client SET ox_app_org_id = 1 where id = 1";
            $statement = $this->adapter->query($update);
            $result = $statement->execute();
        }
    }

    public function tearDown() : void
    {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $query = "DROP DATABASE ".$this->database;
        $statement = $this->adapter->query($query);
        $result = $statement->execute();
        $_REQUEST = [];
    }

    /**
     * Code to check the insert for a normal query
     */
    public function testInsertQuery()
    {
        $config = $this->getApplicationConfig();
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);

        $insertData = array(
            'name' => "Task2",
            'client_id' => 1,
            'description' => "New Task 2",
            'process_id' => 2
        );
        $insertQuery = "INSERT INTO ox_timesheet (`name`, `client_id`, `description`, `process_id`, `date_created`) 
VALUES ('".$insertData['name']."', ".$insertData['client_id'].", '".$insertData['description']."', ".$insertData['process_id'].", '2019-01-02 12:00:00')";
        $this->data['query'] =  $insertQuery;
        $persistenceObject->insertQuery($this->data);
        $sqlQuery1 = "Select * from ox_timesheet where name = '" . $insertData['name'] . "' and client_id = " . $insertData['client_id'] . " and description = '" . $insertData['description'] . "' and process_id = " . $insertData['process_id'];
        $statement1 = $this->adapter->query($sqlQuery1);
        $result1 = $statement1->execute();
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['id'] > 0, true);
        $this->assertEquals($tableFieldName[0]['name'], $insertData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $insertData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $insertData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $insertData['process_id']);
    }

    /**
     *
     */
    public function testInsertQueryWithJoinSelect()
    {
        $config = $this->getApplicationConfig();
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $insertData = array(
            'id' => 1,
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $insertQuery = "INSERT INTO ox_timesheet (`name`, `client_id`, `description`, `process_id`, `date_created`)
          SELECT `ox_timesheet`.`name`, `ox_timesheet`.`client_id`, `ox_timesheet`.`description`, `ox_timesheet`.`process_id`, `ox_timesheet`.`date_created` FROM ox_timesheet LEFT JOIN 
          ox_timesheet_client ON ox_timesheet_client.id = ox_timesheet.client_id";
        $this->data['query'] =  $insertQuery;
        $persistenceObject->insertQuery($this->data);
        $sqlQuery1 = "Select * from ox_timesheet where name = '" . $insertData['name'] . "' and client_id = " . $insertData['client_id'] . " and description = '" . $insertData['description'] . "' and process_id = " . $insertData['process_id'];
        $statement1 = $this->adapter->query($sqlQuery1);
        $result1 = $statement1->execute();
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['id'] > 1, true);
        $this->assertEquals($tableFieldName[0]['name'], $insertData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $insertData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $insertData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $insertData['process_id']);
    }

    public function testSelectQuery()
    {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "Select * from ox_timesheet where id > 0";
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $result1 = $persistenceObject->selectQuery($this->data);
        $selectData = array(
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['id'] > 0, true);
        $this->assertEquals($tableFieldName[0]['name'], $selectData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $selectData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $selectData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $selectData['process_id']);
    }

    public function testSelectQueryWithJoin()
    {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "SELECT `ox_timesheet`.`name`, `ox_timesheet`.`client_id`, `ox_timesheet`.`description`, `ox_timesheet`.`process_id`, `ox_timesheet`.`date_created` FROM ox_timesheet 
LEFT JOIN ox_timesheet_client ON ox_timesheet_client.id = ox_timesheet.client_id where ox_timesheet.client_id = 1";
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $persistenceObject->selectQuery($this->data);
        $selectData = array(
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $this->adapter->query($this->data['query']);
        $result1 = $statement1->execute();
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['name'], $selectData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $selectData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $selectData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $selectData['process_id']);
    }

    public function testUpdateQuery()
    {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "UPDATE ox_timesheet set name = 'Updated Task' WHERE id > 0";
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $persistenceObject->updateQuery($this->data);
        $selectData = array(
            'name' => "Updated Task",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $this->adapter->query("Select * from ox_timesheet");
        $result1 = $statement1->execute();
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['id'] > 0, true);
        $this->assertEquals($tableFieldName[0]['name'], $selectData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $selectData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $selectData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $selectData['process_id']);
    }


    public function testUpdateQueryWithJoin()
    {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "UPDATE ox_timesheet AS b
            INNER JOIN ox_timesheet_client AS g ON b.client_id = g.id
            SET b.name = 'Updated Task', b.Description = 'New Task'
            WHERE  (b.client_id = 1) and g.id = 1";
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $persistenceObject->updateQuery($this->data);
        $selectData = array(
            'name' => "Updated Task",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $this->adapter->query("Select * from ox_timesheet");
        $result1 = $statement1->execute();
        $resultSet = new ResultSet();
        $resultset=$resultSet->initialize($result1);
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['id'] > 0, true);
        $this->assertEquals($tableFieldName[0]['name'], $selectData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $selectData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $selectData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $selectData['process_id']);
    }


    public function testDeleteQuery()
    {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "Delete from ox_timesheet where id > 0";
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $persistenceObject->deleteQuery($this->data);
        $statement1 = $this->adapter->query("Select * from ox_timesheet");
        $result1 = $statement1->execute();
        $tableFieldName = array();
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName, array());
    }

    // //Code to delete all the enreies made to the timesheet table for testing purpose.
    public function testDeleteAllQuery()
    {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "Delete from ox_timesheet where id > 0";
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $persistenceObject->deleteQuery($this->data);
        $statement1 = $this->adapter->query("Select * from ox_timesheet");
        $result1 = $statement1->execute();
        $tableFieldName = array();
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName, array());
    }
}
