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
use Bos\Transaction\TransactionManager;

class MigrationTest extends ServiceTest {
//    use TestCaseTrait;

    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;
    protected $traceError = true;
    private $data;
    private $database;
    private $adapter;

    public function setUp() : void {
        $this->loadConfig();
        $this->data = Array (
            "appName" => 'ox_app_2',
            'UUID' => 123889,
            'description' => 'FirstApp'
        );
        if($this->getName() == 'testInitDBWithOutAppName'){
            unset($this->data['appName']);
        }else if($this->getName() == 'testMigrateWrongAppName'){
            $this->data['appName'] = 'ox_app_4';
        }
        $config = $this->getApplicationConfig();
        $config = $config['db'];
        $this->database = $this->data['appName'] . "___" . $this->data['UUID'];
        $config['dsn'] = 'mysql:dbname=' . $this->database . ';host=' . $config['host'] . ';charset=utf8;username='.$config["appuser"].';password='.$config["password"].'';
        $this->adapter = new Adapter($config);
        
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
        
    }
    public function tearDown() : void {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $_REQUEST = [];
    }

    public function testInitDB() {
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
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals(1, $value);
        $this->assertEquals($tableFieldName[0]['Field'], "id");
        $this->assertEquals($tableFieldName[1]['Field'], "version_number");
        $this->assertEquals($tableFieldName[2]['Field'], "date_created");
        $this->assertEquals($tableFieldName[3]['Field'], "date_modified");
        $this->assertEquals($tableFieldName[4]['Field'], "description");

    }

    // To be reviewed
    // public function testInitDBWithOutAppName() {
    //     $config = $this->getApplicationConfig();
    //     $this->assertEquals(isset($this->data['appName']), false);
    //     $migrationObject = new Migration($config, $this->database, $this->adapter);
    //     $testCase = $migrationObject->initDB($data);
    //     $this->assertEquals(0, $testCase);
    // }

    public function testMigrate() {
        $config = $this->getApplicationConfig();
        $dataSet = array_diff(scandir(dirname(__FILE__) ."/../Migration/"), array(".", ".."));
        $migrationFolder = dirname(__FILE__) ."/../Migration/";
        $migrationObject = new Migration($config, $this->database, $this->adapter);
        $testCase = $migrationObject->migrationSql($dataSet, $migrationFolder, $data);

//Check to see if the version table is updated or not
        $versionArray = '1.0, 1.1';
        $statement1 = $this->adapter->query("Select id, version_number from ox_app_migration_version where version_number IN ($versionArray) order by version_number asc");
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['version_number'], "1.0");
        $this->assertEquals($tableFieldName[1]['version_number'], "1.1");

    }

    // To be reviewed
    // public function testMigrateWrongAppName() {
    //     $config = $this->getApplicationConfig();
    //     $this->assertEquals($this->data['appName'], 'ox_app_4');
    //     $dataSet = array_diff(scandir(dirname(__FILE__) ."/../Migration/"), array(".", ".."));
    //     $migrationFolder = dirname(__FILE__) ."/../Migration/";
    //     $migrationObject = new Migration($config, $this->database, $this->adapter);
    //     $testCase = $migrationObject->migrationSql($dataSet, $migrationFolder, $data);

    //     $sqlQuery = 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . $this->database . '"';
    //     $statement = $this->adapter->query($sqlQuery);
    //     $result = $statement->execute();
    //     $result = $result->count();
    //     $this->assertEquals($result, "0");

    // }


}