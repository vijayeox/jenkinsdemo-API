<?php
namespace Oxzion\Analytics\Relational;

use Oxzion\Analytics\Relational\AnalyticsEngineRelational;
use Zend\Db\Adapter\Adapter;

class AnalyticsEnginePostgresImpl extends AnalyticsEngineRelational {

    public function __construct($appDBAdapter,$appConfig) {
		parent::__construct($appDBAdapter,$appConfig);
    }

    public function setConfig($config){
      $dbConfig['driver'] = 'Pgsql';
      $dbConfig['database'] = $config['database'];
      $dbConfig['host'] = $config['host'];
      $dbConfig['username'] = $config['username'];
      $dbConfig['password'] = $config['password'];
      $dbConfig['dsn'] = 'Pgsql:dbname=' . $config['database'] . ';host=' . $config['host'] . ';charset=utf8;username=' . $config["username"] . ';password=' . $config["password"] . '';
      $this->dbConfig = $dbConfig;
      $this->dbAdapter = new Adapter($dbConfig);
  }



}
?>