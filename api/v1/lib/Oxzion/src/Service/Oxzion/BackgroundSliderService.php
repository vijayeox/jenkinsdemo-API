<?php
namespace Oxzion;
require_once __DIR__.'/../Common/Config.php';
include __DIR__.'/../autoload.php';
include __DIR__.'/../../../../bin/init.php';
use Job\Job;

class BackgroundSliderService {
	private static $instance;
	private $dao;
	private $job;
	private static $autoloader; 

	private function __construct(){
		$this->dao = new Dao();
		$this->job = Job::getInstance();
		date_default_timezone_set('UTC');
		static::$autoloader = require_once(__DIR__.'/../../../vendor/autoload.php');
	}

	public static function getInstance(){
		if(!isset(static::$instance)){
			static::$instance = new BackgroundSliderService();
		}
		return static::$instance;
	}

	public function sync(){
		\VA_Logic_Custom::syncSocialStreams();
	}
}
?>