<?php
namespace Oxzion\Analytics\Relational;

use Oxzion\Analytics\Relational\AnalyticsEngineRelational;
use Zend\Db\Adapter\Adapter;

class AnalyticsEngineMySQLImpl extends AnalyticsEngineRelational {

    public function __construct($appDBAdapter,$appConfig)  {
		parent::__construct($appDBAdapter,$appConfig);
    }

    public function setConfig($config){
      $dbConfig['driver'] = 'Pdo';
      $dbConfig['database'] = $config['database'];
      $dbConfig['host'] = $config['host'];
      $dbConfig['username'] = $config['username'];
      $dbConfig['password'] = $config['password'];
      $dbConfig['dsn'] = 'mysql:dbname=' . $config['database'] . ';host=' . $config['host'] . ';charset=utf8;username=' . $config["username"] . ';password=' . $config["password"] . '';
      $this->dbConfig = $dbConfig;
      $this->dbAdapter = new Adapter($dbConfig);  
      }



}
?>