<?php
namespace Job;
include __DIR__.'/../autoload.php';
use Thread;
use Pool;
use Auto\Autoloader;
date_default_timezone_set('UTC');
class JobRunner extends Thread implements JobRunnerNotifier{
	const LOCK_FILE='job_runner.lock';
	const JOB_WAIT_PERIOD = 1; //in minutes
	const JOB_HOUSE_KEEPING_PERIOD = 20; //inminutes

	private static $instance;
	private $job;
	private $lasthouseKeepingPerformed;
	private $lastExecPerformed;
	private $run;
	//This is required to work around a bug in classloader refer
	//https://github.com/composer/composer/issues/5482
	private static $autoloader; 
	public function __construct(){
		$this->job = Job::getInstance();
		$date = date("Y-m-d H:i:s");
		$this->job->setJobRunnerNotifier($this);
		$this->lasthouseKeepingPerformed = strtotime($date);
		$this->lastExecPerformed = null;
		static::$autoloader = require_once(__DIR__.'/../../../vendor/autoload.php');
		// We don't know how it works,Run the thread instance from 
		$this->run();
	}

	public static function getInstance(){
		if(!isset(static::$instance)){
			static::$instance = new JobRunner();
		}
		return static::$instance;
	}

	public function notifyJob($jobDetail){
		self::execJob($this->job, $jobDetail);	
		$this->synchronized(function($thread){
			$thread->notify();
		}, $this);
	}

	public function stop(){
		$this->run = false;
		$this->notifyJob();
	}

	public function run(){
		$this->run = true;
		while($this->run){
			$this->synchronized(function($thread){
				$this->runJobs();
				$date = date("Y-m-d H:i:s");
				if((strtotime($date) - $this->lasthouseKeepingPerformed) >= HOUSEKEEPING_TIME *60*1000){
					$this->$lasthouseKeepingPerformed = strtotime($date);	
					$this->performHouseKeeping();			
				}
				sleep(60);
			}, $this);
		}
	}
	
	public function performHouseKeeping(){
		$result = $this->job->getExpiredJobs();
		if($result){
			foreach ($result as $jobDetail) {
				try{
					$task = new $jobDetail['jobExecutor']($jobDetail['id'], $jobDetail['jobParams']);
					if($task instanceof Task){
						$task->cleanup();
					}else{
						error_log("Cleanup not performed! Executor provided is of invalid type should be of type Job\Task");
					}
					$this->job->completeExpiredJob($jobDetail['id']);
				}catch(Exception $e){
					error_log("Exception occurred while performing housekeeping for job id : ".$jobDetail['id']);
					error_log("Exception  : ".$e->getMessage());
				}
				
			}
		}
		
	}

	protected function runJobs(){
		if(file_exists(JobRunner::LOCK_FILE)){
			print "Job is already running so exiting\n";
			return; //Job is already running
		}
		if(is_writable(JobRunner::LOCK_FILE)){
			print "creating lock file\n";
			touch(JobRunner::LOCK_FILE);
		}
		try{
			$data = $this->job->getRunnableJobs(EMAIL_SYNC_PERIOD);
			if(!empty($data)){
				$this->processJobs($data);
				error_log("Completed running ".count($data)." Jobs");
			}else{
				error_log("No Jobs to run");
			}
		}catch(Exception $e){
			$currentdate = date("Y-m-d H:i:s");
			$this->lastExecPerformed =strtotime($date);
			error_log("Exception occurred while running jobs ");
			error_log("Exception  : ".$e->getMessage());
		}
		if(is_writable(JobRunner::LOCK_FILE)){
			unlink(JobRunner::LOCK_FILE);
		}
		
	}

	protected function processJobs($data){
		spl_autoload_register();
		$pool = new Pool(THREAD_LIMIT,Autoloader::class,[__DIR__.'/../../../vendor/autoload.php']);
		foreach ($data as $jobDetail) {
				$this->job->startJobById($jobDetail['id']);
				$pool->submit(new $jobDetail['jobExecutor']($jobDetail['id'], $jobDetail['jobParams']));
		}
		while ($pool->collect(function($work){
			return $work->cleanup();
		}));
		$pool->shutdown();
	}

	 static function execJob($job, $jobDetail){
		try{
			$job->startJobById($jobDetail['id']);
			$task = new $jobDetail['jobExecutor']($jobDetail['id'], $jobDetail['jobParams']);
			if($task instanceof Task){
				error_log("Found Task : now starting");
				$task->start();
				// $task->executeTask();
			}else{
				error_log("Executor provided is of invalid type should be of type Job\Task");
			}
		}catch(Exception $e){
			error_log("Exception occurred while running job for job id ".$jobDetail['id']);
			error_log("Exception  : ".$e->getMessage());	
		}
	}
	

}

?>