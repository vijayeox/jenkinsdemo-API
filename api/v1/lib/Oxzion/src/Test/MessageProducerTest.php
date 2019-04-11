<?php
namespace MessageProducerTest;

use Mockery;
use PHPUnit\Framework\TestCase;
use Oxzion\Messaging\MessageProducer;

class MessageProducerTest extends TestCase{
    public function testOrganizationCreationUsingTopic(){
        $mp = MessageProducer::getInstance();
        $mp->sendTopic(json_encode(array('orgname' => 'Cleveland Final', 'status' => 'Active')),'ORGANIZATION_ADDED');
    }

    // public function testOrganizationCreationUsingQueue(){
    //     $mp = MessageProducer::getInstance();
    //     $mp->sendQueue(json_encode(array('name' => 'Cleveland White', 'status' => 'Active')),'ORGANIZATION_ADDED');
    // }

    // public function testMail(){
    //     $mp = MessageProducer::getInstance();
    //     $mp->sendTopic(json_encode(array('to' => 'saditha@myvamla.com', 'from' => 'oxzion@oxzion.com', 
    //                                      'subject' => "Test", 'body' => 'Test Body')),'mail');
    // }

}
?>
