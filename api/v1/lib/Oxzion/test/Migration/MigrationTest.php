<?php
namespace Oxzion\Db\Migration;

use Oxzion\Db\Migration\Migration;
use Oxzion\Test\ServiceTest;
use Oxzion\Transaction\TransactionManager;
use Zend\Db\Adapter\Adapter;
use Exception;

class MigrationTest extends ServiceTest
{
//    use TestCaseTrait;

    private static $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;
    protected $traceError = true;
    private $data;
    private $database;
    private $adapter;
    private $migrationObject;

    public function setUp(): void
    {
        $this->loadConfig();
        $this->data = array(
            "appName" => 'ox_app_2',
            'UUID' => 123889,
            'description' => 'FirstApp',
        );
        if ($this->getName() == 'testInitDBWithOutAppName') {
            unset($this->data['appName']);
        }else{
            $config = $this->getApplicationConfig();

            $this->migrationObject = new Migration($config, $this->data['appName'], $this->data['UUID'], $this->data['description']);
            $this->adapter = $this->migrationObject->getAdapter();
            $this->database = $this->migrationObject->getDatabase();
            $tm = TransactionManager::getInstance($this->adapter);
            $tm->setRollbackOnly(true);
        }

    }

    public function tearDown(): void
    {
        if($this->getName() != 'testInitDBWithOutAppName'){
            $tm = TransactionManager::getInstance($this->adapter);
            $tm->rollback();
        }
        $_REQUEST = [];
    }

    public function testInitDB()
    {
        $config = $this->getApplicationConfig();
        $sqlQuery = 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . $this->database . '"';
        $statement = $this->adapter->query($sqlQuery);
        $result = $statement->execute();
        $value = $result->count();

        $sqlQuery1 = "SHOW COLUMNS FROM ox_app_migration_version";
        $statement1 = $this->adapter->query($sqlQuery1);
        $result1 = $statement1->execute();
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals(1, $value);
        $this->assertEquals($tableFieldName[0]['Field'], "id");
        $this->assertEquals($tableFieldName[1]['Field'], "version_number");
        $this->assertEquals($tableFieldName[2]['Field'], "date_created");
        $this->assertEquals($tableFieldName[3]['Field'], "date_modified");
        $this->assertEquals($tableFieldName[4]['Field'], "description");
    }

    public function testInitDBWithOutAppName()
    {
        $config = $this->getApplicationConfig();
        $this->assertEquals(isset($this->data['appName']), false);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("appName and appId cannot be empty!");
        $this->migrationObject = new Migration($config, "", $this->data['UUID'], $this->data['description']);
    }

    public function testMigrate()
    {
        $config = $this->getApplicationConfig();
        $migrationFolder = dirname(__FILE__) . "/scripts/";
        $testCase = $this->migrationObject->migrate($migrationFolder);

        //Check to see if the version table is updated or not
        $versionArray = '1.0, 1.1';
        $sqlquery = "SELECT id ,version_number FROM ox_app_migration_version WHERE version_number IN ($versionArray) ORDER BY version_number ASC";
        $statement1 = $this->adapter->query($sqlquery);
        $result1 = $statement1->execute();
        while ($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['version_number'], "1.0");
        $this->assertEquals($tableFieldName[1]['version_number'], "1.1");
    }

}
