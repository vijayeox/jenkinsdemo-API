<?php
namespace clients\hub\rules\Import;

use Oxzion\AppDelegate\AppDelegate;
use Oxzion\Db\Persistence\Persistence;

class Import extends AppDelegate
{
    private $logger;
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    // private $adapter;
    // public function __construct($config, $database, $adapter)
    // {
    //     $this->database = $database;
    //     $dbConfig = $config['db'];
    //     $dbConfig['database'] = 'mysql';
    //     $dbConfig['dsn'] = 'mysql:dbname=mysql;host=' . $dbConfig['host'] . ';charset=utf8;username=' . $dbConfig["username"] . ';password=' . $dbConfig["password"] . '';
    //     $this->mysqlAdapter = new Adapter($dbConfig);
    //     parent::__construct($config, $adapter);
    // }

    public function execute(array $data, Persistence $persistenceService)
    {
        print_r($persistenceService);exit;
        $this->logger->info("Executing Rate Card");
        $select = "Select * FROM premium_rate_card WHERE product ='" . $data['product'] . "' AND start_date = '" . $data['start_date'] . "' AND end_date = '" . $data['end_date'] . "'";
        $result = $persistenceService->selectQuery($select);
        while ($result->next()) {
            $premiumRateCardDetails[] = $result->current();
        }
        return $premiumRateCardDetails;
    }
}
