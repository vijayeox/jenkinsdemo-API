<?php
namespace Email;
include_once __DIR__.'/../autoload.php';
include __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../Common/Config.php';
use Horde_Imap_Client_Socket;
use Horde_Imap_Client_Search_Query;
use Horde_Imap_Client_Fetch_Query;
use Horde_Imap_Client_Data_Fetch;
use Horde_Imap_Client_Ids;
use Horde_Mail_Rfc822_List;
use Horde_Exception;
use Horde_Mime_Headers;
use Horde_Mime_Headers_Date;
use Horde_Mime_Headers_MessageId;
use Horde_Mime_Mdn;
use Horde_Core_Factory_TextFilter;
use Horde_Mime_Part;
use Horde_Text_Flowed;
use Horde_Mime_Magic;
use Horde_Mime_Headers_ContentParam;
use Horde_Url_Data;
use Horde_Mail_Transport_Smtphorde;
use Horde_Mime_Headers_UserAgent;
use Horde_Injector;
use Horde_Injector_TopLevel;
use Horde_Domhtml;
use DOMXPath;
use Horde_Imap_Client;
use Horde_Mail_Exception;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

use HTMLPurifier;
use HTMLPurifier_Config;
use PhpMimeMailParser\Parser;
use DateTime;
use DateInterval;

use Email\EmailConfig;
use Email\EmailMessage;
use Oxzion\EmailCache;
use Cache\FileCache;

class EmailClient{
	const TIMEOUT = 100;
	private $client;
	private $emailCache; 
	private $host;
	private $fileCache;
	private $email;

		/**
		 * $authMode - Default - password, 
		 *				when set to 'xoauth2' the 
		 *				$password should contain the xoauth2 token 
		 *
		 */
		public function __construct($userid, $email, $username, $password, $host, $port = '993', $secure = 'tlsv1', $oauthToken = ''){
		    /* Connect to an IMAP server.
		     */
		    $this->host = $host;
		    $this->email = $email;
		    $pwd = json_decode(utf8_encode((mcrypt_decrypt(MCRYPT_RIJNDAEL_128, hash("sha256", "arogAegatnaVOfficeBack123", TRUE), $this->base_64_decode($password), MCRYPT_MODE_ECB))));
		    if(!$pwd){
		    	$pwd = preg_replace("/((^\{|\"\,))([0-9A-z]+)\:/", '${1}"${3}":',mcrypt_decrypt(MCRYPT_RIJNDAEL_128, hash("sha256", "arogAegatnaVOfficeBack123", TRUE), $this->base_64_decode($password), MCRYPT_MODE_ECB));
		    	$pwd = str_replace("\"","",$pwd);
		    	$pwd = html_entity_decode(strip_tags($pwd));
		    }
		    if(!isset($password) || !strlen($password)){
		    	$pwd = '##$$##$'; //Horde throws an exception if we send empty password
		    }
		    $params = array(
		    	'username' => $username,
		    	'password' => $pwd,
		    	'hostspec' => $host,
		    	'port' => $port,
		    	'secure' => $secure,
		 		//'debug_literal' => true,
		        // OPTIONAL Debugging. Will output IMAP log to the /tmp/foo file
		    	// 'debug' => '../imap.log'

		        // OPTIONAL Caching. Will use cache files in /tmp/hordecache.
		        // Requires the Horde/Cache package, an optional dependency to
		        // Horde/Imap_Client.
		        /*'cache' => array(
		            'backend' => new Horde_Imap_Client_Cache_Backend_Cache(array(
		                'cacheob' => new Horde_Cache(new Horde_Cache_Storage_File(array(
		                    'dir' => 'hordecache'
		                )))
		            ))
		            )*/
		            );
		    $params['password'] = $pwd;
		    if(isset($oauthToken) && $oauthToken != ''){
		    	$params['xoauth2_token'] = $this->getOAuth64($email, $oauthToken);
		    }
		    $this->client = new Horde_Imap_Client_Socket($params);
		    $this->emailCache = new EmailCache($userid, $email);
		    $this->fileCache = new FileCache();
		}
		function clean($string) {
   			$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   			return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
   		}
   		function base_64_decode($value) {
   			$mod = 4 - strlen($value) % 4;
   			$counter = 1;
   			while ($counter <= $mod) {
   				$value.="=";
   				$counter++;
   			}
   			return base64_decode(strtr($value,"-_", "+/" ));
   		}

   		function syncAllImapFoldersAndContacts($refreshContacts = false, $folders = null, $monthsSince = EMAIL_SYNC_FOR_LAST_MONTHS){
   			try{
   			if(!$folders){
   				$folders = $this->listMailBoxes('*');
   			}
			//first sync inbox
   			print "Syncing for email : ".$this->emailCache->getEmail()."\n";
   			$messageCount = 0;
   			$inboxresult = $this->syncEmails();
   			$unseen = $inboxresult['unseen'];
   			$messageCount = (int) $inboxresult['sync'];
   			$this->cacheLatestUnseenMessages();
   			foreach ($folders as $value) {
   				if (strtolower($value) != 'inbox') {
   					// print "Syncing folder $value\n";
   					$result = $this->syncEmails($value, $monthsSince);
   					$messageCount += $result['sync'];
   				}
   			}
   			$count = 0;
   			if($refreshContacts){
   				// print "Syncing Contacts \n";
   				$count = $this->refreshContacts();
   			}
   			return array("messages"=>$messageCount,"contacts" => $count,"unseen" => $unseen);

   			} catch (Exception $e){
   				print "Sync failed \n";
   			}
   		}   
   		function cacheLatestUnseenMessages($folder = 'INBOX'){
   			$ids = $this->emailCache->getLatestUnseenEmailIds($folder);
   			if(isset($ids)){
				foreach ($ids as $uid) {
					$this->getEmailMessage($uid, $folder,true);
				}
			}
   		}
   		private function sendMessageToUI($userid,$email,$count,$folder) {
   			try{
   				$client = new Client(new Version1X(NODEJS_URL, ['context' => ['ssl' => ['verify_peer_name' =>false, 'verify_peer' => false]]]));
   				$client->initialize();
   				$client->emit('newemailsfolder', array('folder'=>$folder,'userid'=>$userid,'email'=>$email,'date'=>new DateTime(),'count'=>$count));
   				$client->close();
   			} catch(Exception $e) {
   				return;
   			}
   		}

