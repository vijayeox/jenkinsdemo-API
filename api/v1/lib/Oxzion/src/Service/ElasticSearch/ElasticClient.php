<?php
namespace Oxzion\Service\ElasticSearch;
use Elasticsearch\ClientBuilder;
use Oxzion\Db\Config;
class ElasticClient{

	public static function createClient(){
        $conf = new Config();
        $config = $conf->getConfig();
		$clientsettings['host'] = $config['elasticsearch']['serveraddress'];
		$clientsettings['user'] = $config['elasticsearch']['user'];
		$clientsettings['pass'] = $config['elasticsearch']['password'];
		$clientsettings['type'] = $config['elasticsearch']['type'];
		$clientsettings['port'] = $config['elasticsearch']['port'];
		$clientsettings['scheme'] = $config['elasticsearch']['scheme'];
		return ClientBuilder::create()->setHosts(array($clientsettings))->build();
	}
	public static function getConfigByParam($param){
		$ini = parse_ini_file(dirname(dirname(dirname(dirname(__DIR__)))).'/application/configs/application.ini');
		return $ini['resources.elastic.'.$param];
	}
}
?>