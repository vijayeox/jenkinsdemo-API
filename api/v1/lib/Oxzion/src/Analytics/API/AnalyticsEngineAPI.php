<?php
namespace Oxzion\Analytics\API;

use Oxzion\Analytics\AnalyticsAbstract;


class AnalyticsEngineAPI extends AnalyticsAbstract {

    public function __construct($config,$appDBAdapter,$appConfig) {
      parent::__construct($config,$appDBAdapter,$appConfig);

    }

}
?>