   		function refreshContacts(){
   			return $this->emailCache->refreshContacts();
   		}
		/**
	     * Search a mailbox.
	     *
	     * @param mixed $mailbox                      The mailbox to search.
	     *                                            a string (UTF-8) Default is 'INBOX'.
	     * 
	     * </pre>
	     */
		function syncEmails($mailbox = 'INBOX', $monthsSince = EMAIL_SYNC_FOR_LAST_MONTHS,$ui=true){
			$dateSince = new DateTime();
			$interval = new DateInterval("P".$monthsSince."M");
			$interval->invert = 1;
			$dateSince->add($interval);
			$results = $this->_search($mailbox, array(), $dateSince);
				$ids = array();
				foreach ($results['match'] as $key => $value) {
					array_push($ids, $value);
				}
				$ids = $this->emailCache->expungeAndGetNewIds($mailbox, $ids);
				$count = 0;
				$unseen = 0;
				if(!empty($ids) && count($ids) > 0){
					// print "Total emails to sync :".count($ids)."\n";
					$ids = array_chunk($ids, EMAIL_SYNC_CHUNK_SIZE);
					// print "Number of Chunks to sync : ".count($ids)."\n";
					foreach ($ids as $key => $chunk) {
						// print "Syncing chunk # ".($key+1)."\n";
						$count += (int) $this->fetchMessages($mailbox, $chunk);
						$unseen += $this->emailCache->getUnseenCount($mailbox, $chunk);
					}
				}
				if($ui){
					$this->sendMessageToUI($this->emailCache->getUser(),$this->emailCache->getEmail(),$unseen,$mailbox);
				}
				return array('sync' => $count,'unseen' => $unseen);
			}
			function syncEmailsbyids($idobj,$mailbox='INBOX'){
				try{
					$this->emailCache->expungeMessages($mailbox,$idobj);
					$this->fetchMessages($mailbox, $idobj);
				} catch (Exception $e){
					return;
				}
				return $this->syncEmails($mailbox,EMAIL_SYNC_FOR_LAST_MONTHS,false);
			}

			private function fetchMessages($mailbox, $idChunk){
				$idObj = new Horde_Imap_Client_Ids($idChunk);
				$results = $this->fetch($mailbox, array('ids' => $idObj,
					'exists'=>true));
				$data;
				$temp;

			//var_dump($results);
				foreach ($results as $key => $value) {
					$data = array();
					$temp = $value->getEnvelope();
					$data['uid'] = $key;
					$a1 = $this->getAddressList($temp->bcc);
					$data['bcc'] = $a1['list'];

					$a1 = $this->getAddressList($temp->cc);
					$data['cc'] = $a1['list'];
					$data['cc-csv'] = $a1['encodedCsv'];

					$a1 = $this->getAddressList($temp->from);
					$data['from'] = $a1['list'];
					$data['from-csv'] = $a1['encodedCsv'];

					$data['messageId'] = $temp->message_id;

					$a1 = $this->getAddressList($temp->reply_to);
					$data['replyTo'] = $a1['list'];

					$a1 = $this->getAddressList($temp->sender);
					$data['sender'] = $a1['list'];

					$data['subject'] = $temp->subject;

					$a1 = $this->getAddressList($temp->to);
					$data['to'] = $a1['list'];
					$data['to-csv'] = $a1['encodedCsv'];
					$temp = $value->getHeaders('oxzion', Horde_Imap_Client_Data_Fetch::HEADER_PARSE);
					foreach ($temp->getIterator() as $key1 => $value1) {
						if($key1 == 'Content-Type'){
							$data[$key1] = $value1->value;
						}
					}


					$data["flags"] = $value->getFlags();
					$data["size"] = $value->getSize();
					$data["datetime"] = $value->getImapDate()->format('Y-m-d H:i:s');
					$this->emailCache->updateCache($mailbox, $data);
				}
				return $results->count();
			}

