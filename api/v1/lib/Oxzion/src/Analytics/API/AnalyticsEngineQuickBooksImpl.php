<?php

namespace Oxzion\Analytics\API;

use Oxzion\Service\QuickBooksService;
use Zend\Db\Sql\Sql;
use Oxzion\Analytics\AnalyticsEngine;

use function GuzzleHttp\json_encode;

class AnalyticsEngineQuickBooksImpl extends AnalyticsEngineAPI implements AnalyticsEngine
{

  public function __construct($config, $appDBAdapter, $appConfig)
  {
    parent::__construct($config, $appDBAdapter, $appConfig);
  }

  public function getData($app_name,$entity_name,$parameters)
  {
    $finalResult['meta']=$parameters;
    $qbService = new QuickBooksService($this->config);
    $dateperiod = null;
    if (!empty($parameters['date-period'])) $dateperiod = $parameters['date-period'];
    if (!empty($parameters['date_period'])) $dateperiod =  $parameters['date_period'];

    if ($dateperiod) {
      $period = explode('/', $dateperiod);
      $startdate = date('Y-m-d', strtotime($period[0]));
      $enddate =  date('Y-m-d', strtotime($period[1]));
    } else {
      $startdate = date('Y') . '-01-01';
      $enddate = date('Y') . '-12-31';
    }
    $parameters['startdate'] = $startdate;
    $parameters['enddate'] = $enddate;
    $result = $qbService->getReport($parameters);
    $finalResult['data']=$result['data'];
    $dsid = $this->config['dsid'];
    unset($result['config']['dsid']);
    $this->updateConfig($result['config'], $dsid);
    return $finalResult;
  }

  public function updateConfig($newConfig, $dsid)
  {
    $config['data'] = $newConfig;
    $jsonString = json_encode($config);
    $sql    = new Sql($this->appDBAdapter);
    $update = $sql->update();
    $update->table('ox_datasource')->set(['configuration' => $jsonString])->where(['id' => $dsid]);
    $statement = $sql->prepareStatementForSqlObject($update);
    $result = $statement->execute();
  }
}
?>