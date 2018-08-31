<?php
	namespace Oxzion;
	require_once __DIR__.'/../Common/Config.php';
	include __DIR__.'/../autoload.php';
	include __DIR__.'/../../../vendor/autoload.php';
	use Email\EmailClient;
	use ElephantIO\Client;
	use ElephantIO\Engine\SocketIO\Version1X;
	use DateTime;
	use Exception;
	use Job\Job;
	
	class BackgroundEmailService {
	 	private static $instance;
		private $dao;
	 	private $job;
	 	private $oAuthService;

	 	private function __construct(){
	 		$this->dao = new Dao();
	 		$this->job = Job::getInstance();
	 		$this->oAuthService = new OAuthService();
	 	}
		
		public static function getInstance(){
			if(!isset(static::$instance)){
				static::$instance = new BackgroundEmailService();
			}
			return static::$instance;
		}
		private function setupEmailClient($row){
			$password = $row['password'];
			if(!isset($password) && !strlen($password)){
				$password = "dummyPasswordToPreventHordeClientThrowException";
			}
			$email = $row['email'];
			$authToken = '';

			if(isset($row['oauth_provider'])){
				$credentials = $this->oAuthService->getCredentials($row['userid'], $email, $row['oauth_provider']);
				if(isset($credentials)){
					$authToken = $credentials['access_token'];
				}
				
			}
			return EmailClientCache::setupEmailClient($row['userid'], $email, $row['username'], $password, $row['host'], $row['port'], $row['secure'], $authToken);
		}

		public function signinEmailsForUser($data){
			$userid = $data['userid'];

	 		$sql = "select * from email_setting WHERE userid = ".$userid;
	 		if(isset($data['email'])){
	 			$sql = $sql." and email = '".$data['email']."'";
	 		}
	 		if(!$result = $this->dao->execQuery($sql)){ 
				return;
			}
			
			while ($row = $result->fetch_assoc()) {
				$emailClient = $this->setupEmailClient($row);
			}
			
	 	}

	 	public function signoutEmailsForUser($data){
	 		$email = NULL;
			$userid = $data['userid'];
			if(isset($data['email'])){
				$email = $data['email'];
			}
	 		EmailClientCache::removeEmailClientsForUser($userid, $email);
			
	 	}

	 	public function getInboxStatus($data){
	 		$userid = $data['userid'];
	 		$sql = "select * from email_setting WHERE userid = ".$userid;
	 		if(isset($data['email'])){
	 			$sql = $sql." and email = '".$data['email']."'";
	 		}
	 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
				return;
			}

			while ($row = $result->fetch_assoc()) {
				try{
		 			$emailClient = EmailClientCache::getEmailClient($row['userid'], $row['email']);
		 			if(!$emailClient){
		 				$emailClient = $this->setupEmailClient($row);
		 			}
		 			$emailClient->openMailbox('INBOX');
		 			$status = $emailClient->getMailBoxStatus(array('INBOX'));
		 		}catch(Exception $e){
		 			error_log("BackgroundEmailService->syncEmailsForUser : Encountered Exception =>".$e->getMessage());
		 			
		 		}
			}
	 	}
	 	public function syncEmailsForUser($data){
	 		if($data['userid']){
	 			$userid = $data['userid'];
	 		} else {
	 			echo "error with parameter";
	 			return;
	 		}

	 		$sql = "select * from email_setting WHERE userid = ".$userid;
	 		if(isset($data['email'])){
	 			$sql = $sql." and email = '".$data['email']."'";
	 		}

	 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
	 			$this->job->jobExecCompleted($userid, "EMAIL_SYNC_JOB", "No email Accounts to Sync", "Done!");
				return;
			}
			$status = "";
			$details = array();
			$total = 0;
			$failures = 0;
			while ($row = $result->fetch_assoc()) {
				$start = new DateTime();
	 			$total++;
		 		$result;		
	 			try{
		 			$emailClient = EmailClientCache::getEmailClient($row['userid'], $row['email']);

		 			if(!$emailClient){
		 				$emailClient = $this->setupEmailClient($row);
		 			}
		 			$folders = $emailClient->listMailBoxes('*');
		 			$isFirstSync = $this->isFirstSync($userid, $row['email']);
		 			$this->setSyncDetails($row['userid'], $row['email'], "In Progress...", 0, $start, $folders);
		 			$monthSince = $row['month_since'] > 0 ? $row['month_since'] : EMAIL_SYNC_FOR_LAST_MONTHS;
		 			$syncResult = $emailClient->syncAllImapFoldersAndContacts(true, $folders, $monthSince);
		 			$end = new DateTime();
		 			$diff = $start->diff($end);
		 			$this->setSyncDetails($row['userid'], $row['email'], "Sync Completed Successfully", $diff->s);
		 			
		 			if(!$isFirstSync && $syncResult['unseen'] > 0){
		 				print "Sending message to ui\n";
	 					$this->sendMessageToUI($userid, $row['email'], $syncResult['unseen']);
	 				}
	 			}catch(Exception $e){
	 				$end = new DateTime();
		 			$diff = $start->diff($end);
		 			$failures++;
		 			$details[$row['email']] = $e->getMessage();
		 			error_log("BackgroundEmailService->syncEmailsForUser : Encountered Exception =>".$e->getMessage());
		 			$this->setSyncDetails($row['userid'], $row['email'], "Sync Failed : ".$e->getMessage(), $diff->s);
	 			} finally {
	 				$content = "";
	 				if($failures > 0){
	 					$status = "Sync Failed : $failures failed out of $total emails";
	 					$content = json_encode($details);
	 					$content = str_replace("\\", "\\\\", $content);
	 					$content = str_replace("\"", "\\\"", $content);
	 					$content = str_replace("'", "''", $content);
	 				}else{
	 					$status = "Sync Successful : $total email(s)";
	 				}
	 				$this->job->jobExecCompleted($userid, EMAIL_SYNC_JOB, $status, $content);
	 			}
	 		}
	 	}

	 	private function isFirstSync($userid, $email){
	 		$sql = "select count(id) as sync from email_setting where userid = $userid AND email = '".$email."' and last_sync_time is null";
	 		if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			$row = $result->fetch_assoc();

			return $row['sync'] != 0;
	 	}

	 	private function setSyncDetails($userid, $email, $syncDetails, $duration, $start = null, $folders = null){
	 		$sql = "update email_setting set last_sync_status = '".$syncDetails."', last_sync_duration = ".$duration;
	 		if($start){
	 			$sql = "$sql, last_sync_time = '".$start->format('Y-m-d H:i:s')."'";
	 		}

	 		if($folders){
	 			$content = json_encode($folders);
				$content = str_replace("\\", "\\\\", $content);
				$content = str_replace("\"", "\\\"", $content);
				$content = str_replace("'", "''", $content);
	 			$sql = "$sql, folders = '".$content."'";
	 		}								 
	 		$sql = "$sql WHERE userid = ".$userid." AND email = '".$email."'";
	 		//print "sql - $sql\n";
	 		return $this->dao->execUpdate($sql);
	 	}
	 	private function loadEmailConfig($row, &$imapConfig, &$smtpConfig){
	 		$imapConfig['username'] == $row['username'];
 			$imapConfig['password'] == $row['password'];
 			$imapConfig['host'] == $row['host'];
 			$imapConfig['port'] == $row['port'];
 			$imapConfig['secure'] == $row['secure'];

 			$smtpConfig['username'] == $row['smtp_username'];
 			$smtpConfig['password'] == $row['smtp_password'];
 			$smtpConfig['host'] == $row['smtp_host'];
 			$smtpConfig['port'] == $row['smtp_port'];
 			$smtpConfig['secure'] == $row['smtp_secure'];
	 	}

	 	public function sendMessageToUI($userid,$email,$count) {
	 		try{
	 			$client = new Client(new Version1X(NODEJS_URL, ['context' => ['ssl' => ['verify_peer_name' =>false, 'verify_peer' => false]]]));
	 			$client->initialize();
	 			$client->emit('newemails', array('userid'=>$userid,'email'=>$email,'count'=>$count));
	 			$client->close();
	 		} catch(Exception $e) {
	 			return;
	 		}
	 	}

	}
?>