			private function getAddressList($addresses){
				$addList = array();
				$encodedList = "";
				foreach ($addresses as $k1 => $v1) {
					$t = array();
					$t['personal'] = $v1->personal ? $v1->personal : "";
					$t['bare_address'] = $v1->bare_address ? $v1->bare_address : '';
					$t["encoded"] = $v1->encoded ? $v1->encoded : '';
					array_push($addList, $t);
					$encodedList = $encodedList ? "$encodedList, $v1->encoded" : $v1->encoded;	
				}

				return array('list' => $addList, 'encodedCsv' => $encodedList);
			}
		public function count($mailbox = 'INBOX'){
			$dateSince = new DateTime();
			$interval = new DateInterval("P".EMAIL_SYNC_FOR_LAST_MONTHS."M");
			$interval->invert = 1;
			$dateSince->add($interval);
			return $this->_search($mailbox, array('count'=>true),$dateSince, null, true, false,true);
		}
		/**
	     * Search a mailbox.
	     *
	     * @param string $mailbox                      The mailbox to search.
	     *                                            a string (UTF-8) Default is 'INBOX'.
	     * @param array $options                         Additional options:
	     * <pre>
	     *   - partial: (mixed) The range of results to return (message sequence
	     *              numbers) Only a single range is supported (represented by
	     *              the minimum and maximum values contained in the range
	     *              given).
	     *              DEFAULT: All messages are returned.
	     *   - sort: (array) Sort the returned list of messages. Multiple sort
	     *           criteria can be specified. Any sort criteria can be sorted in
	     *           reverse order (instead of the default ascending order) by
	     *           adding a EmailConfig::SORT_REVERSE element to the array
	     *           directly before adding the sort element. The following sort
	     *           criteria are available:
	     *     - EmailConfig::SORT_ARRIVAL
	     *     - EmailConfig::SORT_CC
	     *     - EmailConfig::SORT_DATE
	     *     - EmailConfig::SORT_FROM
	     *     - EmailConfig::SORT_SIZE
	     *     - EmailConfig::SORT_SUBJECT
	     *     - EmailConfig::SORT_TO
	     * @param string $text      The search text.
         * @param string $bodyonly  If true, only search in the body of the
     	 *                          message. If false, also search in the headers.
	     * @param boolean $not      If true, do a 'NOT' search of $text.
	     * </pre>
	     */
		public function search($mailbox = 'INBOX', 
			array $options = array(), $dateTime = null, $text = null, $bodyonly = true, $not = false){
			$ids = array();
			if($text || $dateTime){
				$results = $this->_search($mailbox, $options, $dateTime, $text, $bodyonly, $not);

				foreach ($results['match'] as $key => $value) {
					array_push($ids, $value);
				}
			}

			$sort = $options['sort'] ? $options['sort'] : array();
			return $this->emailCache->getMailList($mailbox, $ids, $sort);
		}
		/**
	     * Search a mailbox.
	     *
	     * @param string $mailbox                      The mailbox to search.
	     *                                            a string (UTF-8) Default is 'INBOX'.
	     * @param array $options                         Additional options:
	     * <pre>
	     *   - partial: (mixed) The range of results to return (message sequence
	     *              numbers) Only a single range is supported (represented by
	     *              the minimum and maximum values contained in the range
	     *              given).
	     *              DEFAULT: All messages are returned.
	     *   - sort: (array) Sort the returned list of messages. Multiple sort
	     *           criteria can be specified. Any sort criteria can be sorted in
	     *           reverse order (instead of the default ascending order) by
	     *           adding a EmailConfig::SORT_REVERSE element to the array
	     *           directly before adding the sort element. The following sort
	     *           criteria are available:
	     *     - EmailConfig::SORT_ARRIVAL
	     *     - EmailConfig::SORT_CC
	     *     - EmailConfig::SORT_DATE
	     *     - EmailConfig::SORT_FROM
	     *     - EmailConfig::SORT_SIZE
	     *     - EmailConfig::SORT_SUBJECT
	     *     - EmailConfig::SORT_TO
	     * @param string $text      The search text.
         * @param string $bodyonly  If true, only search in the body of the
     	 *                          message. If false, also search in the headers.
	     * @param boolean $not      If true, do a 'NOT' search of $text.
	     * </pre>
	     */
		private function _search($mailbox = 'INBOX', 
			array $options = array(), $dateSince = null, $text = null, $bodyonly = true, $not = false,$unseen = false){

			$query = new Horde_Imap_Client_Search_Query();
			if($text){
				$query->text($text, $bodyonly, $not);
			}
			if($dateSince){
				$query->dateSearch($dateSince, Horde_Imap_Client_Search_Query::DATE_SINCE, false);
			}
			if($unseen){
				$query->flag('SEEN',false);
			}
			$query->charset('UTF-8');
			
			if(!isset($options['sort'])) {
				$options['sort'] = array(EmailConfig::SORT_REVERSE,
					EmailConfig::SORT_ARRIVAL);
			}
			$results = $this->client->search($mailbox, $query, $options);
			return $results;
		}

		/**
      	 * Obtain a list of mailboxes matching a pattern.
     	 *
      	 * @param mixed $pattern   The mailbox search pattern(s) (see RFC 3501
      	 *                         [6.3.8] for the format). A UTF-8 string or an
      	 *                         array of strings. 
      	 * @param boolean removeGmailFolders Reset to not remove the GMail specific folders
		 */
		public function listMailBoxes($pattern = '*', $removeGmailFolders = true){
			$data = array_keys($this->client->listMailBoxes($pattern,Horde_Imap_Client::MBOX_SUBSCRIBED_EXISTS));
			$result = array();
			foreach ($data as $value) {
					array_push($result, $value);
			}
			return $result;
		}

			public function __call($method, $params){
				$result = call_user_func_array(array($this->client, $method), $params);

				return $result;
			}


		/**
		 * @return array  An array with keys being the UTF-8 mailbox name
		 *                and values as arrays containing the keys as below
		 * <pre>
	     *     Return key: messages
	     *     Return format: (integer) The number of messages in the mailbox.
	     *
	     *     Return key: recent
	     *     Return format: (integer) The number of messages with the \Recent
	     *                    flag set as currently reported in the mailbox
	     *
	     *     Return key: recent_total
	     *     Return format: (integer) The number of messages with the \Recent
	     *                    flag set. This returns the total number of messages
	     *                    that have been marked as recent in this mailbox
	     *                    since the PHP process began. (since 2.12.0)
	     *     Return key: unseen
	     *     Return format: (integer) The number of messages which do not have
		 *                    the \Seen flag set.
		 *
     	 *
     	 * @throws Horde_Imap_Client_Exception
      	 */
		public function getMailBoxStatus($mailBox){
			$flags = Horde_Imap_Client::STATUS_MESSAGES | 
			Horde_Imap_Client::STATUS_RECENT |
			Horde_Imap_Client::STATUS_RECENT_TOTAL |
			Horde_Imap_Client::STATUS_UNSEEN |
			Horde_Imap_Client::STATUS_FORCE_REFRESH;
			
			$result = $this->client->status($mailBox, $flags);
			return $result;
		}	

		public function getAllMailBoxStatus(){
			$data = $this->listMailBoxes();
			return $this->getMailBoxStatus($data);
		}	

