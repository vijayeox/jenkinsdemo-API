<?php 
namespace Oxzion;
require_once __DIR__.'/../Common/Config.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;
use Exception;
use Job\Job;
Class Notification{
		private $dao;
		private $client;
	function __construct(){
		$this->dao = new Dao();
		$this->client = new Client(new Version1X(NODEJS_URL, ['context' => ['ssl' => ['verify_peer_name' =>false, 'verify_peer' => false]]]));	
	 	$this->client->initialize();
	}
	public function socketEmit($event,$data){
		$client = new Client(new Version1X(NODEJS_URL, ['context' => ['ssl' => ['verify_peer_name' =>false, 'verify_peer' => false]]]));
		$client->initialize();
		$client->emit($event,$data);
	 	$client->close();
	}
	public function sendFcmNotification($userid,$params,$notification){
		$sql = "SELECT gcm_registration_id from google_gcm AS g where avatarid = ".$userid;
		if(!$result = $this->dao->execQuery($sql)){
			return;
		}
		while ($row = $result->fetch_assoc()) {
			$fields = array('to' => $row['gcm_registration_id'], 'data' => $params,'notification'=>$notification);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: key=AAAA62B7eBY:APA91bFIbIc5cTHgbXRrlutg4ZSWrq29g91Zxbf7s7eq5Qyk1Xxu7xLl6W8-tSKMgYmstFerCbH4VX_Qkl3O9YcWAQPwUd_bdemskXjE7xhe78AUnKVeIHfBZrWmoZzPHcrLjzmkRzTL', 'Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$res = curl_exec($ch);
			if ($res === FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}
			curl_close($ch);
		}
	}
}

?>