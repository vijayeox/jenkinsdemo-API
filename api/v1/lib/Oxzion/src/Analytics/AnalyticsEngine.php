<?php
namespace Oxzion\Analytics;

interface AnalyticsEngine
{

    public function runQuery($appId,$type,$parameters);

    public function getData($appId,$type,$parameters);

}
?>