		/**
	     * Store message flag data (see RFC 3501 [6.4.6]).
	     *
	     * @param string $mailbox  The mailbox name (string UTF-8) containing the messages to modify.
	     *
	     * @param array $options  Additional options:
	     *   - add: (array) An array of flags to add.
	     *			such as EmailMessage::FLAG_SEEN to mark the message as seen
	     *          DEFAULT: No flags added.
	     *   - ids: (Horde_Imap_Client_Ids) The list of messages to modify.
	     *          DEFAULT: All messages in $mailbox will be modified.
	     *   - remove: (array) An array of flags to remove.
	     *              such as EmailMessage::FLAG_SEEN to remove the seen flag so it is marked as unseen
	     *				DEFAULT: No flags removed.
	     *   - replace: (array) Replace the current flags with this set
	     *              of flags. Overrides both the 'add' and 'remove' options.
	     *              DEFAULT: No replace is performed.
	     *
	     * @return Horde_Imap_Client_Ids  A Horde_Imap_Client_Ids object
	     *                                containing the list of IDs that failed
	     *                                the 'unchangedsince' test.
	     *
	     * @throws Horde_Imap_Client_Exception
	     * @throws Horde_Imap_Client_Exception_NoSupportExtension
	     */

		function store($mailbox, array $options = array()){
			$result = $this->client->store($mailbox, $options);
			return $result;
		}
		/**
	     * Fetch message data (see RFC 3501 [6.4.5]).
	     *
	     * @param mixed $mailbox                        The mailbox to search.
	     *                                              Either a
	     *                                              Horde_Imap_Client_Mailbox
	     *                                              object or a string (UTF-8).
	     * @param array $options                        Additional options:
	     *   - changedsince: (integer) Only return messages that have a
	     *                   mod-sequence larger than this value. This option
	     *                   requires the CONDSTORE IMAP extension (if not present,
	     *                   this value is ignored). Additionally, the mailbox
	     *                   must support mod-sequences or an exception will be
	     *                   thrown. If valid, this option implicity adds the
	     *                   mod-sequence fetch criteria to the fetch command.
	     *                   DEFAULT: Mod-sequence values are ignored.
	     *   - exists: (boolean) Ensure that all ids returned exist on the server.
	     *             If false, the list of ids returned in the results object
	     *             is not guaranteed to reflect the current state of the
	     *             remote mailbox.
	     *             DEFAULT: false
	     *   - ids: (Horde_Imap_Client_Ids) A list of messages to fetch data from.
	     *          DEFAULT: All messages in $mailbox will be fetched.
	     */
		private function fetch($mbox = 'INBOX', 
			array $options = array('exists' => true)){
			$query = new Horde_Imap_Client_Fetch_Query();
			// This fetches the structure:
			$list = array();
			$query->envelope();
			//$query->structure();
			$query->flags();
			$query->size();
			$query->modseq();
			$query->imapDate();
			$query->uid();
			$headersUsed = array(
				'content-type',
				'importance',
				'list-post',
				'x-priority',
				'resent-date',
				'resent-from'
				);
			$query->headers('oxzion',$headersUsed,array('peek' => true));
			$data = $this->client->fetch($mbox, $query, $options);
			return $data;
		}
		private function getCacheKey($uid, $folder){
			return $this->email."/".$folder."/".$uid;
		}
		/*
		 * Retrieves the email message
		 *		Checks if the emailMessage is already cached and if found cached will load from cache
		 *		Else it fetches and adds to cache
		 *
		 */
		function getEmailMessage($uid, $folder = 'INBOX',$peek=false){
			$cacheKey = $this->getCacheKey($uid, $folder);
			$emailMessage = $this->fileCache->get($cacheKey);
			if($emailMessage){
				return $emailMessage;
			}
			$query = new Horde_Imap_Client_Fetch_Query();
			$query->structure();
			$query->fullText(array('peek' => $peek));
			$options = array('exists' => true,'ids' => new Horde_Imap_Client_Ids($uid));
			$results = $this->client->fetch($folder, $query, $options);
			try{
				if($results[$uid]){
					$structure = $results[$uid]->getStructure();
					print_r($structure);exit;
					$parser = new Parser();
					$parser->setText($results[$uid]->getFullMsg());
					$html = (string) $parser->getMessageBody('html');
					$text = (string) $parser->getMessageBody('text');
					$htmlpurifier = new HTMLPurifier(HTMLPurifier_Config::createDefault());
					 // Filter HTML body to have only safe HTML
        			$html = trim($htmlpurifier->purify($html));
					if ($html == '') {
						$html = nl2br($text);
					}
					$query = new Horde_Imap_Client_Fetch_Query();
					$emailMessage = new EmailMessage($structure,$html);
					$emailMessage->processParts($query,null,1,$peek);
					$partresults = $this->client->fetch($folder, $query, $options);
					if($partresults[$uid]){
						$emailMessage->updateContents($partresults[$uid]);
					}
					$this->fileCache->store($cacheKey, $emailMessage);
				}
			} catch(Exception $e){
				error_log("EmailClient->getEmailMessage : Encountered Exception =>".$e->getMessage());
			}
			return $emailMessage;
		}
		/**
		 * $ids - one or more uids of messages that has to be expunged
		 */
		function expungeMessages($ids, $folder='INBOX'){
			$idsOb = $this->client->getIdsOb($ids);
			$options = array('delete' => true,'ids' => $idsOb,'list' => true);
			$results = $this->client->expunge($folder, $options);
			$this->emailCache->expungeMessages($folder, $idsOb->ids);
			return $idsOb->ids;
		}

