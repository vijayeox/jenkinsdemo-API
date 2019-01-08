<?php
namespace Oxzion\Analytics;

class AnalyticsFactory
{
    private static $instance;

    protected function __construct() {
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new AnalyticsFactory();
        }
        return self::$instance;
    }

    public static function getAnalyticsEngine() {
        return new Elastic\AnalyticsEngine();
    }


}
