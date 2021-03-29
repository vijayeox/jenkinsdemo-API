<?php

namespace Oxzion\Analytics\API;

use Oxzion\Service\AnalyticsCustomAPIService;
use Zend\Db\Sql\Sql;

use function GuzzleHttp\json_encode;

class AnalyticsEngineCustomAPIImpl extends AnalyticsEngineAPI
{
    private $customAPIService;

    public function __construct($appDBAdapter, $appConfig, AnalyticsCustomAPIService $customAPIService)
    {
        parent::__construct($appDBAdapter, $appConfig);
        $this->customAPIService = $customAPIService;
    }

    public function getQuery()
    {
        return '';
    }

    public function setConfig($config)
    {
        parent::setConfig($config);
        $this->customAPIService->setConfig($config);
    }

    public function getData($app_name, $entity_name, $parameters)
    {
        $module = $this->config['module'];
        $finalResult= $this->customAPIService->$module();
        return $finalResult;
    }
}
