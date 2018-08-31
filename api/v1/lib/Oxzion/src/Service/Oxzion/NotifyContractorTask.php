<?php
namespace Oxzion;
use Job\Task;
use Thread;
use Oxzion\Backgroundnotifycontractor;
use DateTime;
use Job\Job;

class NotifyContractorTask extends Task{
	protected $job;

	protected function executeTask(){
	 	$this->job = Job::getInstance();
		error_log("Inside executeTask");
		error_log("=========================================================");
		error_log("Starting triggering Mails for non complaints");
		$notifycontractor = BackgroundCompliantMail::getInstance();
		$notifycontractor->sendReminderMail($this->params);
		error_log("Ending triggering Mails for non complaints");
		error_log("=========================================================");
	}

	public function cleanup(){
	 	$this->job = Job::getInstance();
		error_log("Cleaning up Mails for non complaints");
		$this->job->jobExecCompleted("EMAIL_TRIGGER_JOB", "EMAIL_TRIGGER_JOB", "Reminders Have been Sent", "Done!");
	}
}
?>