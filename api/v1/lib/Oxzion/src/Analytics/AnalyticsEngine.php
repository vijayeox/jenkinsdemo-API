<?php
namespace Oxzion\Analytics;

interface AnalyticsEngine
{

    public function runQuery($parameters, $appId);

}
?>