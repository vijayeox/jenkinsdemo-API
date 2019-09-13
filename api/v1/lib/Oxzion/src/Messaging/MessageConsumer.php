<?php
namespace Oxzion\Messaging;

class MessageConsumer{
	private static $instance = null;
	private $client;
	private $subscription;
	private function __construct(){
		$this->client = new Client();
	}

	public static function getInstance(){
		if (!isset(static::$instance)) {
			self::$instance = new MessageConsumer();
		}
		return static::$instance;
	}
	public function subscribe($destination,$subscriptionId = null,$ack = 'auto',$selector = null,$durable = false){
		$this->subscription = $subscription;
		$this->client->subscribe($destination,$subscriptionId,$ack,$selector,$durable);
	}
	public function sendFrame($frame, $sync = null){
		return $this->client->sendFrame($frame, $sync);
	}

	public function consumeMessage(){
		return $this->client->listen();
	}
}
?>