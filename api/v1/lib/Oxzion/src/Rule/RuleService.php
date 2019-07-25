<?php
namespace Oxzion\Rule;

use Exception;
use Oxzion\Service\AbstractService;
use Oxzion\Db\Persistence\Persistence;

class RuleService extends AbstractService {

	private $fileExt = ".php";

    public function __construct($config, $dbAdapter){
		parent::__construct($config, $dbAdapter);
		$this->ruleEngineDir = $this->config['RULE_FOLDER'];
    	if (!is_dir($this->ruleEngineDir)) mkdir($this->ruleEngineDir, 0777, true);
    }

    public function rule($appId,$className,$dataArray=array(),Persistence $persistenceService=null){
		try{
		$result = $this->ruleEngineFile($appId,$className);
		if($result){
			$obj = new $className;
			$output = $obj->runRule($dataArray,$persistenceService);
			return $output;
		}
		return $result;
	}catch(Exception $e){
		print_r($e->getMessage());
	}
		return false;
	}  
	
	private function ruleEngineFile($appId,$className){ 
		$file = $className.$this->fileExt;
		$path = $this->ruleEngineDir.$appId."/".$file;
		if((file_exists($path))){
			// include $path; 
			require_once($path);
		}else{
			return false;
		}
		return true;
	}
}
?>