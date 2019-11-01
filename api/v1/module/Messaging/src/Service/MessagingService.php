<?php
namespace Messaging\Service;

use Exception;
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
            try {
                $response = $this->messageProducer->sendTopic(json_encode($data), $data['topic']);
                $response = true;
            } catch (Exception $e) {
                return false;
            }
        } else {
            if (isset($data['queue'])) {
                try {
                    $this->messageProducer->sendQueue(json_encode($data), $data['queue']);
                    $response = true;
                } catch (Exception $e) {
                    return false;
                }
            } else {
                return false;
            }
        }
        return $response;
    }
}
