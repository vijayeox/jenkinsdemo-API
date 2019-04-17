<?php
namespace Oxzion\Db\Persistence;

use Oxzion\Test\ServiceTest;
use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\ArrayUtils;
use Bos\Transaction\TransactionManager;


class PersistenceTest extends ServiceTest {
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    protected $traceError = true;
    protected $data;
    private $database;
    private $adapter;

    public function setUp() : void {
        $this->loadConfig();
        $this->data =  Array (
            "appName" => 'ox_app_2',
            'UUID' => 123889,
            'description' => 'FirstApp',
        );
        $config = $this->getApplicationConfig();
        $config = $config['db'];
        $this->database = $this->data['appName'] . "___" . $this->data['UUID'];
        $config['dsn'] = 'mysql:dbname=' . $this->database . ';host=' . $config['host'] . ';charset=utf8;username='.$config["appuser"].';password='.$config["password"].'';
        $this->adapter = new Adapter($config);
        
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
        if($this->getName() == 'testInsertQueryWithJoinSelect' || $this->getName() == 'testSelectQuery' || $this->getName() == 'testSelectQueryWithJoin' || $this->getName() == 'testUpdateQuery' || $this->getName() == 'testUpdateQueryWithJoin'){
            $insertQuery = "INSERT INTO ox_timesheet (`id`, `name`, `client_id`, `description`, `process_id`, `date_created`,`ox_app_org_id`) 
VALUES (1, 'Task1', 1, 'New Task', 2, '2019-01-02 12:00:00',1)";
            $statement = $this->adapter->query($insertQuery);
            $result = $statement->execute();
            $update="UPDATE ox_timesheet_client SET ox_app_org_id = 1 where id = 1"; 
            $statement = $this->adapter->query($update);
            $result = $statement->execute();
               
        }
    }

    public function tearDown() : void {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $_REQUEST = [];
    }

    /**
     * Code to check the insert for a normal query
     */
    public function testInsertQuery () {
        $config = $this->getApplicationConfig();
        $persistenceObject = new Persistence($config, $this->database,$this->adapter);
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
         $sqlQuery1 = "Select * from ox_timesheet where id = 1 and name = '" . $insertData['name'] . "' and client_id = " . $insertData['client_id'] . " and description = '" . $insertData['description'] . "' and process_id = " . $insertData['process_id'];
        $statement1 = $this->adapter->query($sqlQuery1);
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
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
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
        $sqlQuery1 = "Select * from ox_timesheet where id = " . $insertData['id'] . " and name = '" . $insertData['name'] . "' and client_id = " . $insertData['client_id'] . " and description = '" . $insertData['description'] . "' and process_id = " . $insertData['process_id'];
        $statement1 = $this->adapter->query($sqlQuery1);
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
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $persistenceObject->selectQuery($this->data);
        $selectData = Array (
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $this->adapter->query($this->data['query']);
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
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $persistenceObject->selectQuery($this->data);
        $selectData = Array (
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $this->adapter->query($this->data['query']);
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
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $persistenceObject->updateQuery($this->data);
        $selectData = Array (
            'name' => "Updated Task",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $this->adapter->query("Select * from ox_timesheet where id = 1");
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
            SET b.name = 'Updated Task', b.Description = 'New Task'
            WHERE  (b.id = 1) and g.id = 1";
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $persistenceObject->updateQuery($this->data);
        $selectData = Array (
            'name' => "Updated Task",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2
        );
        $statement1 = $this->adapter->query("Select * from ox_timesheet where id = 1");
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


    public function testDeleteQuery() {
        $config = $this->getApplicationConfig();
        $this->data['query'] = "Delete from ox_timesheet where id = 1";
        $persistenceObject = new Persistence($config, $this->database, $this->adapter);
        $persistenceObject->deleteQuery($this->data);
        $statement1 = $this->adapter->query("Select * from ox_timesheet where id = 1");
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
        $persistenceObject = new Persistence($config, $this->database,$this->adapter);
        $persistenceObject->deleteQuery($this->data);
        $statement1 = $this->adapter->query("Select * from ox_timesheet where id = 1");
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName, null);
    }
}