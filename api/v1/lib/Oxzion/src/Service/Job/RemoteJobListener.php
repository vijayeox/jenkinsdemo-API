<?php
namespace Job;
use Messaging\MessageConsumer;
use DateTime;

class RemoteJobListener {

	private $job;
	private $consumer;
	public function __construct($job){
		$this->job = $job;
		$this->consumer = MessageConsumer::getInstance(True);
	}
	
	public function startListener(){
		error_log("Starting RemoteJob Listener for "."NOTIFY_JOB");
		
		$job = $this->job;
	
		$this->consumer->consumeMessage("NOTIFY_JOB", function($msg) use(&$job){
			$start = new DateTime();
			error_log("=========================================================");
			error_log(" In Queue - "."NOTIFY_JOB");
			error_log(" [x] Received  - ".$msg->body);
			try{
				$doc = json_decode($msg->body, true);
				$job->notifyJob($doc['jobTracker'], $doc['jobType']);
			}catch(Exception $e){
				error_log("Exception occurred while processing "."NOTIFY_JOB");
				error_log("Message content - ".$msg->body);
				error_log("Exception  : ".$e->getMessage());
			}
					
			$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
				
			$end = new DateTime();
			$diff = $start->diff($end);
			error_log("Time taken to process "."NOTIFY_JOB"." : ".$diff->format("%H:%I:%S"));
			error_log("=========================================================");
		});
	}
}
?>