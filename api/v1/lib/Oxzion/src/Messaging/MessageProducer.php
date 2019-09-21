<?php
namespace Oxzion\Messaging;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Exception;

class MessageProducer
{
    private static $instance = null;
    private $client;
    private $logger;
    private function __construct()
    {
        $this->client = new Client();
        $this->logger = new Logger;
        $writer = new Stream(__DIR__ . '/../../../../logs/Messaging.log');
        $this->logger->addWriter($writer);
    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            self::$instance = new MessageProducer();
        }
        return static::$instance;
    }

    public function sendTopic($message, $topic)
    {
        try {
            $this->client->sendMessage('/topic/' . $topic, $message);
        } catch (Exception $e) {
            $this->logger->log(Logger::ERR, $e->getMessage());
        }
    }
    public function sendQueue($message, $queue)
    {
        $this->client->sendMessage('/queue/' . $queue, $message);
    }
}
