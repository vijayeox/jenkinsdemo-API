<?php
namespace Oxzion;
use Job\Task;
use Thread;
use DateTime;
use Job\Job;
require __DIR__.'/../autoload.php';

class WorkflowTask extends Task{
	
	protected function executeTask(){
		error_log("Inside executeTask");
		error_log("=========================================================");
		//$start = new DateTime();
		error_log("Starting to perform Workflow for id - ".$this->params['id']);
		// try {
			$workFlow = new WorkflowService();
			// print_r('came here');
			$workFlow->execWorkflow($this->params,$this->id);
		// } catch(Exception $e){
		// 	error_log("Error while syncing");
		// }
		//$end = new DateTime();
		//$diff = $start->diff($end);
		//print("Time taken to Syn emails for userid - ".$this->params['userid']." : ".$diff->format("%H:%I:%S")."\n");
		// error_log("Completed  Workflow Execution for id - ".$this->params['id']);
		// error_log("=========================================================");
	}

	public function cleanup(){
	 	// $this->job = Job::getInstance();
		// error_log("Cleaning up Workflow Job with id - ".$this->params['id']);
		// $this->job->jobExecCompleted("workflow_".$params['id'], "WORKFLOW_JOB", "Workflow Successfully Executed", "Done!");
	}
}
?>