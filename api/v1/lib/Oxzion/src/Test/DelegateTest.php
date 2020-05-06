<?php
namespace Oxzion\Test;

use Oxzion\Test\ServiceTest;
use Oxzion\Db\Migration\Migration;
use Oxzion\Db\Persistence\Persistence;

use PHPUnit\DbUnit\TestCaseTrait;

abstract class DelegateTest extends ServiceTest
{
    use TestCaseTrait;

    protected $data;
    protected $migrationObject;
    protected $persistence;
    protected $database;
    protected static $pdo = null;
    protected static $connection = null;
    protected $appDbAdapter; // HANDLING ROLLBACK ONLY FOR APP DB LIKE IN ABSTRACT SERVICE TEST

    public function getConnection()
    {
        if (!isset(static::$pdo)) {
            static::$pdo = $this->getDbAdapter()->getDriver()->getConnection()->getResource();
            static::$connection = $this->createDefaultDBConnection(static::$pdo);
        }
        return static::$connection;
    }

     protected function doMigration($data,$migrationFolder)
     {
        $this->appUuid = $data['UUID'];
        $this->appName = $data['appName']; 
        $this->description = $data['description'];
        $config = $this->getApplicationConfig();
        $this->migrationObject = new Migration($config, $this->appName, $this->appUuid, $this->description);
        $adapter = $this->migrationObject->getAdapter();
        $this->appDbAdapter = $adapter;
        // $this->setDbAdapter($adapter);
        $this->persistence = new Persistence($config, $this->appName, $this->appUuid);
        $this->persistence->setAdapter($adapter);
        $this->database = $this->migrationObject->getDatabase();
        $testCase = $this->migrationObject->migrate($migrationFolder);
        
     }
    
}
