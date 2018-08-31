<?php
namespace Oxzion;
use Job\Task;
use Thread;
use Oxzion\BackgroundCalendarService;
use DateTime;
use Job\Job;

class CalendarSyncTask extends Task{
	protected $job;

	protected function executeTask(){
	 	$this->job = Job::getInstance();
		error_log("Inside executeTask");
		error_log("=========================================================");
		//$start = new DateTime();
		error_log("Starting to perform calendar sync task for ");
		$calendarService = BackgroundCalendarService::getInstance();
		$calendarService->sendReminder();
		//$end = new DateTime();
		//$diff = $start->diff($end);
		//print("Time taken to Sync calendar for userid - ".$this->params['userid']." : ".$diff->format("%H:%I:%S")."\n");
		error_log("Completed calendar sync task for userid - ".$this->params['userid']);
		error_log("=========================================================");
	}

	public function cleanup(){
	 	$this->job = Job::getInstance();
		error_log("Cleaning up Calendar client cache for userid - ".$this->params['userid']);
		$this->job->jobExecCompleted("CALENDARJOBS", "CALENDAR_SYNC", "Reminders Have been Sent", "Done!");
	}
}
?>