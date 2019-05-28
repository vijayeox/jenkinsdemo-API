<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Workflow\Activity;
use Oxzion\Utils\RestClient;

class ActivityImpl implements Activity {
	private $restClient;

	public function __construct(){
		$this->restClient = new RestClient(Config::ENGINE_URL);
	}

	public function setRestClient($restClient){
		$this->restClient = $restClient;
	}

	public function getActivity($activityId){
		try {
			$response =  $this->restClient->get("task/".$activityId);
			$result = json_decode($response,true);
			return $result;
		} catch(Exception $e){
			return array();
		}
	}

	public function getActivitiesByUser($userId,$params){
		try {
			$queryArray = array_merge($params,array("assignee"=>$userId));
			$response =  $this->restClient->get('task', $queryArray);
			$result = json_decode($response,true);
			return $result;
		} catch(Exception $e){
			return array();
		}
	}

	public function claimActivity($activityId,$userId){
		$query = 'task/'.$activityId.'/claim';
		try {
			$response =  $this->restClient->post($query, array('userId'=>$userId));
			$result = json_decode($response,true);
			return $result;
		} catch(Exception $e){
			return 0;
		}
	}
	public function unclaimActivity($activityId,$userId){
		$query = 'task/'.$activityId.'/unclaim';
		try {
			$response =  $this->restClient->post($query, array('userId'=>$userId));
			$result = json_decode($response,true);
			return $result;
		} catch(Exception $e){
			return 0;
		}
	}
	public function completeActivity($activityId,$parameterArray=array()){
		$query = 'task/'.$activityId.'/complete';
		try {
			$response =  $this->restClient->post($query,$parameterArray);
			$result = json_decode($response,true);
			return $result;
		} catch(Exception $e){
			return 0;
		}
	}
	public function submitTaskForm($activityId,$parameterArray=array()){
		$query = 'task/'.$activityId.'/submit-form';
		$params = array();
		foreach ($parameterArray as $k => $v) {
			$params[$k] = array('value'=>$v);
		}
		try {
			$response =  $this->restClient->post($query,json_encode(array('variables'=>$params)));
			$result = json_decode($response,true);
			return $result;
		} catch(Exception $e){
			return 0;
		}
	}
	
	public function resolveActivity($id,$parameterArray=array()){
		$query = 'task/'.$id.'/resolve';
		try {
			$response =  $this->restClient->post($query,$parameterArray);
			$result = json_decode($response,true);
			return $result;
		} catch(Exception $e){
			return 0;
		}
	}

	public function getActivitiesByGroup($groupId){
		try {
			$response =  $this->restClient->post('task', array("candidateGroup"=>$groupId));
			$result = json_decode($response,true);
			return $result;
		} catch(Exception $e){
			return array();
		}
	}

	public function saveActivityData($activityId,$parameterArray){
		return $this->restClient->post('process-instance/'.$activityId.'/variables', $parameterArray);
	}

	public function getActivityData($activityId,$parameterArray){
		return $this->restClient->post('process-instance/'.$activityId.'/variables', $parameterArray);
	}
}