<?php
	namespace Oxzion;

	include_once __DIR__.'/../Common/Config.php';
	
	use mysqli;
	use Exception;

ini_set('memory_limit', -1);
	class Dao{
		private $mysqli;
		
		public function __construct(){
			$this->createConnection();
		}
		private function createConnection(){
			try{

			$this->mysqli = new mysqli(SERVER_NAME, USERNAME, PASSWORD, DBNAME);
			if ($this->mysqli->connect_errno) {
			    error_log("Error connecting to database ".SERVER_NAME.".".DBNAME);

			    error_log("Errno: " . $this->mysqli->connect_errno);
			    error_log("Error: " . $this->mysqli->connect_error);
			    throw new Exception("Connection to Database Failed with error - ".$this->mysqli->connect_error);
			}

			if (!$this->mysqli->set_charset("utf8")) {
			    printf("Error loading character set utf8: %s\n", $this->mysqli->error);
			    throw new Exception("Not able to set the character set to utf8 - ".$this->mysqli->error);
			} else {
			    //printf("Current character set: %s\n", $this->mysqli->character_set_name());
			}
			} catch(Exception $e){
				printf("Error Connecting to databas", $this->mysqli->error);
			}
		}
		private function checkConnection(){
			if (!$this->mysqli->ping()) {
				while(!$this->mysqli || !$this->mysqli->ping()){
					try{
						$this->createConnection();
					}catch(Exception $e){
						print "Wait for 10s and try again\n";
						sleep(10);
					}
				}
				
			}
		}
		public function escapeString($str){
			return $this->mysqli->real_escape_string($str);
		}
		public function extractMap($data, $separator = '|'){
			$map = array();
			if(isset($data)){
				$val = explode($separator, $data);
				array_walk($val, function($a, $key) use(&$map){
									$ele = explode('=>', $a);
									if(array_key_exists(1, $ele)){
										$map[$ele[0]] = $ele[1];
									}
								});
			}
			return $map;
		}

		public function execUpdate($sql){
			//print($sql."\n");
			$this->checkConnection();
			if (!$this->mysqli->query($sql)) {
				error_log("Error: query failed to execute and here is why:");
			    error_log("Query: " . $sql);
			    error_log("Errno: " . $this->mysqli->errno);
			    error_log("Error: " . $this->mysqli->error);
			    //TODO  need to update the status in the DB
			    return false;
			}		

			return true;
		}

		public function execQuery($sql){
			//print($sql."\n");
			$this->checkConnection();
			
			if (!$result = $this->mysqli->query($sql)) {
				error_log("Error: query failed to execute and here is why:");
			    error_log("Query: " . $sql);
			    error_log("Errno: " . $this->mysqli->errno);
			    error_log("Error: " . $this->mysqli->error);
			    //TODO  need to update the status in the DB
			    return;
			}		

			if ($result->num_rows === 0) {
				//error_log("No Records found!");
				return;
			}

			return $result;
		}

		public function close(){
			if(isset($this->mysqli)){
				$this->mysqli->close();
			}
		}
	}
?>