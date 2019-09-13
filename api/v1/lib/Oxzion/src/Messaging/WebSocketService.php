<?php
namespace Oxzion\Messaging;

class WebSocket extends Payload {
	protected $data = array(
		'user_id'=>0 ,
		'app_id' => NULL,
		'icon' => NULL,
		'event' => NULL,
		'body' => NULL
    );
    protected $payload;
    protected $topic = Constants::WEBSOCKET;

	public function __construct($data){
		if(isset($data['user_id'])){
			$this->__set('user_id',$data['user_id']);
		}
		if(isset($data['app_id'])){
			$this->__set('app_id',$data['app_id']);
		}
		if(isset($data['icon'])){
			$this->__set('icon',$data['icon']);
		}
		if(isset($data['event'])){
			$this->__set('event',$data['event']);
		}
		if(isset($data['body'])){
			$this->__set('body',$data['body']);
		}
		$this->payload = new Payload($this->data);
	}
}
?>