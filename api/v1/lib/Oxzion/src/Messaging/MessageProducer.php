<?php
namespace Oxzion\Messaging;

class MessageProducer{
	private static $instance = null;
	private $client;
	private function __construct(){
		$this->client = new CLient();
	}

	public static function getInstance(){
		if (!isset(static::$instance)) {
			self::$instance = new MessageProducer();
		}
		return static::$instance;
	}

	public function sendTopic($message, $topic){
		$this->client->sendMessage('/topic/'.$topic, $message);
	}
	public function sendQueue($message, $queue){
		$this->client->sendMessage('/queue/'.$queue, $message);
	}
}
?>