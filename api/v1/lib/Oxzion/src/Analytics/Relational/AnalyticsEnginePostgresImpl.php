<?php
namespace Oxzion\Analytics\Relational;

use Oxzion\Analytics\AnalyticsEngine;
use Oxzion\Analytics\Relational\AnalyticsEngineRelational;

class AnalyticsEnginePostgresImpl extends AnalyticsEngineRelational implements AnalyticsEngine {

    public function __construct($config,$appDBAdapter,$appConfig) {
		$dbConfig['driver'] = 'Pgsql';
		$dbConfig['database'] = $config['database'];
		$dbConfig['host'] = $config['host'];
		$dbConfig['username'] = $config['username'];
		$dbConfig['password'] = $config['password'];
		$dbConfig['dsn'] = 'Pgsql:dbname=' . $config['database'] . ';host=' . $config['host'] . ';charset=utf8;username=' . $config["username"] . ';password=' . $config["password"] . '';
        parent::__construct($dbConfig,$appDBAdapter,$appConfig);
    }



}
?>