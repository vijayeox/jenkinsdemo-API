<?php
namespace Oxzion;
use Job\Task;
use Thread;
use DateTime;
use Job\Job;
require __DIR__.'/../autoload.php';

class EmailSyncTask extends Task{
	
	protected function executeTask(){
		error_log("Inside executeTask");
		error_log("=========================================================");
		//$start = new DateTime();
		error_log("Starting to perform email sync task for userid - ".$this->params['userid']);
		try {
			$emailService = BackgroundEmailService::getInstance();
			$emailService->syncEmailsForUser($this->params);
		} catch(Exception $e){
			error_log("Error while syncing");
		}
		//$end = new DateTime();
		//$diff = $start->diff($end);
		//print("Time taken to Syn emails for userid - ".$this->params['userid']." : ".$diff->format("%H:%I:%S")."\n");
		error_log("Completed email sync task for userid - ".$this->params['userid']);
		error_log("=========================================================");
	}

	public function cleanup(){
		error_log("Cleaning up email client cache for userid - ".$this->params['userid']);
		$email = NULL;
		if(isset($this->params['email'])){
			$mail = $this->params['email'];
		}
		EmailClientCache::removeEmailClientsForUser($this->params['userid'], $email);
	}
}
?>