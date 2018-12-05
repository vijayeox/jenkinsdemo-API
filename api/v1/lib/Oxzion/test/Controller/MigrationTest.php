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
use Zend\Stdlib\ArrayUtils;

class MigrationTest extends ServiceTest {
//    use TestCaseTrait;

    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;
    protected $traceError = true;


    public function setUp() : void {
        $this->loadConfig();
        parent::setUp();
    }

    protected function loadConfig() {
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/autoload/global.php', include __DIR__ . '/../../../../config/autoload/local.php');
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php',$configOverrides);
        $this->setApplicationConfig($configOverrides);
    }

    public function testInitDB() {
        $config = $this->getApplicationConfig();
        $data = Array (
            "appName" => 'ox_app_2',
            'UUID' => 123889,
            'description' => 'FirstApp'
        );
        $database = $data['appName'] . "___" . $data['UUID'];
        $migrationObject = new Migration($config, $database);
        $testCase = $migrationObject->initDB($data);
        $config = $this->getApplicationConfig();
        $config = $config['db'];
        $config['dsn'] = 'mysql:dbname=' . $config['database'] . ';host=' . $config['host'] . ';charset=utf8;username=' .$config["username"] . ';password='.$config["password"];
        $adapter = new Adapter($config);
        $sqlQuery = 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' .$database . '"';
        $statement = $adapter->query($sqlQuery);
        $result = $statement->execute();
        $value = $result->count();

        $sqlQuery1 = "SHOW COLUMNS FROM ox_app_migration_version";
        $statement1 = $adapter->query($sqlQuery1);
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals(1, $value);
        $this->assertEquals($tableFieldName[0]['Field'], "id");
        $this->assertEquals($tableFieldName[1]['Field'], "app_id");
        $this->assertEquals($tableFieldName[2]['Field'], "app_name");
        $this->assertEquals($tableFieldName[3]['Field'], "version_number");
        $this->assertEquals($tableFieldName[4]['Field'], "org_id");
        $this->assertEquals($tableFieldName[5]['Field'], "date_created");
        $this->assertEquals($tableFieldName[6]['Field'], "date_modified");
    }


    public function testInitDBWithOutAppName() {
        $config = $this->getApplicationConfig();
        $data = Array (
            'UUID' => 123889,
            'description' => 'FirstApp'
        );
        $database = $data['appName'] . "___" . $data['UUID'];
        $migrationObject = new Migration($config, $database);
        $testCase = $migrationObject->initDB($data);
        $this->assertEquals(0, $testCase);
    }

    public function testMigrate() {
        $config = $this->getApplicationConfig();
        $data = Array (
            'appName' => 'ox_app_2',
            'UUID' => 123889,
            'description' => 'FirstApp'
        );
        $database = $data['appName'] . "___" . $data['UUID'];
        $dbConfig = $config['db'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $database . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' .$dbConfig["username"] . ';password='.$dbConfig["password"];
        $adapter = new Adapter($dbConfig);
        $dataSet = array_diff(scandir(dirname(__FILE__) ."/../Migration/"), array(".", ".."));
        $migrationFolder = dirname(__FILE__) ."/../Migration/";
        $migrationObject = new Migration($config, $database);
        $testCase = $migrationObject->migrationSql($dataSet, $migrationFolder, $data);

//Check to see if the version table is updated or not
        $versionArray = '1.0, 1.1';
        $statement1 = $adapter->query("Select id, version_number from ox_app_migration_version where version_number IN ($versionArray) order by version_number asc");
        $result1 = $statement1->execute();
        while($result1->next()) {
            $tableFieldName[] = $result1->current();
        }
        $this->assertEquals($tableFieldName[0]['version_number'], "1.0");
        $this->assertEquals($tableFieldName[1]['version_number'], "1.1");

    }

    public function testMigrateWrongAppName() {
        $config = $this->getApplicationConfig();
        $data = Array (
            'appName' => 'ox_app_4',
            'UUID' => 123889,
            'description' => 'FirstApp'
        );
        $database = $data['appName'] . "___" . $data['UUID'];
        $dbConfig = $config['db'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $dbConfig['database'] . ';host=' . $dbConfig['host'] . ';charset=utf8;username=' .$dbConfig["username"] . ';password='.$dbConfig["password"];
        $adapter = new Adapter($dbConfig);
        $dataSet = array_diff(scandir(dirname(__FILE__) ."/../Migration/"), array(".", ".."));
        $migrationFolder = dirname(__FILE__) ."/../Migration/";
        $migrationObject = new Migration($config, $database);
        $testCase = $migrationObject->migrationSql($dataSet, $migrationFolder, $data);

        $sqlQuery = 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . $database . '"';
        $statement = $adapter->query($sqlQuery);
        $result = $statement->execute();
        $result = $result->count();
        $this->assertEquals($result, "0");

    }


}