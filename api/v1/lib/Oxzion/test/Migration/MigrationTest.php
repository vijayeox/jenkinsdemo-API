<?php
namespace Oxzion\Db\Migration;

use Oxzion\Test\ServiceTest;
use Oxzion\Service\AbstractService;
use Zend\Db\Adapter\Adapter;
use Oxzion\Db\Migration\Migration;
use PHPUnit\DbUnit\TestCaseTrait;
use Zend\Db\Adapter\AdapterInterface;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Oxzion\Transaction\TransactionManager;

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

    public function setUp() : void
    {
        $this->loadConfig();
        $this->data = array(
            "appName" => 'ox_app_2',
            'UUID' => 123889,
            'description' => 'FirstApp'
        );
        if ($this->getName() == 'testInitDBWithOutAppName') {
            unset($this->data['appName']);
        } elseif ($this->getName() == 'testMigrateWrongAppName') {
            $this->data['appName'] = 'ox_app_4';
        }
        $this->data['appName'] = isset($this->data['appName']) ? $this->data['appName'] : null;
        $config = $this->getApplicationConfig();
        $config = $config['db'];
        $this->database = $this->data['appName'] . "___" . $this->data['UUID'];
        $config['database']=$this->database;
        $config['dsn'] = 'mysql:dbname=' . $this->database . ';host=' . $config['host'] . ';charset=utf8;username='.$config["username"].';password='.$config["password"].'';
        $this->adapter = new Adapter($config);
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
    }
    public function tearDown() : void
    {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $_REQUEST = [];
    }

    public function testInitDB()
    {
        $config = $this->getApplicationConfig();
        $migrationObject = new Migration($config, $this->database, $this->adapter);
        $testCase = $migrationObject->initDB($this->data);
        $sqlQuery = 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' .$this->database . '"';
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
        $migrationObject = new Migration($config, $this->database, $this->adapter);
        $testCase = $migrationObject->initDB($this->data);
        $this->assertEquals(0, $testCase);
    }

    public function testMigrate()
    {
        $config = $this->getApplicationConfig();
        $migrationObject = new Migration($config, $this->database, $this->adapter);
        $testCase = $migrationObject->initDB($this->data);
        $dataSet = array_diff(scandir(dirname(__FILE__) ."/scripts/"), array(".", ".."));
        $migrationFolder = dirname(__FILE__) ."/scripts/";
        $migrationObject = new Migration($config, $this->database, $this->adapter);
        $testCase = $migrationObject->migrationSql($dataSet, $migrationFolder, $this->data);

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

    public function testMigrateWrongAppName()
    {
        $config = $this->getApplicationConfig();
        $this->assertEquals($this->data['appName'], 'ox_app_4');
        $dataSet = array_diff(scandir(dirname(__FILE__) ."/scripts/"), array(".", ".."));
        $migrationFolder = dirname(__FILE__) ."/scripts/";
        $migrationObject = new Migration($config, $this->database, $this->adapter);
        $testCase = $migrationObject->migrationSql($dataSet, $migrationFolder, $this->data);
        $sqlQuery = 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . $this->database . '"';
        $dbConfig = $config['db'];
        $dbConfig['database']='mysql';
        $dbConfig['dsn'] = 'mysql:dbname=mysql;host=' . $dbConfig['host'] . ';charset=utf8;username='.$dbConfig["username"].';password='.$dbConfig["password"].'';
        $mysqlAdapter = new Adapter($dbConfig);
        
        $statement = $mysqlAdapter->query($sqlQuery);
        $result = $statement->execute();
        $result = $result->count();
        $this->assertEquals($result, "0");
    }
}
