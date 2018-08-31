<?php
	namespace Oxzion;

	use Email\EmailClient;

	class EmailClientCache{
		private static $emailClients;
		
		public static function setupEmailClient($userid, $email, $username, $password, $host, $port, $secure, $authToken = NULL){
			if (!isset(static::$emailClients))
	        {
	            self::$emailClients = array();
        	}

        	if(!isset(static::$emailClients[$userid])){

        		static::$emailClients[$userid] = array();
        	}
			
			if(!isset(static::$emailClients[$userid][$email])){
				$client =new EmailClient($userid, $email, $username, $password, $host, $port, $secure, $authToken);
				static::$emailClients[$userid][$email] = $client;
			}

			return static::$emailClients[$userid][$email];

		}

		public static function getEmailClient($userid, $email){
			if (!isset(static::$emailClients))
	        {
	            self::$emailClients = array();
        	}

        	if(!isset(static::$emailClients[$userid])){

        		static::$emailClients[$userid] = array();
        	}
			
			if(!isset(static::$emailClients[$userid][$email])){
				return null;
			}

	        return static::$emailClients[$userid][$email];
		}

		public static function removeEmailClientsForUser($userid){
			if(!isset(static::$emailClients[$userid])){
				return;
			}
			/*foreach (static::$emailClients[$userid] as $email => $client) {
				$client->logout();
			}*/
			unset(static::$emailClients[$userid]);
		}
	}
?>