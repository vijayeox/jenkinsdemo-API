<?php
namespace Oxzion\Db\Persistence;

use Oxzion\Db\Migration\Migration;
use Oxzion\Test\ServiceTest;
use Oxzion\Transaction\TransactionManager;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class PersistenceTest extends ServiceTest
{

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    protected $traceError = true;
    protected $data;
    private $database;
    private $adapter;

    public function setUp(): void
    {
        $this->loadConfig();
        $this->data = array(
            "appName" => 'ox_app_2',
            'UUID' => 123889,
            'description' => 'FirstApp',
        );

        $config = $this->getApplicationConfig();
        
        $migrationObject = new Migration($config, $this->data['appName'], $this->data['UUID'], $this->data['description']);
        $this->adapter = $migrationObject->getAdapter();
        $this->database = $migrationObject->getDatabase();
        $migrationFolder = dirname(__FILE__) . "/scripts/";
        $testCase = $migrationObject->migrate($migrationFolder);

        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();

        if ($this->getName() == 'testInsertQueryWithJoinSelect' || $this->getName() == 'testSelectQuery' || $this->getName() == 'testSelectQueryWithJoin' || $this->getName() == 'testUpdateQuery' || $this->getName() == 'testUpdateQueryWithJoin') {
            $insertQuery = "INSERT INTO ox_timesheet (`name`, `client_id`, `description`, `process_id`, `date_created`,`ox_app_org_id`) VALUES ('Task1', 1, 'New Task', 2, '2019-01-02 12:00:00',1)";
            $statement = $this->adapter->query($insertQuery);
            $result = $statement->execute();
            $update = "UPDATE ox_timesheet_client SET ox_app_org_id = 1 where id = 1";
            $statement = $this->adapter->query($update);
            $result = $statement->execute();
        }
    }

    public function tearDown(): void
    {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $query = "DROP DATABASE " . $this->database;
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
        AuthContext::put(AuthConstants::ORG_ID, 1);
        $persistenceObject = new Persistence($config, $this->data["appName"], $this->data['UUID']);
        $persistenceObject->setAdapter($this->adapter);
        $insertData = array(
            'name' => "Task2",
            'client_id' => 1,
            'description' => "New Task 2",
            'process_id' => 2,
        );
        $insertQuery = "INSERT INTO ox_timesheet (`name`, `client_id`, `description`, `process_id`, `date_created`)
                        VALUES ('" . $insertData['name'] . "', " . $insertData['client_id'] . ", '" . $insertData['description'] . "', " . $insertData['process_id'] . ", '2019-01-02 12:00:00')";
        $persistenceObject->insertQuery($insertQuery);
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
        AuthContext::put(AuthConstants::ORG_ID, 1);
        $persistenceObject = new Persistence($config, $this->data["appName"], $this->data['UUID']);
        $persistenceObject->setAdapter($this->adapter);
        $insertData = array(
            'id' => 1,
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2,
        );
        $insertQuery = "INSERT INTO ox_timesheet (`name`, `client_id`, `description`, `process_id`, `date_created`)
          SELECT `ox_timesheet`.`name`, `ox_timesheet`.`client_id`, `ox_timesheet`.`description`, `ox_timesheet`.`process_id`, `ox_timesheet`.`date_created` FROM ox_timesheet LEFT JOIN
          ox_timesheet_client ON ox_timesheet_client.id = ox_timesheet.client_id";
        $persistenceObject->insertQuery($insertQuery);
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
        $query = "Select * from ox_timesheet where id > 0";
        $orgId = AuthContext::put(AuthConstants::ORG_ID,1);
        $persistenceObject = new Persistence($config, $this->data["appName"], $this->data['UUID']);
        $persistenceObject->setAdapter($this->adapter);
        $result1 = $persistenceObject->selectQuery($query);
        $selectData = array(
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2,
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
        $orgId = AuthContext::put(AuthConstants::ORG_ID,1);
        $query = "SELECT `ox_timesheet`.`name`, `ox_timesheet`.`client_id`, `ox_timesheet`.`description`, `ox_timesheet`.`process_id`, `ox_timesheet`.`date_created` FROM ox_timesheet
                                LEFT JOIN ox_timesheet_client ON ox_timesheet_client.id = ox_timesheet.client_id where ox_timesheet.client_id = 1";
        $persistenceObject = new Persistence($config, $this->data["appName"], $this->data['UUID']);
        $persistenceObject->selectQuery($query);
        $persistenceObject->setAdapter($this->adapter);
        $selectData = array(
            'name' => "Task1",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2,
        );
        $statement1 = $this->adapter->query($query);
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
        $orgId = AuthContext::put(AuthConstants::ORG_ID,1);
        $query = "UPDATE ox_timesheet set name = 'Updated Task' WHERE id > 0";
        $persistenceObject = new Persistence($config, $this->data["appName"], $this->data['UUID']);
        $persistenceObject->setAdapter($this->adapter);
        $persistenceObject->updateQuery($query);
        $selectData = array(
            'name' => "Updated Task",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2,
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
        AuthContext::put(AuthConstants::ORG_ID, 1);
        $query = "UPDATE ox_timesheet AS b
            INNER JOIN ox_timesheet_client AS g ON b.client_id = g.id
            SET b.name = 'Updated Task', b.Description = 'New Task'
            WHERE  (b.client_id = 1) and g.id = 1";
        $persistenceObject = new Persistence($config, $this->data["appName"], $this->data['UUID']);
        $persistenceObject->setAdapter($this->adapter);
        $persistenceObject->updateQuery($query);
        $selectData = array(
            'name' => "Updated Task",
            'client_id' => 1,
            'description' => "New Task",
            'process_id' => 2,
        );
        $statement1 = $this->adapter->query("Select * from ox_timesheet");
        $result1 = $statement1->execute();
        $resultSet = new ResultSet();
        $resultset = $resultSet->initialize($result1);
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
        $query = "Delete from ox_timesheet where id > 0";
        $orgId = AuthContext::put(AuthConstants::ORG_ID,1);
        $persistenceObject = new Persistence($config, $this->data["appName"], $this->data['UUID']);
        $persistenceObject->setAdapter($this->adapter);
        $persistenceObject->deleteQuery($query);
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
        $query = "Delete from ox_timesheet where id > 0";
        $orgId = AuthContext::put(AuthConstants::ORG_ID,1);
        $persistenceObject = new Persistence($config, $this->data["appName"], $this->data['UUID']);
        $persistenceObject->setAdapter($this->adapter);
        $persistenceObject->deleteQuery($query);
        $statement1 = $this->adapter->query("Select * from ox_timesheet");
        $result1 = $statement1->execute();
        $tableFieldName = array();
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName, array());
    }
}