		/**
	     * Builds and sends a MIME message.
	     *
	     * @param string $body                  The message body.
	     * @param array attDetails			    array of items with following properties
	     * 	- file :  Temporary file containing attachment contents
	     *  - bytes : Size of data, in bytes.
	     *  - filename : Filename of data
	     *  - type : Mime type of data 
	     * @param array $header                 List of message headers.
	     * @param array $smtpConfig				Config values for smtp
	     *     - host: [*] (string) SMTP server host.
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
		 *     - username: (string) Username to use for SMTP server authentication.
		 *		- xoauth2_token: (string) If set, will authenticate via the XOAUTH2
		 * @param array $opts                   An array of options w/the
	     *                                      following keys:
	     *  - html: (boolean) Whether this is an HTML message.
	     *          DEFAULT: false
	     *  - priority: (string) The message priority ('high', 'normal', 'low').
	     *  - save_sent: (boolean) Save sent mail? DEFAULT true
	     *  - sent_mail: The sent-mail mailbox name (UTF-8). DEFAULT 'sent'
	     *  - strip_attachments: (bool) Strip attachments from the message?
	     *  - useragent: (string) The User-Agent string to use.
     	 *
	     * @throws Horde_Exception
	     * @throws MailException
	     */
		public function buildAndSendMessage($body, $attDetails, $header, $smtpConfig, array $opts = array(), $draftid=null)
		{
			/* Set up defaults. */
			$opts = array_merge(array(
				'save_sent' => true,
				'sent_mail' => 'sent'
			), $opts);
			/* We need at least one recipient. */
			$recip = $this->recipientList($header);
			if (!count($recip['list'])) {
				if ($recip['has_input']) {
					throw new MailException("Invalid e-mail address.");
				}
				throw new MailException("Need at least one message recipient.");
			}

			/* Initalize a header object for the outgoing message. */
			$headers = $this->_prepareHeaders($header, $opts);

			/* Add the 'User-Agent' header. */
			$headers->addHeaderOb(new Horde_Mime_Headers_UserAgent(
				null,
				empty($opts['useragent'])
				? 'Oxzion Email Client'
				: $opts['useragent']
			));

			$message = $this->_createMimeMessage($body, $attDetails, array(
				'html' => !empty($opts['html']),
				'recip' => $recip['list'],
			));

			/* Send the messages out now. */
			$this->sendMessage($recip['list'], $headers, $message, $smtpConfig);
			if($draftid){
				$this->expungeMessages(new Horde_Imap_Client_Ids($draftid),$this->getSpecialFolders('drafts'));
			}
			/* Save message to the sent mail mailbox. */
			$this->_saveToSentMail($headers, $message, $recip['list'], $opts);
		}

	    /**
	     * Save message to sent-mail mailbox, if configured to do so.
	     *
	     * @param Horde_Mime_Headers $headers     Headers object.
	     * @param Horde_Mime_Part $save_msg       Message data to save.
	     * @param Horde_Mail_Rfc822_List $recips  Recipient list.
	     * @param array $opts                     See buildAndSendMessage()
	     */
	    protected function _saveToSentMail(
	    	Horde_Mime_Headers $headers,
	    	Horde_Mime_Part $save_msg,
	    	Horde_Mail_Rfc822_List $recips,
	    	$opts
	    	)
	    {	
	    	if (empty($opts['sent_mail']) ||
	    		empty($opts['save_sent'])) {
	    		return;
	    }

	    /* Strip attachments if requested. */
	    if (!empty($opts['strip_attachments'])) {
	    	$save_msg->buildMimeIds();

	            /* Don't strip any part if this is a text message with both
	             * plaintext and HTML representation, or a signed or encrypted
	             * message. */
	            if ($save_msg->getType() != 'multipart/alternative' &&
	            	$save_msg->getType() != 'multipart/encrypted' &&
	            	$save_msg->getType() != 'multipart/signed') {
	            	for ($i = 2; ; ++$i) {
	            		if (!($oldPart = $save_msg[$i])) {
	            			break;
	            		}

	            		$replace_part = new Horde_Mime_Part();
	            		$replace_part->setType('text/plain');
	            		$replace_part->setCharset('utf-8');
	            		$replace_part->setContents('[' . _("Attachment stripped: Original attachment type") . ': "' . $oldPart->getType() . '", ' . _("name") . ': "' . $oldPart->getName(true) . '"]');
	            		$save_msg[$i] = $replace_part;
	            	}
	            }
	        }
	        /* Generate the message string. */
	        $fcc = $save_msg->toString(array(
	        	'headers' => $headers,
	        	'stream' => false
	        	));

	        $flags = array(
	        	Horde_Imap_Client::FLAG_SEEN
	        	/* RFC 3503 [3.3] - MUST set MDNSent flag on sent message. */
	            //Horde_Imap_Client::FLAG_MDNSENT
	        	);
	        
	        try {
	        	$this->client->append($opts['sent_mail'], array(array('data' => $fcc, 'flags' => $flags)));
	        } catch (Horde_Imap_Client_Exception $e) {
	        	throw new MailException(sprintf(_("Message sent successfully, but not saved to %s."), $opts['sent_mail']));
	        }
	    }
	    private function getOAuth64($email, $accessToken){
			return base64_encode("user=".$email."\001auth=Bearer ".$accessToken. "\001\001");
		}

	    public function smtpLogin($smtpConfig){
	    	$transport = $this->getsmtpTransport($smtpConfig);
	    	try {
	    		$object = $transport->getSMTPObject();
	    	} catch (Horde_Smtp_Exception $e) {
	    		throw new MailException(sprintf(_("Message sent successfully, but not saved to %s."), $opts['sent_mail']));
	    	}
	    	return $object;
	    }
	    private function getsmtpTransport($smtpConfig){
			if(isset($smtpConfig['xoauth2_token'])){
				$smtpConfig['xoauth2_token'] = $this->getOAuth64($smtpConfig['username'], $smtpConfig['xoauth2_token']);
			}
			$transport = new Horde_Mail_Transport_Smtphorde($smtpConfig);
			return $transport;
		}


