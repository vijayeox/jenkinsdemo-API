<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Workflow\ProcessEngine;
use Oxzion\Utils\RestClient;

class ProcessEngineImpl implements ProcessEngine {
	private $restClient;
	public function __construct(){
		$this->restClient = new RestClient(Config::ENGINE_URL);
	}
	public function setRestClient($restClient){
		$this->restClient = $restClient;
	}

	public function getProcessDefinition($id){
		$query = 'process-definition/'.$id;
		try {
			$response = $this->restClient->get($query);
			$result = json_decode($response,true);
			return $result;
		} catch (Exception $e){
			return 0;
		}
	}

	public function startProcess($key ,$tenantId, $processVariables = array()){
		$query = 'process-definition/key/'.$key.'/tenant-id/'.$tenantId.'/start';
		try{
			if($processVariables){
				$response = $this->restClient->post($query, array("variables"=>json_encode((object)$processVariables)));
			} else {
				$response = $this->restClient->post($query);
			}
			$result = json_decode($response,true);
			if($result){
				return $result;
			} else {
				return 0;
			}
		} catch (Exception $e){
			return 0;
		}
	}

	public function stopProcess($id){
		$query = 'process-definition/'.$id;
		return $this->restClient->delete($query)?0:1;
	}

	public function getProcessDefinitionsByParams($id,$paramsArray){
		$query = 'process-definition/';
		return $this->restClient->get($query, $paramsArray);
	}
}
?>