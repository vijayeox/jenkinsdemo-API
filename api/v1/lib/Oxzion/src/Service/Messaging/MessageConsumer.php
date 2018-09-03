<?php
	namespace Messaging;

	include_once __DIR__.'/mqConnection.php';
	use PhpAmqpLib\Connection\AMQPStreamConnection;
			
	class MessageConsumer{
		private static $instance = null;
		private $mqConnection; 
		private $channel;
		private $performWait;
		public function __construct($wait){
			$this->mqConnection = new AMQPStreamConnection("localhost", MQ_PORT, MQ_USER, MQ_PASSWORD);
			$this->channel = $this->mqConnection->channel();
			$this->performWait = False;
			register_shutdown_function('mqCleanup', $this->channel, $this->mqConnection);
		}

		public static function getInstance($wait = False){
			if (!isset(static::$instance))
	        {
	            self::$instance = new MessageConsumer($wait);
	        }
	        return static::$instance;
		}

		public function wait(){
			print("Callbacks - ". count($this->channel->callbacks)."\n");
			while(count($this->channel->callbacks)) {
			    $this->channel->wait();
			}
		}

		public function consumeMessage($queue, $callback){
			$this->channel->queue_declare($queue, false, true, false, false);
			// $this->channel->basic_qos();
			//$this->channel->exchange_declare(EXCHANGE, EXCHANGE_TYPE, false, true, false);
			//$this->channel->queue_bind($queue, EXCHANGE);
			$this->channel->basic_consume($queue, '', false, false, false, false, $callback);
		}
	}
?>