	<?php
	require __DIR__ .'/autoload.php';
	require_once __DIR__.'/Common/Config.php';
	use Email\EmailConfig;
	use Oxzion\Dao;
	use Oxzion\EmailSyncJob;
	use Email\EmailClient;
	use Oxzion\EmailClientCache;
	use Email\EmailMessage;
	use Oxzion\OAuthService;
	use Messaging\MessageProducer;
	use Auth\GoogleOAuth;
	use Auth\OutlookOAuth;
	use Auth\OAuth;
	use Job\Job;

	class VA_ExternalLogic_EmailService{
		private $dao;
		private $job;

		public function __construct(){
			$this->dao = new Dao();
			$this->avatarobj = VA_Logic_Session::getAvatar();
			$this->job = EmailSyncJob::getInstance();
		}
		public function createEmailClient($avatarid, $email, $username, $password, $host, $port,$secure,$oauth_provider=null){
			if($oauth_provider){
				$service = new OAuthService();
				$credentials = $service->getCredentials($avatarid, $email,$oauth_provider);
				$client = EmailClientCache::getEmailClient($avatarid,$email);
				return EmailClientCache::setupEmailClient($avatarid, $email, $username, $password, $host, $port,$secure,$credentials['access_token']);
			} else {
				$client = EmailClientCache::getEmailClient($avatarid,$email);
				return EmailClientCache::setupEmailClient($avatarid, $email, $username, $password, $host, $port,$secure);
			}
		}
		public function getMailBoxes($avatarid){
			$emaillist = array();
			$accountmapper = new VA_Model_EmailAccounts();
			$emailcache = new VA_Model_EmailCache();
			$accounts = $accountmapper->enlistByAvatarId($avatarid);
			$i=0;
			foreach ($accounts as $account) {
				$foldrs = json_decode($accountmapper->enlistFoldersByAvatarId($avatarid,$account['email'])['folders']);
				if($foldrs){
					$emaillist[$i]['email'] = $account['email'];
					$emaillist[$i]['lastsynctime']=VA_Service_Utils::dateFromUTC(date('Y-m-d'), $this->avatarobj->getTimeZone());
					$emaillist[$i]['id']=$i;
					try{
						$emailclient = $this->createEmailClient($avatarid, $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
						$sentfolder = $emailclient->getSpecialFolders('sent');
						$draftfolder = $emailclient->getSpecialFolders('drafts');
						$trashfolder = $emailclient->getSpecialFolders('trash');
					} catch (Exception $e){
						error_log("VA_ExternalLogic_EmailService->getMailBoxes : Encountered Exception =>".$e->getMessage());
					}
					$j=4;
					foreach ($foldrs as $key => $value) {
						if(strtolower($value)=='inbox'){
							$emaillist[$i]['labels'][0] = array();
							$emaillist[$i]['labels'][0]['label']=$value;
							try{
								if($emailclient){
									$emaillist[$i]['labels'][0]['count'] = $emailclient->count($value)['count'];
								}
							} catch (Exception $e){
								error_log("VA_ExternalLogic_EmailService->getMailBoxes : Encountered Exception =>".$e->getMessage());
							}
						} else if($value == $sentfolder){
							$emaillist[$i]['labels'][1] = array();
							$emaillist[$i]['labels'][1]['label']=$value;
							$emaillist[$i]['labels'][1]['number'] = '';
						} else if ($value == $draftfolder){
							$emaillist[$i]['labels'][3] = array();
							$emaillist[$i]['labels'][3]['label']=$value;
							$emaillist[$i]['labels'][3]['type']='1';
							$emaillist[$i]['labels'][3]['number'] = '';
						}  else if($value == $trashfolder){
							$emaillist[$i]['labels'][2] = array();
							$emaillist[$i]['labels'][2]['label']=$value;
							$emaillist[$i]['labels'][2]['number'] = '';
						}else {
							$emaillist[$i]['labels'][$j] = array();
							$emaillist[$i]['labels'][$j]['label']=str_replace("'", "", $value);
							$emaillist[$i]['labels'][$j]['number'] = '';
							$j++;
						}
					}
				} else {
					try{
						$emailclient = $this->createEmailClient($avatarid, $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
						$foldrs = $emailclient->listMailBoxes('*');
						$emaillist[$i]['email'] = $account['email'];
						$emaillist[$i]['lastsynctime']=VA_Service_Utils::dateFromUTC(date('Y-m-d'), $this->avatarobj->getTimeZone());
						$emaillist[$i]['id']=$i;
						$sentfolder = $emailclient->getSpecialFolders('sent');
						$draftfolder = $emailclient->getSpecialFolders('drafts');
						$trashfolder = $emailclient->getSpecialFolders('trash');
						$j=1;
						foreach ($foldrs as $key => $value) {
							if(strtolower($value)=='inbox'){
								$emaillist[$i]['labels'][0]['label']=$value;
								$emaillist[$i]['labels'][0]['count'] = $emailclient->count($value)['count'];
							} else if($value == $sentfolder){
								$emaillist[$i]['labels'][1]['label']=$value;
								$emaillist[$i]['labels'][1]['number'] = '';
							} else if ($value == $draftfolder){
								$emaillist[$i]['labels'][2]['label']=$value;
								$emaillist[$i]['labels'][2]['number'] = '';
							}  else if($value == $trashfolder){
								$emaillist[$i]['labels'][3]['label']=$value;
								$emaillist[$i]['labels'][3]['number'] = '';
							}else {
								$emaillist[$i]['labels'][$j]['label']=$value;
								$emaillist[$i]['labels'][$j]['number'] = '';
								$j++;
							}
						}
						$content = $foldrs;
						$content = str_replace("\\", "\\\\", $content);
						$content = str_replace("\"", "\\\"", str_replace("\\", "\\\\", $content));
						$content = str_replace("'", "''", $content);
						$data['folders'] = json_encode($content);
						$data['id'] = $account['id'];
						$accountmapper->update($data);
					} catch (Exception $e){
						error_log("VA_ExternalLogic_EmailService->getMailBoxes : Encountered Exception =>".$e->getMessage());
					}
				} 
				$i++;
			}
			return $emaillist;
		}
		public function syncMailBox($account,$folder){
			$emailclient =  $this->createEmailClient($account['userid'], $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
			return $emailclient->syncEmails($folder);
		}
		public function getMaildescription($id,$avatarid,$mailid,$folder){
			$accountmapper = new VA_Model_EmailAccounts();
			$account = $accountmapper->enlistByAvatarIdandEmailId($avatarid,$mailid);
			$emailclient =  $this->createEmailClient($account['userid'], $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
			$result = $emailclient->store($folder, array('ids'=>new Horde_Imap_Client_Ids($id),'add' => EmailMessage::FLAG_SEEN));
			return $emailclient->getEmailMessage($id,$folder);
		}
		public function markReadUnread($id,$avatarid,$mailid,$folder,$status){
			$accountmapper = new VA_Model_EmailAccounts();
			$account = $accountmapper->enlistByAvatarIdandEmailId($avatarid,$mailid);
			$emailclient =  $this->createEmailClient($account['userid'], $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
			$mailcache = new VA_Model_EmailCache();
			if($status==1){
				$result = $emailclient->store($folder, array('ids'=>new Horde_Imap_Client_Ids($id),'add' => EmailMessage::FLAG_SEEN));
				$mailcache->update(array('uid'=>$id,'email'=>$mailid,'folder'=>$folder,'userid'=>$avatarid,'unseen'=>0));
			} else {
				$result = $emailclient->store($folder, array('ids'=>new Horde_Imap_Client_Ids($id),'remove' => EmailMessage::FLAG_SEEN));
				$mailcache->update(array('uid'=>$id,'email'=>$mailid,'folder'=>$folder,'userid'=>$avatarid,'unseen'=>1));
			}
			$emailclient->syncEmails($folder);
			return $result;
		}
		public function getLabels($avatarid,$emailid){
			$accountmapper = new VA_Model_EmailAccounts();
			return json_decode($accountmapper->enlistFoldersByAvatarId($avatarid,$emailid)['folders']);
		}
		public function deleteMessage($id,$avatarid,$mailid,$folder){
			$accountmapper = new VA_Model_EmailAccounts();
			$account = $accountmapper->enlistByAvatarIdandEmailId($avatarid,$mailid);
			$emailclient =  $this->createEmailClient($account['userid'], $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
			$emailclient->deleteMessage($id,$folder);
			$emailclient->syncEmails($folder);
			return;
		}
		public function getMailContent($messageid,$userid,$folder,$box,$avatar){
			$mapper = new VA_Model_EmailCache();
			$data = $mapper->getMessage($messageid,$userid,$folder);
			$message = $this->getMaildescription($messageid,$userid,$box,$folder);
			$data['subject'] = $data['subject'];
			$data['envelope'] = json_decode($data['envelope'], true);
			$data['fromavatar'] = $data['envelope']['from-csv'];
			$data['recepients'] = $data['envelope']['to-csv'];
			$data['ccrecepients'] = $data['envelope']['cc-csv'];
			if(!in_array($box, explode(",", $data['cc']))&& !in_array($box, explode(",", $data['to'])) &&$data['fromavatar']!=$box){
				$data['bccrecepients'] = $box;
			}
			$data['date_full'] = VA_Service_Utils::getFormatedDateNoTime($data['date_created'], 'M j, Y h:i A');
			$data['date'] = VA_Logic_Utilities::getMailTime($data['date_full'], $avatar,0);
			$data['date_created'] = VA_Service_Utils::getTimeStampRelativeDateTime($data['date_created']);
			if($message){
				if($data['message']==''||!$data['message']){
					$data['message'] = $message->getBodyText();
				}
				if($data['message']==''||!$data['message']){
					foreach ($message->getBodyParts() as $key => $value) {
						if($value->getType()=='text/html'){
							$data['message'] .= base64_decode($value->getContents());
						}
					}
				}
				if($data['message']==''||!$data['message']){
					foreach ($message->getBodyParts() as $key => $value) {
						$data['message'] .= $value->getContents();
					}
				}
				$encodedbox= urlencode($box);
				$encodedfolder= urlencode($folder);
				$path = APPLICATION_DATA."/uploads/attachments/";
				if(!file_exists($path)){
					mkdir($path, 0777, true);
				}
				$filepath = $path."/$userid";                
				if (!file_exists($path."/$userid")) {
					mkdir($path."/$userid", 0777, true);
				}
				$folderpath = $path."/$userid";
				if (!file_exists($folderpath."/$box/")) {
					mkdir($folderpath."/$box/", 0777, true);
				}
				$folderpath = $folderpath."/$box";
				if (!file_exists($folderpath."/$folder/")) {
					mkdir($folderpath."/$folder", 0777, true);
				}
				$folderpath = $folderpath."/$folder";
				if(!file_exists($folderpath."/$messageid/")){
					mkdir($folderpath."/$messageid", 0777, true);
				}
				$folderpath = $folderpath."/$messageid"."/";
				foreach ($message->getAttachmentParts() as $key => $value) {
					if(!file_exists($folderpath.$message->getPartName($value))){
						$file = fopen($folderpath.$message->getPartName($value), "w");
						fwrite($file, $value->getContents());
						if($message->getPartName($value)){
							$data['message'] .= '<a target="_blank" href="'.$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].VA_Logic_Session::getBaseUrl()."/../data/uploads/attachments/".$userid."/$encodedbox/$encodedfolder/$messageid/".$message->getPartName($value).'">'.$message->getPartName($value).'</a></br>';
						}
					} else {
						if($message->getPartName($value)){
							$data['message'] .= '<a target="_blank" href="'.$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].VA_Logic_Session::getBaseUrl()."/../data/uploads/attachments/".$userid."/$encodedbox/$encodedfolder/$messageid/".$message->getPartName($value).'">'.$message->getPartName($value).'</a></br>';
						}
					}
				}
			}
			return $data;
		}
		public function getAttachments($messageid,$userid,$folder,$box){
			$message = $this->getMaildescription($messageid,$userid,$box,$folder);
			if($message){
				foreach ($message->getAttachmentParts() as $key => $value) {
					$attacharray[] = $message->getPartName($value);
				}
			}
			return $attacharray;
		}
		public function getMailDescr($messageid,$userid,$folder,$box){
			$mapper = new VA_Model_EmailCache();
			$message = $this->getMaildescription($messageid,$userid,$box,$folder);
			foreach ($message->getBodyParts() as $key => $value) {
				if($message->getPartName($value)){
					$data['message'] .= utf8_encode($value->getContents());
				}
			}
			return $data;
		}
		public function SaveDraft($userid,$fromid,$data){
			$accountmapper = new VA_Model_EmailAccounts();
			$account = $accountmapper->enlistByAvatarIdandEmailId($userid,$fromid);
			$client =  $this->createEmailClient($account['userid'], $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
			$crypto = new VA_Logic_Crypto();
			$options['html'] = true;
			$options['priority'] = 'high';
			if ($data['avatarlist']) {
				$data['avatarlist'] = array_filter(array_unique($data['avatarlist']));
				$recepientdata = array();
				foreach ($data['avatarlist'] as $avatarid) {
					$avlogic = new VA_Logic_Avatar($avatarid);
					if ($avlogic->status == 'Active') {
						$recepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['grouplist']){
				$mapper = new VA_Model_GroupsAvatars();
				$group_avatars = $mapper->enlistAvatarsByGroupId($data['grouplist']);
				foreach ($group_avatars as $avatar){
					$avlogic = new VA_Logic_Avatar($avatar['avatarid']);
					if ($avlogic->status == 'Active'){
						$recepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['emaillist']) {
				foreach (array_unique(explode(",", $data['emaillist'])) as $email) {
					$recepientdata[] = $email;
				}
			}
			if ($data['ccavatarlist']) {
				$data['ccavatarlist'] = array_filter(array_unique($data['ccavatarlist']));
				$ccrecepientdata = array();
				foreach ($data['ccavatarlist'] as $avatarid) {
					$avlogic = new VA_Logic_Avatar($avatarid);
					if ($avlogic->status == 'Active') {
						$ccrecepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['ccgrouplist']){
				$mapper = new VA_Model_GroupsAvatars();
				$group_avatars = $mapper->enlistAvatarsByGroupId($data['ccgrouplist']);
				foreach ($group_avatars as $avatar){
					$avlogic = new VA_Logic_Avatar($avatar['avatarid']);
					if ($avlogic->status == 'Active'){
						$ccrecepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['ccemaillist']) {
				foreach (array_unique(explode(",", $data['ccemaillist'])) as $email) {
					$ccrecepientdata[] = $email;
				}
			}
			if ($data['bccavatarlist']) {
				$data['bccavatarlist'] = array_filter(array_unique($data['bccavatarlist']));
				$bccrecepientdata = array();
				foreach ($data['bccavatarlist'] as $avatarid) {
					$avlogic = new VA_Logic_Avatar($avatarid);
					if ($avlogic->status == 'Active') {
						$bccrecepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['bccgrouplist']){
				$mapper = new VA_Model_GroupsAvatars();
				$group_avatars = $mapper->enlistAvatarsByGroupId($data['bccgrouplist']);
				foreach ($group_avatars as $avatar){
					$avlogic = new VA_Logic_Avatar($avatar['avatarid']);
					if ($avlogic->status == 'Active'){
						$bccrecepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['bccemaillist']) {
				foreach (array_unique(explode(",", $data['bccemaillist'])) as $email) {
					$bccrecepientdata[] = $email;
				}
			}
			$recepientslist = implode(",", $recepientdata);
			$ccrecepientslist = implode(",", $ccrecepientdata);
			$bccrecepientslist = implode(",", $bccrecepientdata);
			$headers = array('from' => $account['email'],'to' => $recepientslist,'cc' => $ccrecepientslist,'bcc' => $bccrecepientslist,'subject' => $data['subject']);
			return $client->buildDraftMessage($data['message'], $this->getAttachmentArray($userid,$data['orgid']), $headers, array('id'=>$data['draftid']));
		}

		public function SendMessage($userid,$fromid,$data){
			$accountmapper = new VA_Model_EmailAccounts();
			$account = $accountmapper->enlistByAvatarIdandEmailId($userid,$fromid);
			$client =  $this->createEmailClient($account['userid'], $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
			if($account['oauth_provider']){
				$service = new OAuthService();
				$credentials = $service->getCredentials($account['userid'], $account['email'],$account['oauth_provider']);
				$smtpConfig = array('host' => $account['smtp_host'],'username' => $account['username'],'xoauth2_token' => $credentials['access_token'],'port' => $account['smtp_port'],'secure' => $account['smtp_secure']);
			} else {
				$crypto = new VA_Logic_Crypto();
				$smtpConfig = array('host' => $account['smtp_host'],'username' => $account['username'],'password' => $crypto->decryption($account['password']),'port' => $account['smtp_port'],'secure' => $account['smtp_secure']);
			}
			$crypto = new VA_Logic_Crypto();
			$options['html'] = true;
			$options['priority'] = 'high';
			if ($data['avatarlist']) {
				$data['avatarlist'] = array_filter(array_unique($data['avatarlist']));
				$recepientdata = array();
				foreach ($data['avatarlist'] as $avatarid) {
					$avlogic = new VA_Logic_Avatar($avatarid);
					if ($avlogic->status == 'Active') {
						$recepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['grouplist']){
				$mapper = new VA_Model_GroupsAvatars();
				$group_avatars = $mapper->enlistAvatarsByGroupId($data['grouplist']);
				foreach ($group_avatars as $avatar){
					$avlogic = new VA_Logic_Avatar($avatar['avatarid']);
					if ($avlogic->status == 'Active'){
						$recepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['emaillist']) {
				foreach (array_unique(explode(",", $data['emaillist'])) as $email) {
					$recepientdata[] = $email;
				}
			}
			if ($data['ccavatarlist']) {
				$data['ccavatarlist'] = array_filter(array_unique($data['ccavatarlist']));
				$ccrecepientdata = array();
				foreach ($data['ccavatarlist'] as $avatarid) {
					$avlogic = new VA_Logic_Avatar($avatarid);
					if ($avlogic->status == 'Active') {
						$ccrecepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['ccgrouplist']){
				$mapper = new VA_Model_GroupsAvatars();
				$group_avatars = $mapper->enlistAvatarsByGroupId($data['ccgrouplist']);
				foreach ($group_avatars as $avatar){
					$avlogic = new VA_Logic_Avatar($avatar['avatarid']);
					if ($avlogic->status == 'Active'){
						$ccrecepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['ccemaillist']) {
				foreach (array_unique(explode(",", $data['ccemaillist'])) as $email) {
					$ccrecepientdata[] = $email;
				}
			}
			if ($data['bccavatarlist']) {
				$data['bccavatarlist'] = array_filter(array_unique($data['bccavatarlist']));
				$bccrecepientdata = array();
				foreach ($data['bccavatarlist'] as $avatarid) {
					$avlogic = new VA_Logic_Avatar($avatarid);
					if ($avlogic->status == 'Active') {
						$bccrecepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['bccgrouplist']){
				$mapper = new VA_Model_GroupsAvatars();
				$group_avatars = $mapper->enlistAvatarsByGroupId($data['bccgrouplist']);
				foreach ($group_avatars as $avatar){
					$avlogic = new VA_Logic_Avatar($avatar['avatarid']);
					if ($avlogic->status == 'Active'){
						$bccrecepientdata[] = $avlogic->email;
					}
				}
			}
			if ($data['bccemaillist']) {
				foreach (array_unique(explode(",", $data['bccemaillist'])) as $email) {
					$bccrecepientdata[] = $email;
				}
			}
			$recepientslist = implode(",", $recepientdata);
			$ccrecepientslist = implode(",", $ccrecepientdata);
			$bccrecepientslist = implode(",", $bccrecepientdata);
			$headers = array('from' => $account['email'],'to' => $recepientslist,'cc' => $ccrecepientslist,'bcc' => $bccrecepientslist,'subject' => $data['subject']);
			return $client->buildAndSendMessage($data['message'], $this->getAttachmentArray($userid,$data['orgid']), $headers, $smtpConfig, $options,$data['draftid']);
		}
		public function getAttachmentArray($avatarid,$orgid) {
			$mapper_tmp = new VA_Model_Instformsfilestmp();
			$attachedfiles = $mapper_tmp->enlistMsgFileByAvatarId($avatarid);
			$foldername = "message_".$avatarid;
			$filepathsrc = APPLICATION_DATA . "/uploads/organization/$orgid/temp/$foldername/";
			foreach ($attachedfiles as $filevalue) {
				$data = array();
				$data['file']=$filepathsrc.$filevalue['filename'];
				$data['bytes']=$filevalue['filename'];
				$data['filename']=$filevalue['filename'];
				$attacharray[] = $data;
			}
			$mapper_tmp->deleteMsgFileByAvatarId($avatarid);
			return $attacharray;
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

		 	public function syncEmailsForUser($userid){
		 		$sql = "select * from email_setting WHERE userid = ".$userid;
		 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
		 			return;
		 		}
		 		$imapConfig = array();
		 		$smtpConfig = array();
		 		$status = "";
		 		$details = array();
		 		$total = 0;
		 		$failures = 0;
		 		while ($row = $result->fetch_assoc()) {
		 			$start = new DateTime();
		 			$total++;
		 			$emailClient =  $this->createEmailClient($row['userid'], $row['email'], $row['username'], $row['password'], $row['host'], $row['port'],$row['secure'],$row['oauth_provider']);
		 			$end = new DateTime();
		 			$diff = $start->diff($end);
		 			$this->setSyncDetails($row['userid'], $row['email'], "In Progress...", 0, $start, $folders);
		 			try{
		 				$emailClient->syncAllImapFoldersAndContacts(true);
		 				$this->setSyncDetails($row['userid'], $row['email'], "Sync Completed Successfully", $diff->s);
		 			}catch(Exception $e){
		 				$end = new DateTime();
		 				$diff = $start->diff($end);
		 				$failures++;
		 				$details[$row['email']] = $e->getMessage();
		 				$this->setSyncDetails($row['userid'], $row['email'], "Sync Failed : ".$e->getMessage(), $diff->s);
		 			}
		 		}
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
		 		$this->job->syncCompleted($userid, $status, $content);

		 	}
		 	public function syncEmailsForUserbyEmail($userid,$emailid){
		 		$sql = "select * from email_setting WHERE userid = ".$userid." and email ='".$emailid."'";
		 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
		 			return;
		 		}
		 		while ($row = $result->fetch_assoc()) {
		 			$emailClient =  $this->createEmailClient($row['userid'], $row['email'], $row['username'], $row['password'], $row['host'], $row['port'],$row['secure'],$row['oauth_provider']);
		 			$emailClient->syncEmails();
		 			$emailClient->syncEmails($emailClient->getSpecialFolders('trash'));
		 			$emailClient->syncEmails($emailClient->getSpecialFolders('sent'));
		 			$emailClient->syncEmails($emailClient->getSpecialFolders('draft'));
		 		}
		 	}
		 	public function syncEmailsForUserbyEmailByFolder($userid,$emailid,$folder,$ids){
		 		$sql = "select * from email_setting WHERE userid = ".$userid." and email ='".$emailid."'";
		 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
		 			return;
		 		}
		 		while ($row = $result->fetch_assoc()) {
		 			try{
		 				$emailClient =  $this->createEmailClient($row['userid'], $row['email'], $row['username'], $row['password'], $row['host'], $row['port'],$row['secure'],$row['oauth_provider']);
		 				$emailClient->syncEmailsbyids($ids,$folder);
		 			} catch (Exception $e){
		 				error_log("EmailService->syncEmailsForUserbyEmailByFolder : Encountered Exception =>".$e->getMessage());
		 			}
		 		}
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
		 	function signinEmails($userid){
		 		try{
		 			$producer = MessageProducer::getInstance();	
		 			$producer->sendMessage(array('userid' => $userid), LOGIN_EMAILS);
		 			// $this->addEmailSyncJob($userid);
		 		} catch (Exception $e){

		 		}
		 	}
		 	function signoutEmails($userid){
		 		try{
		 			$producer = MessageProducer::getInstance();	
		 			$producer->sendMessage(array('userid' => $userid), LOGOUT_EMAILS);
		 			$job = Job::getInstance();
		 			$job->removeJob($userid, EMAIL_SYNC_JOB);	
		 		} catch (Exception $e){
		 			
		 		}
		 	}
		 	/*
	 * This api adds a job and the job runner will immediately start the execution of the job.
	 *
	 */
		 	function addEmailSyncJob($userid, $email = NULL){
		 		$job = Job::getInstance();
		 		$producer = MessageProducer::getInstance();	
		 		$jobParams = array('userid' => $userid);
		 		if(!is_null($email)){
		 			$jobParams['email'] = $email;
		 		}
		 		$job->addJob($userid, EMAIL_SYNC_JOB, "\Oxzion\EmailSyncTask", $jobParams, 5, 5, True);
		 	}

		 	function syncEmail($userid, $email = NULL){
		 		$producer = MessageProducer::getInstance();	
		 		$params = array('userid' => $userid);
		 		if(!is_null($email)){
		 			$params['email'] = $email;
		 		}
		 		$producer->sendMessage($params, SYNC_EMAIL);
		 	}

		 	function heartBeat($userid){
		 		try{
		 			$job = EmailSyncJob::getInstance();
		 			$job->jobHeartBeat($userid);
		 		} catch (Exception $e){

		 		}
		 	}
		 	function imapLogin($userid, $email, $password, $host,$port,$secure){
		 		$crypto = new VA_Logic_Crypto();
		 		$client = $this->createEmailClient($userid, $email, $email, $crypto->encryption(str_replace(" ", "", $password)), $host,$post,$secure);
		 		try{
		 			$client->login();
		 		} catch(Horde_Imap_Client_Exception $e){
		 			return $e->getMessage();
		 		}
		 		return 1;
		 	}
		 	function imapsearch($avatarid, $text){
		 		$accountmapper = new VA_Model_EmailAccounts();
		 		$accounts = $accountmapper->enlistByAvatarId($avatarid);
		 		foreach ($accounts as $account) {
		 			$emailclient =  $this->createEmailClient($avatarid, $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
		 			$folders = $accountmapper->enlistFoldersByAvatarId($avatarid,$account['email']);
		 			$results[] = $emailclient->search('INBOX', array('sort' => array(EmailConfig::SORT_REVERSE, EmailConfig::SORT_ARRIVAL)),EMAIL_SYNC_FOR_LAST_MONTHS, $text,false,false);
		 		}
		 		return $results;
		 	}
		 	function moveEmails($avatarid, $box,$folder,$destfolder,$id){
		 		$accountmapper = new VA_Model_EmailAccounts();
		 		$account = $accountmapper->enlistByAvatarIdandEmailId($avatarid,$box);
		 		$emailclient =  $this->createEmailClient($account['userid'], $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
		 		$return = $emailclient->moveEmails($id, $folder,$destfolder);
		 		return $return;
		 	}
		 	function deleteMail($id,$avatarid, $box,$folder){
		 		$accountmapper = new VA_Model_EmailAccounts();
		 		$account = $accountmapper->enlistByAvatarIdandEmailId($avatarid,$box);
		 		$emailclient =  $this->createEmailClient($account['userid'], $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
		 		$return = $emailclient->deleteEmails($id, $folder);
		 		return $return;
		 	}
		 	function countMail($avatarid, $box,$folder){
		 		$accountmapper = new VA_Model_EmailAccounts();
		 		$account = $accountmapper->enlistByAvatarIdandEmailId($avatarid,$box);
		 		$emailclient =  $this->createEmailClient($account['userid'], $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
		 		return $emailclient->count($folder)['count'];
		 	}
		 	function googleOauth(){
		 		$client = GoogleOAuth::getClient();
		 		return $client->createAuthUrl();
		 	}

		 	function OutlookOauth(){
		 		$client = OutlookOAuth::getClient();
		 		return OutlookOAuth::getAuthenticationUrl('OUTLOOK');
		 	}
		 	function saveOAuthcredentials($userid,$provider,$authCode){
		 		$service = new OAuthService();
		 		if($provider == 'OUTLOOK'){
		 			$accessToken = OAuth::getAccessTokenWithAuthCode('OUTLOOK',$authCode);
		 			$outlookOauth = new OutlookOAuth($accessToken);
		 			$emailid = $outlookOauth->getEmailid();
		 		} else {
		 			$client = GoogleOAuth::getClient();
		 			$accessToken = $client->fetchAccessTokenWithAuthCode(trim($authCode));
		 			$googleOauth = new GoogleOAuth($accessToken['access_token']);
		 			$emailid = $googleOauth->getGoogleEmailid();
		 		}
		 		$service->saveCredentials($userid, $emailid, $provider, $accessToken);
		 		return $emailid;
		 	}
		 	function getSpecialFolder($avatarid,$box,$type){
		 		$accountmapper = new VA_Model_EmailAccounts();
		 		$account = $accountmapper->enlistByAvatarIdandEmailId($avatarid,$box);
		 		try{
		 			$emailclient = $this->createEmailClient($avatarid, $account['email'], $account['username'], $account['password'], $account['host'], $account['port'],$account['secure'],$account['oauth_provider']);
		 			return $emailclient->getSpecialFolders($type);
		 		} catch(Exception $e){
		 			return;
		 		}
		 	}
		 }
		 ?>