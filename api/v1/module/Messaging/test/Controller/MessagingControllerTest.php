<?php
namespace Messaging;

use Messaging\Controller\MessagingController;
use PHPUnit\DbUnit\TestCaseTrait;
use Oxzion\Test\MainControllerTest;
use Oxzion\Messaging\MessageProducer;
use Mockery;

class MessagingControllerTest extends MainControllerTest{
    
    private $mockMessageProducer = null;

    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
        $config = $this->getApplicationConfig();
        $this->mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $this->mockMessageProducer->expects('getInstance')->once()->andReturn($this->mockMessageProducer);
    }

    public function getMockMessageProducer(){
        $messagingService = $this->getApplicationServiceLocator()->get(Service\MessagingService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $messagingService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Messaging');
        $this->assertControllerName(MessagingController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MessagingController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }


    public function testTopicCreate()
    {
        $data = ['topic' => 'test_topic', 'param1' => 'value1', 'param2' => 'value2'];
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('topic' => 'test_topic', 'param1' => 'value1','param2'=>'value2')),'test_topic')->once()->andReturn();
        }
        $this->dispatch('/messaging', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('messaging');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['result']['topic'], $data['topic']);
        $this->assertEquals($content['data']['result']['param1'], $data['param1']);
        $this->assertEquals($content['data']['result']['param2'], $data['param2']);
    }
    public function testQueueCreate()
    {
        $data = ['queue' => 'test_queue', 'param1' => 'value1', 'param2' => 'value2'];
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(json_encode(array('queue' => 'test_queue', 'param1' => 'value1','param2'=>'value2')),'test_queue')->once()->andReturn();
        }
        $this->dispatch('/messaging', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('messaging');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['result']['queue'], $data['queue']);
        $this->assertEquals($content['data']['result']['param1'], $data['param1']);
        $this->assertEquals($content['data']['result']['param2'], $data['param2']);
    }
    public function testFail()
    {
        $data = ['param1' => 'value1', 'param2' => 'value2'];
        $this->setJsonContent(json_encode($data));
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(json_encode(array('param1' => 'value1','param2'=>'value2')),'test_queue')->once()->andReturn();
        }
        $this->dispatch('/messaging', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('messaging');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

  
}