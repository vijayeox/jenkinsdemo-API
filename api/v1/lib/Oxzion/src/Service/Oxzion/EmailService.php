<?php
	namespace Oxzion;
	require_once __DIR__.'/Common/Config.php';
	use Email\EmailConfig;
	use Email\EmailClient;
	use DateTime;

	class EmailService{
	 	private $dao;
	 	
	 	public function __construct(){
	 		$this->dao = new Dao();
	 	}

	 	/**
	 	 *  $userid				oxzion userid
	 	 *  $folder    			folder to get the list of mails from
	 	 *  $pageNo 			The page number to fetch
	 	 *  $pageSize   		The number of records to fetch
	 	 *  $sortOptions: (array) Sort the returned list of messages. Multiple sort
	     *           criteria can be specified. Any sort criteria can be sorted in
	     *           reverse order (instead of the default ascending order) by
	     *           adding a EmailConfig::SORT_REVERSE element to the array
	     *           directly before adding the sort element. The following sort
	     *           criteria are available:
	     *     - EmailConfig::SORT_CC
	     *     - EmailConfig::SORT_DATE
	     *     - EmailConfig::SORT_FROM
	     *     - EmailConfig::SORT_SUBJECT
	     *     - EmailConfig::SORT_TO  
	 	 */
	 	public function getMailList($userid, $folder, $pageNo = 1, $pageSize = 100, $sortOptions = array(EmailConfig::SORT_REVERSE, EmailConfig::SORT_DATE)){
	 		$clause = "ec.userid=".$userid." AND ec.folder='".$folder."'";
			$order = "ORDER BY";
			$reverse = "";
			foreach ($sortOptions as $key => $value) {
				$order = $order != "ORDER BY" ? "$order," : $order;
				switch ($value) {
					case EmailConfig::SORT_REVERSE:
						$reverse = "DESC";
						break;
					case EmailConfig::SORT_DATE:
						$order = $order." ec.datetime $reverse";
						$reverse = "";
						break;
					case EmailConfig::SORT_CC:
						$order = $order." ec.cc $reverse";
						$reverse = "";
						break;
					case EmailConfig::SORT_FROM:
						$order = $order." ec._from $reverse";
						$reverse = "";
						break;
					case EmailConfig::SORT_TO:
						$order = $order." ec._to $reverse";
						$reverse = "";
						break;
					case EmailConfig::SORT_SUBJECT:
						$order = $order." ec._subject $reverse";
						$reverse = "";
						break;
				}
			}
			$pageNo = ($pageNo-1)*$pageSize;
			$limit = "LIMIT ".$pageNo.",".$pageSize;
			$sql = "select ec.envelope from email_cache ec where $clause $order $limit";
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			
			$data = array();
			while ($row = $result->fetch_assoc()) {
				array_push($data, json_decode($row['envelope'], true));
			}
			$result->free();
			
			return $data;
	 	}

	 	public function getContacts($userid, $q){
	 		$sql = "select name, email from user_contact where userid=".$userid." AND name like '%".$q."%' and email like '%".$q."%'";
	 		if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			$data = array();
	 		while ($row = $result->fetch_assoc()) {
	 			$data[] = array("name" => $row['name'],
	 							"email" => $row['email']);

	 		}

	 		$result->free();
	 		return $data;
	 	}

	 	/**
	 	 *  @param userid 		- userid in the Oxzion system
	 	 *  @param email 		- The email address to add for the user
	 	 *  @param imapConfig 	- The imap settings object containing following
	 	 *     - host: [*] (string) IMAP server host.
	 	 *	   - username: (string) username to use for IMAP server authentication
		 *     - password: (string) Password to use for IMAP server authentication.
		 *     - port: [*] (integer) IMAP server port.
		 *     - secure: [*] (string) Use SSL or TLS to connect.
		 *               Possible options:
		 *                 - false (No encryption)
		 *                 - 'ssl' (Auto-detect SSL version)
		 *                 - 'sslv2' (Force SSL version 2)
		 *                 - 'sslv3' (Force SSL version 3)
		 *                 - 'tls'  (TLS; started via protocol-level negotation over
       	 *     						unencrypted channel; RECOMMENDED way of initiating secure
      	 *     						connection)
		 *                 - 'tlsv1' (TLS direct version 1.x connection to server)
		 *                 - true (Use TLS, if available)  
	 	 *  @param smtpConfig 	- The smtp settings object containting following
	 	 *     - host: [*] (string) SMTP server host.
	 	 *	   - username: optional if not provided will default to email
		 *     - password: (string) Password to use for SMTP server authentication.
		 *     - port: [*] (integer) SMTP server port.
		 *     - secure: [*] (string) Use SSL or TLS to connect.
		 *               Possible options:
		 *                 - false (No encryption)
		 *                 - 'ssl' (Auto-detect SSL version)
		 *                 - 'sslv2' (Force SSL version 2)
		 *                 - 'sslv3' (Force SSL version 3)
		 *                 - 'tls' (TLS) [DEFAULT]
		 *                 - 'tlsv1' (TLS direct version 1.x connection to server)
		 *                 - true (Use TLS, if available)  
	 	 */
	 	public function updateEmailSettingForUser($userid, $email, $imapConfig, $smtpConfig){
	 		$sql = "select count(id) cnt from email_setting where userid = ".$userid." AND email = '".$email."'";
	 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
				return false;
			}
	 		$row = $result->fetch_assoc();
			$val = $row['cnt'] > 0;
			$result->free();
			if($val) { //entry found in the table so do update
				$sql = "update email_setting set username= '".$imapConfig['username']."',
												 password= '".$imapConfig['password']."',
												 host= '".$imapConfig['host']."',
												 port= '".$imapConfig['port']."',
												 secure= '".$imapConfig['secure']."',
												 smtp_username= '".$smtpConfig['username']."',
												 smtp_password= '".$smtpConfig['password']."',
												 smtp_host= '".$smtpConfig['host']."',
												 smtp_port= '".$smtpConfig['port']."',
												 smtp_secure= '".$smtpConfig['secure']."'
						WHERE userid = ".$userid." AND email = '".$email."'";
			}else{//INSERT
				$sql = "INSERT INTO email_setting (userid, email, username, password, host, port, secure, smtp_username, smtp_password, smtp_host, smtp_port, smtp_secure) VALUES (".$userid.",'".$email."','".$imapConfig['username']."','".$imapConfig['password']."','".$imapConfig['host']."','".$imapConfig['port']."','".$imapConfig['secure']."','".$smtpConfig['username']."','".$smtpConfig['password']."','".$smtpConfig['host']."','".$smtpConfig['port']."','".$smtpConfig['secure']."')";
			}	

			return $this->dao->execUpdate($sql);
	 	}

	 	public function getEmailSetting($userid, $email){
	 		$sql = "select * from email_setting WHERE userid = ".$userid." AND email = '".$email."'";
	 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
				return;
			}

			$row = $result->fetch_assoc();
			$imapConfig = array('username' => $row['username'],
								'password' => $row['password'],
								'host' => $row['host'],
								'port' => $row['port'],
								'secure' => $row['secure']);
			$smtpConfig = array('username' => $row['smtp_username'],
								'password' => $row['smtp_password'],
								'host' => $row['smtp_host'],
								'port' => $row['smtp_port'],
								'secure' => $row['smtp_secure']);

			return array('imap' => $imapConfig, 'smtp' => $smtpConfig);
	 	}	

	 	public function removeEmailForUser($userid, $email){
	 		$sql = "delete from email_setting WHERE userid = ".$userid." AND email = '".$email."'";
	 		return $this->dao->execUpdate($sql);
	 	}

	}
?>