<?php
namespace Oxzion\Analytics\API;

use Oxzion\Analytics\AnalyticsAbstract;


abstract class AnalyticsEngineAPI extends AnalyticsAbstract {

    public function __construct($appDBAdapter,$appConfig) {
      parent::__construct($appDBAdapter,$appConfig);

    }

}
?>