<?php

namespace Oxzion\Db\Migration;

use Oxzion\Service\AbstractService;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Table;
use Oxzion\Utils\FileUtils;

class Migration extends AbstractService {

    protected $config;
    public function __construct($config, $database) {
        $this->database = $database;
        $this->config = $config;
        $config = $config['db'];
        $config['dsn'] = 'mysql:dbname=' . $config['database'] . ';host=' . $config['host'] . ';charset=utf8;username='.$config["username"].';password='.$config["password"].'';
        $adapter = new Adapter($config);
        parent::__construct($config, $adapter);
    }

    public function initDB($data) {
        $adapter = $this->getAdapter();
        $this->beginTransaction();
        try {
            if ($data['appName'] === NULL || $data['appName'] === "" || $data['UUID'] === NULL || $data['UUID'] === "") {
                $this->rollback();
                return 0;
            }
            $checkVersion = $this->checkDB(); //Code to check if the App Version is already installed.
            if (($checkVersion == 0)) {
                $sqlQuery = 'CREATE DATABASE IF NOT EXISTS ' . $this->database;
                $statement = $adapter->query($sqlQuery);
                $result = $statement->execute();
                if ($result) {
                    $appVersion = $this->insertAppVersion($data);
                    return 1;
                } else {
                    $this->updateMigration();
                    return 0;
                }
            } else {
                $this->rollback();
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
    }

    private function checkDB() {
        $adapter = $this->getAdapter();
        $sqlQuery = 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . $this->database . '"';
        $statement = $adapter->query($sqlQuery);
        $result = $statement->execute();
        return $result->count();
    }

    private function insertAppVersion($data) {
        //The code to add the app version information to the table, after it is been installed into the OXZion system.        
        $config = $this->config;
        $config['dsn'] = 'mysql:dbname='.$this->database.';host=' . $this->config['host'] . ';charset=utf8;username='.$this->config["username"].';password='.$this->config["password"];
        $adapter = new Adapter($config);

//Code to create the migration table once the app is installed to the system.
        $createQuery = "CREATE TABLE IF NOT EXISTS `ox_app_migration_version` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `version_number` varchar(1000) NOT NULL,
              `date_created` datetime NOT NULL,
              `date_modified` datetime NOT NULL,
              `description` varchar(10000),
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;";
        $statement1 = $adapter->query($createQuery);
        return $result = $statement1->execute();
    }

    public function migrationSql($fileList, $migrationFolder, $data) {
//The code to add the app version information to the table, after it is been installed into the OXZion system.
        try {
            $this->beginTransaction();
            $checkDb = $this->checkDB($data);
            if (($checkDb == 1)) {
                $config = $this->config;
                $config['dsn'] = 'mysql:dbname=' . $this->database . ';host=' . $this->config['host'] . ';charset=utf8;username=' . $this->config["username"] . ';password=' . $this->config["password"];
                $adapter = new Adapter($config);
                sort($fileList);
                foreach($fileList as $files) {
                    $versionExp = explode("__", $files);
                    $statement1 = $adapter->query("Select id from ox_app_migration_version where version_number = '$versionExp[0]'");
                    $result1 = $statement1->execute();
                    if ($result1->getAffectedRows() === 0) {
                        $versionArray[] = $versionExp[0];
                        $fileContent = file_get_contents($migrationFolder . $files);
                        $statement = $adapter->query($fileContent);
                        $statement->execute();
                    }
                }
                if (!empty($versionArray)) {
//Insert the latest version to the migration table if it is not already added.
                    foreach ($versionArray as $ver) {
                        $statement1 = $adapter->query("Select id from ox_app_migration_version where version_number = '$ver'");
                        $result1 = $statement1->execute();
                        if ($result1->getAffectedRows() === 0) {
                            $sqlQuery = "INSERT into ox_app_migration_version (version_number, date_created, description) VALUES('" . $ver . "', '" . Date('Y-m-d H:i:s') . "', '" . $data['description'] . "')";
                            $statement3 = $adapter->query($sqlQuery);
                            $statement3->execute();
                        }
                    }
                }
            } else {
                $this->rollback();
                return 0;
            }
            return 1;
        } catch(Exception $e) {
            $this->rollback();
            return 0;
        }
    }

}
