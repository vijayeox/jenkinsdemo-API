<?php
namespace Messaging\Service;

use Oxzion\Messaging\MessageProducer;

class MessagingService
{
    public function __construct(MessageProducer $messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    public function send($data)
    {
        if (isset($data['topic'])) {
            $response = $this->messageProducer->sendTopic(json_encode($data), $data['topic']);
        } else {
            if (isset($data['queue'])) {
                $response = $this->messageProducer->sendQueue(json_encode($data), $data['queue']);
            } else {
                return false;
            }
        }
        return $response;
    }
}