		/**
	     * Sends a message.
	     *
	     * @param Horde_Mail_Rfc822_List $email  The e-mail list to send to.
	     * @param Horde_Mime_Headers $headers    The object holding this message's
	     *                                       headers.
	     * @param Horde_Mime_Part $message       The object that contains the text
	     *                                       to send.
	     * @param array $smtpConfig				Config values for smtp
	     *     - host: [*] (string) SMTP server host.
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
		 *     - username: (string) Username to use for SMTP server authentication.
		 *
	     * @throws MailException
	     */
		public function sendMessage(Horde_Mail_Rfc822_List $email,
			Horde_Mime_Headers $headers,
			Horde_Mime_Part $message, $smtpConfig)
		{
			$smtpConfig = array_merge(array('timeout' => self::TIMEOUT,
				'debug' => '../smtp.log'), 
			$smtpConfig);
	    	/* Fallback to UTF-8 (if replying, original message might be in
	         * US-ASCII, for example, but To/Subject/Etc. may contain 8-bit
	         * characters. */
	    		$message->setHeaderCharset('UTF-8');
	    		/* Remove Bcc header if it exists. */
	    		if (isset($headers['bcc'])) {
	    			$headers = clone $headers;
	    			unset($headers['bcc']);
	    		}

	    		try {
	    			$transport = $this->getsmtpTransport($smtpConfig);
	    			$message->send($email, $headers, $transport);
	    		} catch (Horde_Mime_Exception $e) {
	    			throw new MailException($e);
	    		}
	    	}
		/**
	     * Create the base Horde_Mime_Part for sending.
	     *
	     * @param string $body                Message body.
	     * @param array attDetails			
	     * 	- file :  Temporary file containing attachment contents
	     *  - bytes : Size of data, in bytes.
	     *  - filename : Filename of data
	     *  - type : Mime type of data 
	     * @param array $options              Additional options:
	     *   - html: (boolean) Is this a HTML message?
	     *   - noattach: (boolean) Don't add attachment information.
	     *   - recip: (Horde_Mail_Rfc822_List) The recipient list.
	     *
	     * @return Horde_Mime_Part  The base MIME part.
	     *
	     * @throws Horde_Exception
	     * @throws MailException
	     */
		protected function _createMimeMessage($body, $attDetails, array $options = array())
		{
			/* Get body text. */
			if (empty($options['html'])) {
				$body_html = null;
			} else {
				$tfilter = new Horde_Core_Factory_TextFilter(new Horde_Injector(new Horde_Injector_TopLevel()));

					$body_html = $tfilter->filter(
						$body,
						'Xss',
						array(
							'return_dom' => true,
							'strip_style_attributes' => false
							)
						);
					$body_html_body = $body_html->getBody();

					$body = $tfilter->filter(
						$body_html->returnHtml(),
						'Html2text',
						array(
							'width' => 0
							)
						);
				}

				/* Set up the body part now. */
				$textBody = new Horde_Mime_Part();
				$textBody->setType('text/plain');
				$textBody->setCharset('utf-8');
				$textBody->setDisposition('inline');

				/* Send in flowed format. */
				$flowed = new Horde_Text_Flowed($body, 'utf-8');
				$flowed->setDelSp(true);
				$textBody->setContentTypeParameter('format', 'flowed');
				$textBody->setContentTypeParameter('DelSp', 'Yes');
				$text_contents = $flowed->toFlowed();
				$textBody->setContents($text_contents);

	        /* Determine whether or not to send a multipart/alternative
	         * message with an HTML part. */
	        if (!empty($options['html'])) {
	        	$htmlBody = new Horde_Mime_Part();
	        	$htmlBody->setType('text/html');
	        	$htmlBody->setCharset('utf-8');
	        	$htmlBody->setDisposition('inline');
	        	$htmlBody->setDescription("HTML Message");
	        	$this->_cleanHtmlOutput($body_html);
	        	$to_add = $htmlBody;
	            /* Now, all parts referred to in the HTML data have been added
	             * to the attachment list. Convert to multipart/related if
	             * this is the case. Exception: if text representation is empty,
	             * just send HTML part. */
	            if (strlen(trim($text_contents))) {
	            	$textpart = new Horde_Mime_Part();
	            	$textpart->setType('multipart/alternative');
	            	$textpart[] = $textBody;
	            	$textpart[] = $to_add;
	            	$textpart->setHeaderCharset('utf-8');
	            	$textBody->setDescription("Plaintext Message");
	            } else {
	            	$textpart = $to_add;
	            }

	            $htmlBody->setContents(
	            	$tfilter->filter(
	            		$body_html->returnHtml(array(
	            			'charset' => 'utf-8',
	            			'metacharset' => true
	            			)),
	            		'Cleanhtml',
	            		array(
	            			'charset' => 'utf-8'
	            			)
	            			)
	            		);
	            	$base = $textpart;
	            }else {
	            	$base = $textpart = strlen(trim($text_contents))
	            	? $textBody
	            	: null;
	            }

	            /* Add attachments. */
	            $aparts = array();

	            foreach ($attDetails as $key => $value) {
	            	$type = isset($value['type']) ? $value['type'] : null;
	            	$aparts[] = $this->_getAttachmentPart($value['file'], $value['bytes'], $value['filename'], $type);
	            }

	            if (!empty($aparts)) {
	            	if (is_null($base) && (count($aparts) === 1)) {
                    /* If this is a single attachment with no text, the
                     * attachment IS the message. */
                    $base = reset($aparts);
                } else {
                	$base = new Horde_Mime_Part();
                	$base->setType('multipart/mixed');
                	if (!is_null($textpart)) {
                		$base[] = $textpart;
                	}
                	foreach ($aparts as $val) {
                		$base[] = $val;
                	}
                }
            }

            /* If we reach this far with no base, we are sending a blank message.
	         * Assume this is what the user wants. */
            if (is_null($base)) {
            	$base = $textBody;
            }

            /* Flag this as the base part and rebuild MIME IDs. */
            $base->isBasePart(true);
            $base->buildMimeIds();

            return $base;
        }

	    /**
	     * Clean outgoing HTML (remove unexpected data URLs).
	     *
	     * @param Horde_Domhtml $html  The HTML data.
	     */
	    protected function _cleanHtmlOutput(Horde_Domhtml $html)
	    {
	    	global $registry;

	    	$xpath = new DOMXPath($html->dom);

	    	foreach ($xpath->query('//*[@src]') as $node) {
	    		$src = $node->getAttribute('src');

	            /* Check for attempts to sneak data URL information into the
	             * output. */
	            if (Horde_Url_Data::isData($src)) {
	            	$node->removeAttribute('src');
	            } 
	        }
	    }

