<?php
require __DIR__ .'/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

class VA_ExternalLogic_SocketService {
	private $client;
	public function __construct(){
		try{
			$this->client = new Client(new Version1X(NODEJS_URL, ['context' => ['ssl' => ['verify_peer_name' =>false, 'verify_peer' => false]]]));
		} catch(Exception $e) {
			return;
		}
	}
	public function emit($event,$data){
		$this->client->initialize();
		$this->client->emit($event, $data);
		$this->client->close();
	}
}
?>