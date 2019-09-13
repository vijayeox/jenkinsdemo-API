<?php
namespace Oxzion\Workflow;

interface Activity{

	public function getActivity($activityId);

	public function getActivitiesByUser($userId,$params);

	public function claimActivity($activityId,$userId);

	public function unclaimActivity($activityId,$userId);

	public function completeActivity($activityId,$parameterArray);

	public function resolveActivity($activityId,$parameterArray);
	
	public function getActivitiesByGroup($groupId);

	public function submitTaskForm($activityId,$parameterArray);

	public function saveActivityData($activityId,$parameterArray);

	public function getActivityData($activityId,$parameterArray);

}