<?php
namespace Oxzion;
use Job\Task;
use Oxzion\InstanceFormService;
use DateTime;
use Job\Job;

class InstanceFormRedTask extends Task{
	protected $job;

	protected function executeTask(){
	 	$this->job = Job::getInstance();
		error_log("Inside executeTask");
		error_log("=========================================================");
		//$start = new DateTime();
		error_log("Starting to perform red task check");
		$InstanceService = new InstanceFormService();
		$InstanceService->CheckAndSendReds();
		$InstanceService->CheckAndSendYellows();
		//$end = new DateTime();
		//$diff = $start->diff($end);
		//print("Time taken to Sync calendar for userid - ".$this->params['userid']." : ".$diff->format("%H:%I:%S")."\n");
		error_log("Completed red task check ");
		error_log("=========================================================");
	}

	public function cleanup(){
	 	$this->job = Job::getInstance();
		error_log("Cleaning up TASKSJOB TASK_SYNC ");
		$this->job->jobExecCompleted("TASKSJOB", "TASK_SYNC", "Reminders Have been Sent", "Done!");
	}
}
?>