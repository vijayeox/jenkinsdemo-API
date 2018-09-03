<?php
	namespace Oxzion;
	require_once __DIR__.'/../Common/Config.php';
	use Email\EmailConfig;
	use Email\EmailMessage;

	class EmailCache{
		
		private $userid;
		private $email;

		private $dao;

		public function __construct($userid, $email){
			$this->userid = $userid;
			$this->email = $email;
			$this->dao = new Dao();
		}

		public function getEmail(){
			return $this->email;
		}
		public function getUser(){
			return $this->userid;
		}
		public function __destruct(){
			$this->dao->close();
		}

		private function removeExpungedEmails($folder) {
			$tmpTable = $this->getTempTableName($folder);
			$filter = "where userid=".$this->userid." AND email = '".$this->email."' AND folder='".$folder."'";
			$where = $filter." AND uid IN (select uid from $tmpTable)";
			$innerSql = "select id from email_cache $where";
			$sql = "delete from email_cache $filter AND id NOT IN (select * from ($innerSql) tmp)";
			return $this->dao->execUpdate($sql);
		}

		private function getTempTableName($folder){
			$folder = str_replace(' ', '_', $folder);
			$folder = str_replace('__', '_', $folder);
			return substr(preg_replace("/[^a-zA-Z0-9\s]/", "_", $this->userid.$this->email.$folder),0,25);
		}
		private function loadIdListInTempTable($folder, $idMap){
			$tmpTable = $this->getTempTableName($folder);
			$this->dao->execUpdate("create temporary table if not exists $tmpTable (uid INT, KEY `idx_uid` (`uid`))ENGINE INNODB");
			
			
			if(count($idMap) > 0){
				$ids = array_chunk($idMap, EMAIL_SYNC_CHUNK_SIZE);
				foreach ($ids as $idx => $chunkList) {
					$sql = "";
					foreach ($chunkList as $key => $mapValue) {
						$sql = $sql ? "$sql, ($mapValue)" : "($mapValue)"; 
					}
					$sql = "INSERT INTO $tmpTable VALUES $sql";
				
					$this->dao->execUpdate($sql);
				}
			}
		}

		private function dropTempTable($folder){
			$tmpTable = $this->getTempTableName($folder);
			$this->dao->execUpdate("drop table $tmpTable");
			
		}

		private function getNewEmailsInList($folder){
			$tmpTable = $this->getTempTableName($folder);
			$clause = "userid=".$this->userid." AND email = '".$this->email."' AND folder='".$folder."'";
			$sql = "select tmp.uid from email_cache ec RIGHT OUTER JOIN $tmpTable tmp on tmp.uid = ec.uid AND $clause where ec.uid is NULL";
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			
			$newIds = array();
			while ($row = $result->fetch_assoc()) {
				array_push($newIds, $row['uid']);
			}
			$result->free();
			
			return $newIds;
		}

		private function getUnseenEmailCount($folder){
			$tmpTable = $this->getTempTableName($folder);
			$clause = "userid=".$this->userid." AND email = '".$this->email."' AND folder='".$folder."'";
			$sql = "select count(ec.id) as unseen_cnt from email_cache ec INNER JOIN $tmpTable tmp on tmp.uid = ec.uid and $clause where ec.unseen is true";
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			$row = $result->fetch_assoc();
			return $row['unseen_cnt'];
		}

		public function getUnseenCount($folder, $ids){
			$this->loadIdListInTempTable($folder, $ids);
			$cnt = $this->getUnseenEmailCount($folder);
			$this->dropTempTable($folder);
			return $cnt;
		}
		public function getLatestUnseenEmailIds($folder, $top=LATEST_UNSEEN_EMAILS_TO_CACHE){
			$clause = "userid=".$this->userid." AND email = '".$this->email."' AND folder='".$folder."'";
			
			$sql = "select uid from email_cache where $clause order by datetime desc LIMIT $top";
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			$data = array();
			while ($row = $result->fetch_assoc()) {
				array_push($data, $row['uid']);
			}
			$result->free();
			return $data;
		}

		public function expungeAndGetNewIds($folder, $ids){
			$this->loadIdListInTempTable($folder, $ids);
			$this->removeExpungedEmails($folder);
			$newIds = $this->getNewEmailsInList($folder);
			$this->dropTempTable($folder);
			return $newIds;

		}
		
		public function expungeMessages($folder, $ids){
			$this->loadIdListInTempTable($folder, $ids);
			$tmpTable = $this->getTempTableName($folder);
			
			$where = "where userid=".$this->userid." AND email = '".$this->email."' AND folder='".$folder."' AND uid IN (select uid from $tmpTable)";
			$innerSql = "select id from email_cache $where";
			$sql = "delete from email_cache where id IN (select * from ($innerSql) tmp)";
			$result = $this->dao->execUpdate($sql);
			$this->dropTempTable($folder);
			return $result;
		}

		public function getMailList($folder, $ids = array(), $sortOptions = array()){
			$joinClause = "";
			if(!empty($ids)){
				$this->loadIdListInTempTable($folder, $ids);
				$tmpTable = $this->getTempTableName($folder);
				$joinClause = "inner join $tmpTable t on ec.uid = t.uid AND ec.email = '".$this->email."'";
			}
			$clause = "ec.userid=".$this->userid." AND ec.folder='".$folder."'";
			$order = "ORDER BY";
			$reverse = "";
			foreach ($sortOptions as $key => $value) {
				$order = $order != "ORDER BY" ? "$order," : $order;
				switch ($value) {
					case EmailConfig::SORT_REVERSE:
						$reverse = "DESC";
						break;
					case EmailConfig::SORT_ARRIVAL:
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
			$sql = "select ec.envelope from email_cache ec $joinClause where $clause $order";
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

		public function updateCache($folder, $data){
			$clause = "userid=".$this->userid." AND email = '".$this->email."' AND folder='".$folder."' AND uid = ".$data['uid'];
			
			$sql = "delete from email_cache where $clause";  
			$this->dao->execUpdate($sql);
			$cc = $this->dao->escapeString($data['cc-csv']);
			$from = $this->dao->escapeString($data['from-csv']);
			$to = $this->dao->escapeString($data['to-csv']);
			$subject = $this->dao->escapeString($data['subject']);
			$content = json_encode($data);
			$content = $this->dao->escapeString($content);
			$unseen = in_array(EmailMessage::FLAG_SEEN, $data['flags']) ? 0 : 1;
			$sql = "insert into email_cache (userid, email, folder, uid, cc, _from, _subject, _to, envelope, unseen, datetime) VALUES ('".$this->userid."', '".$this->email."', '".$folder."', ".$data['uid'].", '".$cc."', '".$from."', '".$subject."', '".$to."', '".$content."', $unseen, '".$data['datetime']."')";
			$result = $this->dao->execUpdate($sql);
			
			return $result;
		}

		public function refreshContacts(){
			$sql = "select envelope from email_cache where userid=".$this->userid;
	 		if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			$count = 0;
	 		while ($row = $result->fetch_assoc()) {
	 			$data = json_decode($row['envelope'], true);
	 			foreach ($data['cc'] as $key => $value) {
					$count += $this->updateContact($value);
				}
				foreach ($data['from'] as $key => $value) {
					$count += $this->updateContact($value);
				}
				foreach ($data['bcc'] as $key => $value) {
					$count += $this->updateContact($value);
				}
				foreach ($data['to'] as $key => $value) {
					$count += $this->updateContact($value);
				}

	 		}

	 		$result->free();

	 		return $count;
		}
		private function updateContact($contact){
			if($contact['bare_address'] == $this->email){
				return;
			}
			$sql = "";
			$name = str_replace("'", "", $contact['personal']);
			$email = str_replace("'", "", $contact['bare_address']);
			$count = 0;		
			if(!$this->checkIfContactEmailExists($email)){
				$sql = "insert into user_contact (userid, name, email) VALUES (".$this->userid.", '".$name."', '".$email."')"; 
			}

			if(!empty($sql)){
				$this->dao->execUpdate($sql);
				$count++;
			}

			return $count;
		}

		private function checkIfContactEmailExists($email){
			$sql = "select count(id) as cnt from user_contact where userid = ".$this->userid." and email = '".$email."'";
			if(!$result = $this->dao->execQuery($sql)){
				return;
			}
			$row = $result->fetch_assoc();
			$val = $row['cnt'] > 0;
			$result->free();
			return $val;
		}
	}
?>