	    /**
	     * Adds an attachment to the outgoing compose message.
	     *
	     * @param string $atc_file  Temporary file containing attachment contents.
	     * @param integer $bytes    Size of data, in bytes.
	     * @param string $filename  Filename of data.
	     * @param string $type      MIME type of data.
	     *
	     * @return Horde_Mime_Part  Attachment object.
	     * @throws MailException
	     */
	    protected function _getAttachmentPart($atc_file, $bytes, $filename, $type)
	    {
	    	$apart = new Horde_Mime_Part();
	    	$apart->setBytes($bytes);
	    	if (strlen($filename)) {
	    		$apart->setName($filename);
	    		if ($type == 'application/octet-stream') {
	    			$type = Horde_Mime_Magic::filenameToMIME($filename, false);
	    		}
	    	}
	    	$apart->setType($type);
	    	if (($apart->getType() == 'application/octet-stream') ||
	    		($apart->getPrimaryType() == 'text')) {
	$analyze = Horde_Mime_Magic::analyzeFile($atc_file, null, array(
		'nostrip' => true
		));
	$apart->setCharset('UTF-8');

	if ($analyze) {
		$ctype = new Horde_Mime_Headers_ContentParam(
			'Content-Type',
			$analyze
			);
		$apart->setType($ctype->value);
		if (isset($ctype->params['charset'])) {
			$apart->setCharset($ctype->params['charset']);
		}
	}
} else {
	$apart->setHeaderCharset('UTF-8');
}

$apart->setContents(fopen($atc_file, 'r'), array('stream' => true));
return $apart;
}
		/**
	     * Cleans up and returns the recipient list. Method designed to parse
	     * user entered data; does not encode/validate addresses.
	     *
	     * @param array $hdr  An array of MIME headers and/or address list
	     *                    objects. Recipients will be extracted from the 'to',
	     *                    'cc', and 'bcc' entries.
	     *
	     * @return array  An array with the following entries:
	     *   - has_input: (boolean) True if at least one of the headers contains
	     *                user input.
	     *   - header: (array) Contains the cleaned up 'to', 'cc', and 'bcc'
	     *             address list (Horde_Mail_Rfc822_List objects).
	     *   - list: (Horde_Mail_Rfc822_List) Recipient addresses.
	     */
		public function recipientList($hdr)
		{
			$addrlist = new Horde_Mail_Rfc822_List();
			$has_input = false;
			$header = array();

			foreach (array('to', 'cc', 'bcc') as $key) {
	if (isset($hdr[$key])) {
		$ob = EmailUtils::parseAddressList($hdr[$key]);
		if (count($ob)) {
			$addrlist->add($ob);
			$header[$key] = $ob;
			$has_input = true;
		} else {
			$header[$key] = null;
		}
	}
}

return array(
	'has_input' => $has_input,
	'header' => $header,
	'list' => $addrlist
	);


}

	    /**
	     * Prepare header object with basic header fields and converts headers
	     * to the current compose charset.
	     *
	     * @param array $headers  Array with 'from', 'to', 'cc', 'bcc', and
	     *                        'subject' values.
	     * @param array $opts     An array of options w/the following keys:
	     *   - priority: (string) The message priority ('high', 'normal', 'low').
	     *
	     * @return Horde_Mime_Headers  Headers object with the appropriate headers
	     *                             set.
	     */
	    protected function _prepareHeaders($headers, array $opts = array())
	    {
	    	$ob = new Horde_Mime_Headers();

	    	$ob->addHeaderOb(Horde_Mime_Headers_Date::create());
	    	$ob->addHeaderOb(Horde_Mime_Headers_MessageId::create());

	    	$hdrs = array(
	    		'From' => 'from',
	    		'To' => 'to',
	    		'Cc' => 'cc',
	    		'Bcc' => 'bcc',
	    		'Subject' => 'subject'
	    		);

	    	foreach ($hdrs as $key => $val) {
	    		if (isset($headers[$val]) &&
	    			(is_object($headers[$val]) || strlen($headers[$val]))) {
	$ob->addHeader($key, $headers[$val]);
}
}
$from = $ob['from']->getAddressList(true)->first();
if (is_null($from->host)) {
	throw new MailException("From Address is Invalid");
}
	        /* Add Reply-To header. Done after pre_sent hook since from address
	         * could be change by hook and/or Reply-To was set by hook. */
	        if (!empty($headers['replyto']) &&
	        	($headers['replyto'] != $from->bare_address) &&
	        	!isset($ob['reply-to'])) {
	        	$ob->addHeader('Reply-To', $headers['replyto']);
	    }
	    /* Add priority header, if requested. */
	    if (!empty($opts['priority'])) {
	    	switch ($opts['priority']) {
	    		case 'high':
	    		$ob->addHeader('Importance', 'High');
	    		$ob->addHeader('X-Priority', '1 (Highest)');
	    		break;

	    		case 'low':
	    		$ob->addHeader('Importance', 'Low');
	    		$ob->addHeader('X-Priority', '5 (Lowest)');
	    		break;
	    	}
	    }

	    return $ob;
	}
	 /**
     * Move emails from one folder to another.
     *
     * @param int[] $ids
     */
	 public function moveEmails($ids, $from, $to){
    	$trashfolder = $this->getSpecialFolders('trash');
    	$sentfolder = $this->getSpecialFolders('sent');
    	$draftfolder = $this->getSpecialFolders('drafts');
    	$this->client->copy($from, $to, ['ids' => new Horde_Imap_Client_Ids($ids),'move' => true]);
	 	return "Mail has been moved successfully!";
	 }
    /**
     * Delete emails by moving them to the trash folder.
     *
     * @param int[] $ids
     * @param string $trashFolder Trash folder. There is no standard default, it can be 'Deleted Messages', 'Trash'…
     * @param string $fromFolder Folder from which the email Ids come from.
     */
    public function deleteEmails($ids, $fromFolder = 'INBOX'){
    	$trashfolder = $this->getSpecialFolders('trash');
    	if($fromFolder==$trashfolder){
    		return $this->expungeMessages(new Horde_Imap_Client_Ids($ids), $fromFolder);
    	} else {
    		return $this->moveEmails(new Horde_Imap_Client_Ids($ids), $fromFolder, $this->getSpecialFolders('trash'));
    	}
    }
    public function getSpecialFolders($type){
    	if($type=='trash'){
    		$searchfolder = Horde_Imap_Client::SPECIALUSE_TRASH;
    	} else if($type=='sent'){
    		$searchfolder = Horde_Imap_Client::SPECIALUSE_SENT;
    	} else if ($type=='drafts'){
    		$searchfolder = Horde_Imap_Client::SPECIALUSE_DRAFTS;
    	} else {
    		$searchfolder = Horde_Imap_Client::SPECIALUSE_ALL;
    	}
    	$folders = $this->client->listMailBoxes("*",Horde_Imap_Client::MBOX_SUBSCRIBED_EXISTS, array('special_use' => 1,'extended'=>true,'attributes'=>true,'children'=>true,'recursivematch'=>true,'remote'=>true));
    	foreach ($folders as $key => $value) {
    		foreach ($value['attributes'] as $k => $v) {
    			if(strpos(strtolower($v), strtolower($searchfolder))>-1){
    				$specialfolder = $key;
    			}
    		}
    		if(strpos(strtolower($value['mailbox']->__get('list_escape')), strtolower('Inbox'.$value['delimiter'].str_replace("\\","",$searchfolder)))>-1){
    			$specialfolder = $key;
    		}
    	}
    	return $specialfolder;
    }

