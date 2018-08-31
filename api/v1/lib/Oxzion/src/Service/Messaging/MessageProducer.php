<?php
	namespace Messaging;
	
	include_once __DIR__.'/mqConnection.php';
	use PhpAmqpLib\Connection\AMQPStreamConnection;
	use PhpAmqpLib\Message\AMQPMessage;

	class MessageProducer{
		private static $instance = null;
		private $mqConnection; 
		private $channel;
		private function __construct($serverAddress){
			$this->mqConnection = new AMQPStreamConnection('localhost', MQ_PORT, MQ_USER, MQ_PASSWORD);
			$this->channel = $this->mqConnection->channel();
			register_shutdown_function('mqCleanup', $this->channel, $this->mqConnection);
		}

		public static function getInstance($type=0){
			if (!isset(static::$instance))
	        {
	        	if($type){
	            	self::$instance = new MessageProducer(SOLR_SERVER_NAME);
	        	}else{
	            	self::$instance = new MessageProducer(SERVER_NAME);
        	    }

	        }
	        return static::$instance;
		}

		public function waitForPendingAcks(){
			$this->channel->wait_for_pending_acks();
		}

		public function sendMessage($message, $queue){
			$this->channel->queue_declare($queue, false, true, false, false);
			//$this->channel->exchange_declare(EXCHANGE, EXCHANGE_TYPE, false, true, false);
			//$this->channel->queue_bind($queue, EXCHANGE);
			$msgBody = json_encode($message);
			//print("Sending Message to queue $queue with payload : $msgBody");
			
			$msg = new AMQPMessage($msgBody, array('content_type' => 'text/plain', 'delivery_mode' => 2));
			//$this->channel->basic_publish($msg, EXCHANGE, $queue);
			$this->channel->basic_publish($msg, '', $queue);
			
		}
	}
?>