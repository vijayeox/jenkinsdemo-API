<?php
namespace Oxzion\Messaging;

use Stomp\Client as StompClient;
use Stomp\SimpleStomp;
use Stomp\Network\Connection;
use Stomp\Transport\Message;

class Client extends StompClient
{
    protected $stomp;
    public function __construct($config)
    {
        $this->stomp = new StompClient($config['amqp']['host']);
        // return $this->stomp;
    }
    public function sendMessage($destination, $message)
    {
        try{
            // $connection = $this->stomp->connect();
            $result = $this->stomp->send($destination, new Message($message));
        } catch( Exception $e){
            print_r('error'.$e->getMessage());exit;
        }
        // return $this->stomp->send($destination, new Message($message));
    }
    public function subscribe($destination, $subscriptionId = null, $ack = 'auto', $selector = null, $durable = false)
    {
        $simpleStomp = new SimpleStomp($this->stomp);
        $subscription = $simpleStomp->subscribe($destination, $subscriptionId, $ack, $selector, $durable);
        $message = $this->stomp->readFrame();
        if ($message !== null) {
            $this->stomp->ack($message);
        } else {
            return 0;
        }
    }
}