		/**
	     * Builds and sends a MIME message.
	     *
	     * @param string $body                  The message body.
	     * @param array attDetails			    array of items with following properties
	     * 	- file :  Temporary file containing attachment contents
	     *  - bytes : Size of data, in bytes.
	     *  - filename : Filename of data
	     *  - type : Mime type of data 
	     * @param array $header                 List of message headers.
	     * @param array $smtpConfig				Config values for smtp
	     *     - host: [*] (string) SMTP server host.
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
		 *     - username: (string) Username to use for SMTP server authentication.
		 *		- xoauth2_token: (string) If set, will authenticate via the XOAUTH2
		 * @param array $opts                   An array of options w/the
	     *                                      following keys:
	     *  - html: (boolean) Whether this is an HTML message.
	     *          DEFAULT: false
	     *  - priority: (string) The message priority ('high', 'normal', 'low').
	     *  - save_sent: (boolean) Save sent mail? DEFAULT true
	     *  - sent_mail: The sent-mail mailbox name (UTF-8). DEFAULT 'sent'
	     *  - strip_attachments: (bool) Strip attachments from the message?
	     *  - useragent: (string) The User-Agent string to use.
     	 *
	     * @throws Horde_Exception
	     * @throws MailException
	     */
		public function buildDraftMessage($body, $attDetails, $header, array $opts = array())
		{

			/* We need at least one recipient. */
			$recip = $this->recipientList($header);
			if (!count($recip['list'])) {
				if ($recip['has_input']) {
					throw new MailException("Invalid e-mail address.");
				}
				throw new MailException("Need at least one message recipient.");
			}
			/* Initalize a header object for the outgoing message. */
			$headers = $this->_prepareHeaders($header, $opts);
			/* Add the 'User-Agent' header. */
			$headers->addHeaderOb(new Horde_Mime_Headers_UserAgent(null,empty($opts['useragent'])? 'Oxzion Email Client': $opts['useragent']));
			$message = $this->_createMimeMessage($body, $attDetails, array('html' => !empty($body),'recip' => $recip['list']));
			/* Save message to the draft mail mailbox. */
			$this->_saveToDraftMail($headers, $message, $recip['list'], $opts);
		}

	    /**
	     * Save message to sent-mail mailbox, if configured to do so.
	     *
	     * @param Horde_Mime_Headers $headers     Headers object.
	     * @param Horde_Mime_Part $save_msg       Message data to save.
	     * @param Horde_Mail_Rfc822_List $recips  Recipient list.
	     * @param array $opts                     See buildAndSendMessage()
	     */
	    protected function _saveToDraftMail(Horde_Mime_Headers $headers,Horde_Mime_Part $save_msg,Horde_Mail_Rfc822_List $recips,$opts) {	
	    /* Strip attachments if requested. */
	    if (!empty($opts['strip_attachments'])) {
	    	$save_msg->buildMimeIds();
	            /* Don't strip any part if this is a text message with both
	             * plaintext and HTML representation, or a signed or encrypted
	             * message. */
	            if ($save_msg->getType() != 'multipart/alternative' &&$save_msg->getType() != 'multipart/encrypted' &&$save_msg->getType() != 'multipart/signed') {
	            	for ($i = 2; ; ++$i) {
	            		if (!($oldPart = $save_msg[$i])) {
	            			break;
	            		}
	            		$replace_part = new Horde_Mime_Part();
	            		$replace_part->setType('text/plain');
	            		$replace_part->setCharset('utf-8');
	            		$replace_part->setContents('[' . _("Attachment stripped: Original attachment type") . ': "' . $oldPart->getType() . '", ' . _("name") . ': "' . $oldPart->getName(true) . '"]');
	            		$save_msg[$i] = $replace_part;
	            	}
	            }
	        }
	        /* Generate the message string. */
	        $fcc = $save_msg->toString(array('headers' => $headers,'stream' => false));
	        $flags = array(Horde_Imap_Client::FLAG_DRAFT);
	        try {
	        	if(!$opts['id']){
	        		$this->client->append($this->getSpecialFolders('drafts'), array(array('data' => $fcc, 'flags' => $flags)));
	        	} else {
	        		$this->expungeMessages(new Horde_Imap_Client_Ids($opts['id']),$this->getSpecialFolders('drafts'));

	        		$this->client->append($this->getSpecialFolders('drafts'), array(array('data' => $fcc, 'flags' => $flags)));

	        	}
	        } catch (Horde_Imap_Client_Exception $e) {
	        	throw new MailException(sprintf(_("Message sent successfully, but not saved to %s."), $opts['sent_mail']));
	       }
	    }
}
?>