<?php
namespace Oxzion\Messaging;

use Logger;
use Exception;

class MessageProducer
{
    private static $instance = null;
    private $client;
    private $logger;
    private function __construct()
    {
        $this->client = new Client();
        $this->logger = Logger::getLogger(__CLASS__);
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
            $this->logger->error($e->getMessage(), $e);
        }
    }
    public function sendQueue($message, $queue)
    {
        $this->client->sendMessage('/queue/' . $queue, $message);
    }
}
