<?php
namespace Oxzion\Search;

class SearchFactory
{
    private static $instance;
    protected $config;

    public function __construct($config) {
        $this->config=$config;    
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new SearchFactory();
        }
        return self::$instance;
    }

    public function getSearchEngine() {
        return new Elastic\SearchEngineImpl($this->config);
    }

    public function getIndexer() {
        return new Elastic\IndexerImpl($this->config);
    }

}
