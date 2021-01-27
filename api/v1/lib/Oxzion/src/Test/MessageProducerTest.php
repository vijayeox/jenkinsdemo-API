<?php
namespace MessageProducerTest;

use Mockery;
use PHPUnit\Framework\TestCase;
use Oxzion\Messaging\MessageProducer;

class MessageProducerTest extends TestCase
{
    public function testAccountCreationUsingTopic()
    {
        $mp = MessageProducer::getInstance();
        $mp->sendTopic(json_encode(array('accountName' => 'Cleveland white', 'status' => 'Active')), 'ACCOUNT_ADDED');
    }
}
