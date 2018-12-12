<?php
namespace Oxzion\Workflow\Camunda;

use Oxzion\Workflow\Activity;

class ActivityImpl implements Activity {
	private $restClient;

	public function __construct(){
		$this->restClient = new RestClient(Config::ENGINE_URL);
	}

	public function setRestClient($restClient){
		$this->restClient = $restClient;
	}

	public function getActivity($activityId){
		return $this->restClient->get("task/".$activityId);
	}

	public function getActivitiesByUser($userId){
		return $this->restClient->post('task', array("assignee"=>$userId));
	}

	public function claimActivity($activityId,$parameterArray){
		$query = 'task/'.$activityId.'/claim';
		return $this->restClient->post($query, $parameterArray);
	}
	public function unclaimActivity($activityId){
		$query = 'task/'.$activityId.'/unclaim';
		return $this->restClient->post($query);
	}
	public function completeActivity($activityId,$parameterArray){
		$query = 'task/'.$activityId.'/complete';
		return $this->restClient->post($query,$parameterArray);
	}
	
	public function resolveActivity($id,$parameterArray){
		$query = 'task/'.$id.'/resolve';
		return $this->restClient->post($query,$parameterArray);
	}

	public function getActivitiesByGroup($groupId){
		return $this->restClient->post('task', array("candidateGroup"=>$groupId));
	}

	public function saveActivityData($activityId,$parameterArray){
		return $this->restClient->post('process-instance/'.$activityId.'/variables', $parameterArray);
	}

	public function getActivityData($activityId,$parameterArray){
		return $this->restClient->post('process-instance/'.$activityId.'/variables', $parameterArray);
	}
}