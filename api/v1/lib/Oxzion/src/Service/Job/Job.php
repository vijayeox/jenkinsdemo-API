<?php
namespace Job;
use Common\Config;
use Common\Dao;
use Messaging\MessageProducer;
use mysqli;

date_default_timezone_set('UTC');
class Job{
	private static $instance;
	const STATUS_RUNNING = 'RUNNING';
	const STATUS_COMPLETED = 'COMPLETED';
	private $jobRunnerNotifier;
	private $producer;
	private $remoteJobListener;

	public static function getInstance(){
		if(!isset(static::$instance)){
			static::$instance = new Job();
		}
		return static::$instance;
	}
	private function __construct(){
	}

	public function setJobRunnerNotifier(JobRunnerNotifier $jobRunnerNotifier){
		$this->jobRunnerNotifier = $jobRunnerNotifier;
		$this->remoteJobListener = new RemoteJobListener($this);
		$this->remoteJobListener->startListener();
	}

	public function checkJobInProgress($jobTracker, $jobType){
		$sql = "select count(id) cnt from job where job_tracker = '".$jobTracker."' AND job_status = '".Job::STATUS_RUNNING."' AND job_type = '$jobType' AND is_job_in_progress = true";
		$dao = new Dao();

		if(!$result = $dao->execQuery($sql)){
			$dao->close();
			return;
		}
		try{
			$row = $result->fetch_assoc();
			$val = $row['cnt'] > 0;
		}finally{
			$result->free();
			$dao->close();
		}
		return $val;
	}

	/*
	 * $jobTracker(string)  	- User specified unique tracking information to check job 
	 *								progress or remove the job
	 * $jobType(string) 		- A user specified job Type to differentiate different job 
	 *								types for a given jobTracker
	 * $jobExecutor(string)  	- The name of the class that implements the Job\Task class
	 * $jobParams(mixed)		- The parameters to be pased to the executor for running the 
	 *								job.
	 * $jobFreqInMin(int)		- Default 0. The periodicity in minutes with which the job 
	 *								should be executed
	 * $maxRuns(int) 			- Default 1. Value of -1 means just keep running with the 
	 *								specified frequency until job is removed or if non zero 
	 *								run that many times
	 * $startJob(Boolean)		- Default True. When true starts the Job immediately. When 
	 *								False the client should call startJob method explicitly 
	 *								to start the job
	 */
	public function addJob($jobTracker, $jobType, $jobExecutor, $jobParams, $jobFreqInMin = 0, $maxRuns = 1, $startJob = True){
		if($this->checkJobInProgress($jobTracker, $jobType)){
			return;
		}
		if(is_array($jobParams)){
			$jobParams = json_encode($jobParams);
		}
		$dao = new Dao();
		try{
			$jobTracker = $dao->escapeString($jobTracker);
			$jobType = $dao->escapeString($jobType);
			$jobExecutor = $dao->escapeString($jobExecutor);
			$jobParams = $dao->escapeString($jobParams);
			//no running job so create a new job
			$sql = "insert into job (job_tracker, job_type, job_executor, job_params, job_frequency_minutes, max_runs) VALUES ('$jobTracker', '$jobType', '$jobExecutor', '$jobParams', $jobFreqInMin, $maxRuns)";
			$dao->execUpdate($sql);
		}finally{
			$dao->close();
		}
		if($startJob){
			$this->startJob($jobTracker, $jobType);
		}
		$this->notifyJob($jobTracker, $jobType);

	}

	public function notifyJob($jobTracker, $jobType){
		if($this->jobRunnerNotifier){
			$jobDetail = $this->getJob($jobTracker, $jobType);
			$this->jobRunnerNotifier->notifyJob($jobDetail);
		}else{
			if(!isset($this->producer)){
				$this->producer = MessageProducer::getInstance();
			}
			
			$this->producer->sendMessage(array('jobTracker' => $jobTracker, 'jobType' => $jobType), 'NOTIFY_JOB');
		
		}
	}
	public function startJobById($id){
		$sql = "update job set is_job_in_progress = true, last_exec_start_time = now() WHERE id = ".$id." AND job_status = '".Job::STATUS_RUNNING."' AND is_job_in_progress = false";
		$dao = new Dao();
		try{
			$dao->execUpdate($sql);	 
		}finally{
			$dao->close();
		}
	}

	public function startJob($jobTracker, $jobType){
		$sql = "update job set is_job_in_progress = true, last_exec_start_time = now() WHERE job_tracker = ".$jobTracker." AND job_status = '".Job::STATUS_RUNNING."' AND is_job_in_progress = false AND job_type = '$jobType'";
		$dao = new Dao();
		try{	
			$dao->execUpdate($sql);
		}finally{
			$dao->close();
		}
	}

	public function jobExecCompleted($jobTracker, $jobType, $status, $details){
		print('Job Execution completed for job with tracker : '.$jobTracker." type - $jobType\n");
		
		$sql = "update job set is_job_in_progress = false, last_exec_end_time = now(), last_exec_status = '".$status."', last_exec_details = '".$details."', num_of_runs = num_of_runs + 1 WHERE job_tracker = '".$jobTracker."' AND job_type = '$jobType' AND job_status = '".Job::STATUS_RUNNING."'";
		//print "$sql\n";
		$dao = new Dao();
		try{
			$dao->execUpdate($sql);	 
		}finally{
			$dao->close();
		}
	}

