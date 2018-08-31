<?php
namespace Oxzion;

use Oxzion\Dao;

class EmailSyncJob{
	protected static $instance;
	const STATUS_RUNNING = 'RUNNING';
	const STATUS_COMPLETED = 'COMPLETED';
	private $dao;

	public static function getInstance(){
		if(!isset(static::$instance)){
			static::$instance = new EmailSyncJob();
		}
		return static::$instance;
	}
	private function __construct(){
		$this->dao = new Dao();
	}

	public function checkSyncJobInProgress($userid){
		$sql = "select count(id) cnt from email_job where userid = ".$userid." AND job_status = '".EmailSyncJob::STATUS_RUNNING."' AND is_sync_in_progress = true";
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		$row = $result->fetch_assoc();
		$val = $row['cnt'] > 0;
		$result->free();
		return $val;
	}

	public function jobHeartBeat($userid){
		$sql = "update email_job set heart_beat_time = now() where userid = ".$userid." AND job_status = '".EmailSyncJob::STATUS_RUNNING."'";
		$this->dao->execUpdate($sql);
	}

	public function addJob($userid){
		if($this->checkSyncJobInProgress($userid)){
			$this->jobHeartBeat($userid);
			return;
		}
		$sql = "select count(id) cnt from email_job where userid = ".$userid." AND job_status = '".EmailSyncJob::STATUS_RUNNING."' AND is_sync_in_progress = false";
		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
			return;
		}
		$row = $result->fetch_assoc();
		$val = $row['cnt'] > 0;
		$result->free();
		if($val) { //a RUNNING job exists for the userid just update the heart beat 
			$this->jobHeartBeat($userid);
			return;
		}

		//no running job so create a new job
		$sql = "insert into email_job (userid) VALUES ($userid)";
		$this->dao->execUpdate($sql);	 
	}

	public function startSync($userid){
		$sql = "update email_job set is_sync_in_progress = true, last_sync_start_time = now() WHERE userid = ".$userid." AND job_status = '".EmailSyncJob::STATUS_RUNNING."' AND is_sync_in_progress = false";
		$this->dao->execUpdate($sql);	 
	}

	public function syncCompleted($userid, $status, $details){
		print('Sync completed for user : '.$userid."\n");
		$sql = "select heart_beat_time from email_job WHERE userid = ".$userid." AND job_status = '".EmailSyncJob::STATUS_RUNNING."' AND is_sync_in_progress = true";
		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
			return;
		}
		$row = $result->fetch_assoc();
		$setClause = "";
		if(empty($row['heart_beat_time'])){
			$setClause = ", heart_beat_time = date_created";
		}
		$sql = "update email_job set is_sync_in_progress = false, last_sync_end_time = now(), last_sync_status = '".$status."', last_sync_details = '".$details."'$setClause WHERE userid = ".$userid." AND job_status = '".EmailSyncJob::STATUS_RUNNING."'";
		//print "$sql\n";
		$this->dao->execUpdate($sql);	 
	}

	public function getExpiredJobs(){
		$sql = "select userid from email_job where TIMESTAMPDIFF(MINUTE, heart_beat_time, NOW()) > ".EMAIL_SYNC_JOB_EXPIRY." AND job_status = '".EmailSyncJob::STATUS_RUNNING."'";
		//print "$sql\n";
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row['userid'];
		}

		$result->free();

		return $data;
	}

	public function completeExpiredJob($userid){
		$sql = "update email_job set job_status = '".EmailSyncJob::STATUS_COMPLETED."', date_completed = now() where userid = $userid";
		//print "$sql\n";
		$this->dao->execUpdate($sql);	 	
	}
	public function completeExpiredJobs(){
		$sql = "update email_job set job_status = '".EmailSyncJob::STATUS_COMPLETED."', date_completed = now() where TIMESTAMPDIFF(MINUTE, heart_beat_time, NOW()) > ".EMAIL_SYNC_JOB_EXPIRY." AND job_status = '".EmailSyncJob::STATUS_RUNNING."'";
		//print "$sql\n";
		$this->dao->execUpdate($sql);	 	
	}

	public function removeJob($userid){
		$sql = "update email_job set job_status = '".EmailSyncJob::STATUS_COMPLETED."', date_completed = now() WHERE userid = ".$userid." AND job_status = '".EmailSyncJob::STATUS_RUNNING."'";

		$this->dao->execUpdate($sql);
	}
	/**
	 *	@param minDelayTime - minimum time in minutes before which the job should not be run 
	 *
	 */
	public function getRunnableJobs($minDelayTime = EMAIL_SYNC_PERIOD){
		$sql = "select userid from email_job where job_status = '".EmailSyncJob::STATUS_RUNNING."' AND is_sync_in_progress = false AND (heart_beat_time is null OR (TIMESTAMPDIFF(MINUTE, heart_beat_time, NOW()) <= ".EMAIL_SYNC_JOB_EXPIRY." AND TIMESTAMPDIFF(MINUTE, last_sync_end_time, NOW()) >=".$minDelayTime."))";
		//print "$sql\n";
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = $row['userid'];
		}

		$result->free();

		return $data;

	}


}


?>