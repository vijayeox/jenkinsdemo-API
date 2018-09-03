<?php
include __DIR__.'/Common/Config.php';
include __DIR__.'/autoload.php';
include __DIR__.'/../../vendor/autoload.php';
use Messaging\MessageConsumer;
use Messaging\MessageProducer;
use Oxzion\BackgroundEmailService;
use Oxzion\EmailSyncTask;
use Oxzion\CalendarSyncTask;
use Job\Task;
use Job\JobRunner;
use Job\Job;
use Oxzion\OxzionIndexer;

date_default_timezone_set('UTC');
$consumer = MessageConsumer::getInstance(True);
$emailService = BackgroundEmailService::getInstance();
$oxzionIndexer = OxzionIndexer::getInstance('');
$autoloader = require __DIR__.'/../../vendor/autoload.php';

function syncmails($msg){
	$start = new DateTime();
	error_log("=========================================================");
	error_log(" In Queue - ".SYNC_EMAIL);
	error_log(" [x] Received  - ".$msg->body);
	$count = 1;
	while ($count <= 5 ) {
		try{
			$doc = json_decode($msg->body, true);
			$task = new EmailSyncTask($autoloader, 0, $doc);
			$task->start();
			$count = 5;
		}catch(Exception $e){
			error_log("Exception occurred while processing ".SYNC_EMAIL);
			error_log("Message content - ".$msg->body);
			error_log("Exception  : ".$e->getMessage());
		}
		$count++;
	}
	$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
	$end = new DateTime();
	$diff = $start->diff($end);
	error_log("Time taken to process ".SYNC_EMAIL." : ".$diff->format("%H:%I:%S"));
	error_log("=========================================================");
}

$consumer->consumeMessage(SYNC_EMAIL, 'syncmails');


$consumer->consumeMessage(LOGIN_EMAILS, function($msg) use(&$emailService){
	$start = new DateTime();
	error_log("=========================================================");
	error_log(" In Queue - ".LOGIN_EMAILS);
	error_log(" [x] Received  - ".$msg->body);
	$count = 1;
	while ($count <= 5 ) {
		try{
			$doc = json_decode($msg->body, true);
			addEmailSyncJob($doc);
			$count = 5;
		}catch(Exception $e){
			error_log("Exception occurred while processing ".LOGIN_EMAILS);
			error_log("Message content - ".$msg->body);
			error_log("Exception  : ".$e->getMessage());
		}
		$count++;
		
	}
	$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
	
	$end = new DateTime();
	$diff = $start->diff($end);
	error_log("Time taken to process ".LOGIN_EMAILS." : ".$diff->format("%H:%I:%S"));
	error_log("=========================================================");
});
function addEmailSyncJob($userid, $email = NULL){
	$job = Job::getInstance();
	$producer = MessageProducer::getInstance();	
	$jobParams = array('userid' => $userid);
	if(!is_null($email)){
		$jobParams['email'] = $email;
	}
	$job->addJob($userid['userid'], 'EMAIL_SYNC_JOB', "\Oxzion\EmailSyncTask", $userid, 5, 5, false);
}
$consumer->consumeMessage(LOGOUT_EMAILS, function($msg) use(&$emailService){
	$start = new DateTime();
	error_log("=========================================================");
	error_log(" In Queue - ".LOGOUT_EMAILS);
	error_log(" [x] Received  - ".$msg->body);
	$count = 1;
	while ($count <= 5 ) {
		
		try{
			$doc = json_decode($msg->body, true);
			$emailService->signoutEmailsForUser($doc);
			$count = 5;
		}catch(Exception $e){
			error_log("Exception occurred while processing ".LOGOUT_EMAILS);
			error_log("Message content - ".$msg->body);
			error_log("Exception  : ".$e->getMessage());
		}
		$count++;
		
	}
	$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
	$end = new DateTime();
	$diff = $start->diff($end);
	error_log("Time taken to process ".LOGOUT_EMAILS." : ".$diff->format("%H:%I:%S"));
	error_log("=========================================================");
});
$consumer->consumeMessage(INBOX_STATUS, function($msg) use(&$emailService){
	$start = new DateTime();
	error_log("=========================================================");
	error_log(" In Queue - ".INBOX_STATUS);
	error_log(" [x] Received  - ".$msg->body);
	$count = 1;
	while ($count <= 5 ) {
		try{
			$doc = json_decode($msg->body, true);
			$emailService->getInboxStatus($doc);
			$count = 5;
		}catch(Exception $e){
			error_log("Exception occurred while processing ".INBOX_STATUS);
			error_log("Message content - ".$msg->body);
			error_log("Exception  : ".$e->getMessage());
		}
		$count++;
		
	}
	$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
	$end = new DateTime();
	$diff = $start->diff($end);
	error_log("Time taken to process ".INBOX_STATUS." : ".$diff->format("%H:%I:%S"));
	error_log("=========================================================");
});
$consumer->consumeMessage(BATCH_INDEX_QUEUE, function($msg) use(&$oxzionIndexer){
	$start = new DateTime();
	error_log("=========================================================");
	error_log(" In Queue - ".BATCH_INDEX_QUEUE);
	error_log(" [x] Received - ".$msg->body);
	try{
		$doc = json_decode($msg->body, true);
		$oxzionIndexer->batchIndex($doc);
		$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

	}catch(Exception $e){
		error_log("Exception occurred while processing ".BATCH_INDEX_QUEUE);
		error_log("Message content - ".$msg->body);
		error_log("Exception  : ".$e->getMessage());
	}
	$end = new DateTime();
	$diff = $start->diff($end);
	error_log("Time taken to process ".BATCH_INDEX_QUEUE." : ".$diff->format("%H:%I:%S"));
	error_log("=========================================================");
});

