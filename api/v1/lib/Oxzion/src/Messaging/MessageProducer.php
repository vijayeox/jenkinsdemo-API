<?php
namespace Oxzion\Messaging;

use Logger;
use Exception;
use Oxzion\Service\ErrorLogService;

class MessageProducer
{
    private static $instance = null;
    private $client;
    private $logger;
    private $errorLogService;

    public function __construct($config,ErrorLogService $errorLogService )
    {
        $this->client = new Client($config);
        $this->logger = Logger::getLogger(__CLASS__);
        $this->errorLogService = $errorLogService;
    }

    public function sendTopic($message, $topic)
    {
        try {
            $result = $this->client->sendMessage('/topic/' . $topic, $message);
        } catch (Exception $e) {
            $this->errorLogService->saveError('amqp',$e->getTraceAsString(),$message,json_encode(array('topic'=>$topic)));
            $this->logger->error($e->getMessage(), $e);
            return false;
        }
        return true;
    }
    public function sendQueue($message, $queue)
    {
        try {
            $result = $this->client->sendMessage('/queue/' . $queue, $message);
        } catch (Exception $e){
            $this->errorLogService->saveError('amqp',$e->getTraceAsString(),$message,json_encode(array('queue'=>$queue)));
            $this->logger->error($e->getMessage(), $e);
            return false;
        }
        return true;
    }
}
