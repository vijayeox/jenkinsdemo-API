<?php
namespace Oxzion\Messaging;

class Payload{
	protected $data = array();
	
	public function __construct($data){
		$this->data= $data;
	}
	public function get(){
		return json_encode($this->data);
	}
}
?>