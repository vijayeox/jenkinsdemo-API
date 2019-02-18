<?php
namespace Oxzion\Analytics;

class AnalyticsFactory
{
    private static $instance;
    protected $config;

    public function __construct($config) {
        $this->config=$config;    
    }

    public function getInstance() {
        if (self::$instance === null) {
            self::$instance = new AnalyticsFactory();
        }
        return self::$instance;
    }

    public function getAnalyticsEngine() {
        return new Elastic\AnalyticsEngineImpl($this->config);
    }


}
