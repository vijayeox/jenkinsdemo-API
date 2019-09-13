<?php
namespace Oxzion\Messaging;

use Oxzion\Messaging\Payload;

class GenericPayload {
	protected $data = array();
	protected $payload;
	protected $topic;

	public function __construct($data,$topic){
		$this->topic = $topic;
		$this->payload = new Payload($this->data);
	}
	public function send(){
		$producer = MessageProducer::getInstance();
		$producer->sendTopic($this->payload->get(),$this->topic);
	}
}
?>