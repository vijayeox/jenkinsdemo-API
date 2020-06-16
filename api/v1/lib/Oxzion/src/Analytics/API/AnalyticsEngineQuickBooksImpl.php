<?php

namespace Oxzion\Analytics\API;

use Oxzion\Service\QuickBooksService;
use Zend\Db\Sql\Sql;

use function GuzzleHttp\json_encode;

class AnalyticsEngineQuickBooksImpl extends AnalyticsEngineAPI
{

  private $quickbookService;

  public function __construct($appDBAdapter, $appConfig, QuickBooksService $quickbookService)
  {
    parent::__construct(null, $appDBAdapter, $appConfig);
    $this->quickbookService = $quickbookService;
  }

  public function setConfig($config){
    parent::setConfig($config);
    $this->quickbookService->setConfig($config);
  }
  public function getData($app_name,$entity_name,$parameters)
  {
    $finalResult['meta']=$parameters;
    $qbService = $this->quickbookService;
    $dateperiod = null;
    if (!empty($parameters['filter']) || !empty($parameters['inline_filter'])) {
      $parameters = $this->parseFilter($parameters);
    } 
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
    if ($this->config!=$result['config']) {
      $dsid = $this->config['dsid'];
      unset($result['config']['dsid']);
      $this->updateConfig($result['config'], $dsid);  
    }
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


  public function parseFilter($parameters){
    if (isset($parameters['filter'])) {
        $filter = $parameters['filter'];
    }
   if (isset($parameters['inline_filter'])) {
        $filter = $parameters['inline_filter'];
    }
    if (isset($filter[0][0][0])){
      if ($filter[0][0][0]=='date_period' || $filter[0][0][0]=='date-period') {
        $startdate=Date('Y-m-d',strtotime($filter[0][0][2]));
        $enddate=Date('Y-m-d',strtotime($filter[0][2][2]));
        $parameters['date_period']=$startdate.'/'.$enddate;
      }  
    }
    return $parameters;
  }
}
?>