// $consumer->consumeMessage(INDEX_DOCUMENT_QUEUE, function($msg) use(&$oxzionIndexer){
// 	$start = new DateTime();
// 	error_log("=========================================================");
// 	error_log(" In Queue - ".INDEX_DOCUMENT_QUEUE);
// 	error_log(" [x] Received  - ".$msg->body);
// 	try{
// 		$doc = json_decode($msg->body, true);
// 		$oxzionIndexer->index($doc);
// 		$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
// 	}catch(Exception $e){
// 		error_log("Exception occurred while processing ".INDEX_DOCUMENT_QUEUE);
// 		error_log("Message content - ".$msg->body);
// 		error_log("Exception  : ".$e->getMessage());
// 	}
// 	$end = new DateTime();
// 	$diff = $start->diff($end);
// 	error_log("Time taken to process ".INDEX_DOCUMENT_QUEUE." : ".$diff->format("%H:%I:%S"));
// 	error_log("=========================================================");
// });

// $consumer->consumeMessage(DELETE_DOCUMENT_QUEUE, function($msg) use(&$oxzionIndexer){
// 	$start = new DateTime();
// 	error_log("=========================================================");
// 	error_log(" In Queue - ".DELETE_DOCUMENT_QUEUE);
// 	error_log(" [x] Received  - ".$msg->body);
// 	try{
// 		$doc = json_decode($msg->body, true);
// 		$oxzionIndexer->delete($doc);
// 		$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
// 	}catch(Exception $e){
// 		error_log("Exception occurred while processing ".DELETE_DOCUMENT_QUEUE);
// 		error_log("Message content - ".$msg->body);
// 		error_log("Exception  : ".$e->getMessage());
// 	}
// 	$end = new DateTime();
// 	$diff = $start->diff($end);
// 	error_log("Time taken to process ".DELETE_DOCUMENT_QUEUE." : ".$diff->format("%H:%I:%S"));
// 	error_log("=========================================================");
// });
$consumer->consumeMessage('NOTIFY_JOB', function($msg) {
	$start = new DateTime();
	$job = Job::getInstance();
	error_log("=========================================================");
	error_log(" In Queue - "."NOTIFY_JOB");
	error_log(" [x] Received  - ".$msg->body);
	try{
		$doc = json_decode($msg->body, true);
		if($doc['jobTracker']&&$doc['jobType']){
		$jobdetails = $job->getJob($doc['jobTracker'], $doc['jobType']);
		try{
			$job->startJobById($jobdetails['id']);
			$task = new $jobdetails['jobExecutor']($jobdetails['id'], $jobdetails['jobParams']);
			if($task instanceof Task){
				error_log("Found Task : now starting");
				// $task->start();
				$task->executeTask();
			}else{
				error_log("Executor provided is of invalid type should be of type Job\Task");
			}
		}catch(Exception $e){
			error_log("Exception occurred while running job for job id ".$jobDetail['id']);
			error_log("Exception  : ".$e->getMessage());	
		}	
	}
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
$consumer->wait();
?>