	public function getExpiredJobs(){
		$sql = "select id, job_executor, job_params from job where num_of_runs = max_runs AND job_status = '".Job::STATUS_RUNNING."'";
		//print "$sql\n";
		$dao = new Dao();
		if(!$result = $dao->execQuery($sql)){
			$dao->close();
			return;
		}
		$data = array();
		try{
			while ($row = $result->fetch_assoc()) {
				$data[] = array('id' => $row['id'],
								'jobExecutor' => $row['job_executor'],
								'jobParams' => $row['job_params']);

			}
		}finally{
			$result->free();
			$dao->close();
		}
		return $data;
	}

	public function completeExpiredJob($id){
		$sql = "update job set job_status = '".Job::STATUS_COMPLETED."', date_completed = now() where id = $id";
		//print "$sql\n";
		$dao = new Dao();
		try{
			$dao->execUpdate($sql);	 	
		}finally{
			$dao->close();
		}
	}
	public function completeExpiredJobs(){
		$sql = "update job set job_status = '".Job::STATUS_COMPLETED."', date_completed = now() where num_of_runs = max_runs AND job_status = '".Job::STATUS_RUNNING."'";
		//print "$sql\n";
		$dao = new Dao();
		try{
			$dao->execUpdate($sql);	 	
		}finally{
			$dao->close();
		}
	}

	public function removeJob($jobTracker, $jobType){
		$sql = "update job set job_status = '".Job::STATUS_COMPLETED."', date_completed = now() WHERE job_tracker = ".$jobTracker." AND job_status = '".Job::STATUS_RUNNING."' AND job_type = '$jobType'";
		$dao = new Dao();
		try{
			$dao->execUpdate($sql);
		}finally{
			$dao->close();
		}
	}

	public function getJob($jobTracker, $jobType){
		$sql = "select id, job_tracker, job_type, job_executor, job_params from job where job_tracker = '$jobTracker' and job_type = '$jobType'";
		$dao = new Dao();
		if(!$result = $dao->execQuery($sql)){
			$dao->close();
			return;
		}
		$row = $result->fetch_assoc();
		$data = array('id' => $row['id'],
							'jobTracker' => $row['job_tracker'],
							'jobType' => $row['job_type'],
							'jobExecutor' => $row['job_executor'],
							'jobParams' => $row['job_params']);
		$result->free();
		$dao->close();
		return $data;
		
	}
	/**
	 *	@param minDelayTime - minimum time in minutes before which the job should not be run 
	 *
	 */
	public function getRunnableJobs($minDelayTime = EMAIL_SYNC_PERIOD){
	 		$now = strtotime("today");
	 		$year = date("Y", $now);
	 		$month = date("m", $now);
	 		$day = date("d", $now);
	 		$nowString = $year . "-" . $month . "-" . $day;
	 		$week = (int) ((date('d', $now) - 1) / 7) + 1;
	 		$weekday = date("N", $now);
		$sql = "select job.id, job.job_tracker, job.job_type, job.job_executor, job.job_params from job LEFT JOIN or_meta EM1 ON EM1.eventid = job.id WHERE (DATE(date_created) = CURDATE() AND EM1.eventtype='calendar' AND ( DATEDIFF( '$nowString', repeat_start ) % repeat_interval = 0 ) OR ((repeat_year = $year OR repeat_year = '*' ) AND (repeat_month = $month OR repeat_month = '*' ) AND (repeat_day = $day OR repeat_day = '*' ) AND (repeat_week = $week OR repeat_week = '*' ) AND (repeat_weekday = $weekday OR repeat_weekday = '*' ) AND repeat_start >= DATE('$nowString')) AND (TIMESTAMPDIFF(MINUTE,NOW(),date_created) = 5 OR TIMESTAMPDIFF(MINUTE,NOW(),date_created) = 10  OR TIMESTAMPDIFF(MINUTE,NOW(),date_created) = 15  OR (TIMESTAMPDIFF(MINUTE,NOW(),date_created) < 5 AND TIMESTAMPDIFF(MINUTE,NOW(),date_created) > 0) AND TIMESTAMPDIFF(MINUTE,NOW(),date_created) > 0) AND EM1.eventtype='job') OR ( job_status = '".Job::STATUS_RUNNING."' AND is_job_in_progress = false AND (max_runs = -1 OR num_of_runs < max_runs) AND (last_exec_start_time is null OR (job_frequency_minutes > 0 AND TIMESTAMPDIFF(MINUTE, last_exec_end_time, NOW()) >= job_frequency_minutes)) )";
		$dao = new Dao();
		if(!$result = $dao->execQuery($sql)){
			$dao->close();
			return;
		}
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$data[] = array('id' => $row['id'],
							'jobTracker' => $row['job_tracker'],
							'jobType' => $row['job_type'],
							'jobExecutor' => $row['job_executor'],
							'jobParams' => $row['job_params']);
		}

		$result->free();
		$dao->close();
		return $data;

	}


}


?>