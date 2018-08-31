<?php
namespace Job;

require __DIR__.'/../autoload.php';

abstract class Task extends \Thread{
	protected $id;
	protected $params;
	// protected $autoloader;

	public function __construct($id, $params){
		// $this->autoloader = $autoloader;
		// $autoloader->register();
		$this->$id = $id;
		if(!is_array($params)){
			$this->params = json_decode($params, true);
			
		}else{
			$this->params = $params;
			$params = $id == 0 ? json_encode($params) : $params;
		}
		if(!$id == 0){
			$this->$id = $params;
		}
	}
	
	public function run(){
		print_r("Starting to execute Task");
		require __DIR__.'/../autoload.php';
		// $this->autoloader->register();
		date_default_timezone_set('UTC');
		$this->executeTask();	
	}

	abstract protected function executeTask();
	abstract public function cleanup();
}
?>