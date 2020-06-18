<?php
namespace Oxzion\Analytics\Relational;

use Oxzion\Analytics\Relational\AnalyticsEngineRelational;

class AnalyticsEnginePostgresImpl extends AnalyticsEngineRelational {

    public function __construct($appDBAdapter,$appConfig) {
		parent::__construct($appDBAdapter,$appConfig);
    }



}
?>