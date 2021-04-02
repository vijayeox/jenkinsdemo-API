<?php
namespace Oxzion\Service;

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\ReportService\ReportService;

class AnalyticsCustomAPIService
{
    private $dataService;
    private $loginHelper;
    private $data;
    private $config;

    public function __construct()
    {
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function unitTest()
    {  //Do not remove. Used for unit test
        $data = ["data"=>[["test1"=>1],["test2"=>2]]];
        return $data;
        //	return json_encode($data);
    }
}
