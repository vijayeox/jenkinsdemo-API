<?php
namespace Oxzion;
use Job\Task;
use Thread;
use Oxzion\BackgroundSliderService;
use DateTime;
use Job\Job;

class SliderSyncTask extends Task{
	protected $job;

	protected function executeTask(){
	 	$this->job = Job::getInstance();
		error_log("Inside executeTask");
		error_log("=========================================================");
		error_log("Starting to perform social sync task");
		$socialService = BackgroundSliderService::getInstance();
		$socialService->sync();
		error_log("Completed social sync task ");
		error_log("=========================================================");
	}

	public function cleanup(){
	 	$this->job = Job::getInstance();
		error_log("Cleaning up Social Sync client");
		$this->job->jobExecCompleted("SOCIALSYNCJOBS", "SOCIAL_SYNC", "Sliders Have been Created", "Done!");
	}
}
?>