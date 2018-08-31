<?php
namespace Oxzion;

use Messaging\MessageProducer;
use DateTime;
use Cache\FileCache;

class EmailSyncJobRunner{
	const LOCK_FILE='email-sync.lock';
	private $job;
	private $producer;

	public function __construct(){	
		$this->job = EmailSyncJob::getInstance();
		$this->producer = MessageProducer::getInstance();
				
	}

	public function getJob(){
		return $this->job;
	}

	public function performHouseKeeping(){
		
		$result = $this->job->getExpiredJobs();
		if($result){
			foreach ($result as $userid) {
				try{
					EmailClientCache::removeEmailClientsForUser($userid);
					$this->job->completeExpiredJob($userid);
				}catch(Exception $e){
					error_log("Exception occurred while performing housekeeping for user : $userid");
					error_log("Exception  : ".$e->getMessage());
				}
				
			}
		}
		FileCache::deleteByTime();
	}

	public function runJobs(){
		if(file_exists(EmailSyncJobRunner::LOCK_FILE)){
			print "Job is already running so exiting\n";
			return; //Job is already running
		}
		if(is_writable(EmailSyncJobRunner::LOCK_FILE)){
			print "creating lock file\n";
			touch(EmailSyncJobRunner::LOCK_FILE);
		}
		$houseKeepingTime = new DateTime();
		try{
			$data = $this->job->getRunnableJobs(EMAIL_SYNC_PERIOD);
			if(!empty($data)){
				$this->processSync($data);
				error_log("Completed running ".count($data)." Jobs");
			}else{
				error_log("No Jobs to run");
			}
		}catch(Exception $e){
			error_log("Exception occurred while running jobs ");
			error_log("Exception  : ".$e->getMessage());
		}
		if(is_writable(EmailSyncJobRunner::LOCK_FILE)){
			unlink(EmailSyncJobRunner::LOCK_FILE);
		}
	}

	private function processSync($data){
		foreach ($data as $userid) {
			try{
				$this->job->startSync($userid);
				$this->producer->sendMessage(array('userid' => $userid), SYNC_EMAIL);
			}catch(Exception $e){
				error_log("Exception occurred while running job for user $userid ");
				error_log("Exception  : ".$e->getMessage());	
			}
		}
		
	}

}

?>