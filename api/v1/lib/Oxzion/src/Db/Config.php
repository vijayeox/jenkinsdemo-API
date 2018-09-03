<?php
namespace Oxzion\Db;
class Config {
	public $config;
    public function __construct() {
    	$this->config = new \Zend\Config\Config( include APPLICATION_PATH.'/config/autoload/global.php');
    }
    public function getConfig(){
    	return $this->config->toArray();
    }
}
?>