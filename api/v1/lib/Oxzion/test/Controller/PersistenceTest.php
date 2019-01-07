<?php
namespace Oxzion\Db\Persistence;

use Oxzion\Test\ServiceTest;
use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\ArrayUtils;

class PersistenceTest extends ServiceTest {
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    protected $traceError = true;
    protected $data;

    public function setUp() : void {
        $this->loadConfig();
        parent::setUp();
    }

    protected function loadConfig() {
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/autoload/global.php', include __DIR__ . '/../../../../config/autoload/local.php');
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php',$configOverrides);
        $this->setApplicationConfig($configOverrides);
        $this->data =  Array (
            "appName" => 'ox_app_2',
            'UUID' => 123889,
            'description' => 'FirstApp',
        );
    }

    /**
     * Code to check the insert for a normal query
     */
    public function testInsertQuery () {
        $config = $this->getApplicationConfig();
        $database = $this->data['appName'] . "___" . $this->data['UUID'];
        $persistenceObject = new Persistence($config, $database);
        $insertData = Array (
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $insertQuery = "INSERT INTO ox_timesheet (`id`, `name`, `client_id`, `description`, `process_id`, `date_created`) 
VALUES (1, 'Task1', 1, 'New Task', 2, '2019-01-02 12:00:00')";
        $this->data['query'] =  $insertQuery;
        $persistenceObject->insertQuery($this->data);
        $dbConfig = $config['db'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $database . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' .$dbConfig["appuser"] . ';password='.$dbConfig["password"];
        $adapter = new Adapter($dbConfig);
        $sqlQuery1 = "Select * from ox_timesheet where id = 1 and name = '" . $insertData['name'] . "' and client_id = " . $insertData['client_id'] . " and description = '" . $insertData['description'] . "' and process_id = " . $insertData['process_id'];
        $statement1 = $adapter->query($sqlQuery1);
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['id'], 1);
        $this->assertEquals($tableFieldName[0]['name'], $insertData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $insertData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $insertData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $insertData['process_id']);
    }

    /**
     *
     */
    public function testInsertQueryWithJoinSelect () {
        $config = $this->getApplicationConfig();
        $database = $this->data['appName'] . "___" . $this->data['UUID'];
        $persistenceObject = new Persistence($config, $database);
        $insertData = Array (
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
        $dbConfig = $config['db'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $database . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' .$dbConfig["appuser"] . ';password='.$dbConfig["password"];
        $adapter = new Adapter($dbConfig);
        $sqlQuery1 = "Select * from ox_timesheet where id = " . $insertData['id'] . " and name = '" . $insertData['name'] . "' and client_id = " . $insertData['client_id'] . " and description = '" . $insertData['description'] . "' and process_id = " . $insertData['process_id'];
        $statement1 = $adapter->query($sqlQuery1);
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['id'], 1);
        $this->assertEquals($tableFieldName[0]['name'], $insertData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $insertData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $insertData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $insertData['process_id']);
    }

    public function testSelectQuery() {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "Select * from ox_timesheet where id = 1";
        $database = $this->data['appName'] . "___" . $this->data['UUID'];
        $persistenceObject = new Persistence($config, $database);
        $persistenceObject->selectQuery($this->data);
        $dbConfig = $config['db'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $database . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' .$dbConfig["appuser"] . ';password='.$dbConfig["password"];
        $adapter = new Adapter($dbConfig);
        $selectData = Array (
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $adapter->query($this->data['query']);
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['id'], 1);
        $this->assertEquals($tableFieldName[0]['name'], $selectData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $selectData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $selectData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $selectData['process_id']);
    }

    public function testSelectQueryWithJoin() {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "SELECT `ox_timesheet`.`name`, `ox_timesheet`.`client_id`, `ox_timesheet`.`description`, `ox_timesheet`.`process_id`, `ox_timesheet`.`date_created` FROM ox_timesheet 
LEFT JOIN ox_timesheet_client ON ox_timesheet_client.id = ox_timesheet.client_id where ox_timesheet.client_id = 1";
        $database = $this->data['appName'] . "___" . $this->data['UUID'];
        $persistenceObject = new Persistence($config, $database);
        $persistenceObject->selectQuery($this->data);
        $dbConfig = $config['db'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $database . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' .$dbConfig["appuser"] . ';password='.$dbConfig["password"];
        $adapter = new Adapter($dbConfig);
        $selectData = Array (
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $adapter->query($this->data['query']);
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['name'], $selectData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $selectData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $selectData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $selectData['process_id']);
    }

    public function testUpdateQuery() {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "UPDATE ox_timesheet set name = 'Updated Task' where id = 1";
        $database = $this->data['appName'] . "___" . $this->data['UUID'];
        $persistenceObject = new Persistence($config, $database);
        $persistenceObject->updateQuery($this->data);
        $dbConfig = $config['db'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $database . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' .$dbConfig["appuser"] . ';password='.$dbConfig["password"];
        $adapter = new Adapter($dbConfig);
        $selectData = Array (
            'name' => "Updated Task",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $adapter->query("Select * from ox_timesheet where id = 1");
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['id'], 1);
        $this->assertEquals($tableFieldName[0]['name'], $selectData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $selectData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $selectData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $selectData['process_id']);
    }


    public function testUpdateQueryWithJoin() {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "UPDATE ox_timesheet AS b
            INNER JOIN ox_timesheet_client AS g ON b.client_id = g.id
            SET b.name = 'Task2', b.Description = 'Changed the description'
            WHERE  (b.id = 1) and g.id = 1";
        $database = $this->data['appName'] . "___" . $this->data['UUID'];
        $persistenceObject = new Persistence($config, $database);
        $persistenceObject->updateQuery($this->data);
        $dbConfig = $config['db'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $database . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' .$dbConfig["appuser"] . ';password='.$dbConfig["password"];
        $adapter = new Adapter($dbConfig);
        $selectData = Array (
            'name' => "Updated Task",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $adapter->query("Select * from ox_timesheet where id = 1");
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
//        print_r($tableFieldName);exit;
        $this->assertEquals($tableFieldName[0]['id'], 1);
        $this->assertEquals($tableFieldName[0]['name'], $selectData['name']);
        $this->assertEquals($tableFieldName[0]['client_id'], $selectData['client_id']);
        $this->assertEquals($tableFieldName[0]['description'], $selectData['description']);
        $this->assertEquals($tableFieldName[0]['process_id'], $selectData['process_id']);
    }


    public function testDeleteQuery() {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "Delete from ox_timesheet where id = 1";
        $database = $this->data['appName'] . "___" . $this->data['UUID'];
        $persistenceObject = new Persistence($config, $database);
        $persistenceObject->deleteQuery($this->data);
        $dbConfig = $config['db'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $database . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' .$dbConfig["appuser"] . ';password='.$dbConfig["password"];
        $adapter = new Adapter($dbConfig);
        $statement1 = $adapter->query("Select * from ox_timesheet where id = 1");
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName, null);
    }

//Code to delete all the enreies made to the timesheet table for testing purpose.
    public function testDeleteAllQuery() {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "Delete from ox_timesheet where 1";
        $database = $this->data['appName'] . "___" . $this->data['UUID'];
        $persistenceObject = new Persistence($config, $database);
        $persistenceObject->deleteQuery($this->data);
        $dbConfig = $config['db'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $database . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' .$dbConfig["appuser"] . ';password='.$dbConfig["password"];
        $adapter = new Adapter($dbConfig);
        $statement1 = $adapter->query("Select * from ox_timesheet where id = 1");
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName, null);
    }
}