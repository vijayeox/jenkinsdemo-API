<?php
namespace Oxzion\Workflow;

interface Activity{

	public function getActivity($activityId);

	public function getActivitiesByUser($userId);

	public function claimActivity($activityId,$parameterArray);

	public function unclaimActivity($activityId);

	public function completeActivity($activityId,$parameterArray);

	public function resolveActivity($activityId,$parameterArray);
	
	public function getActivitiesByGroup($groupId);

	public function saveActivityData($activityId,$parameterArray);

	public function getActivityData($activityId,$parameterArray);

}