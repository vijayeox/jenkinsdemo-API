<?php
namespace Callback;

use Callback\Controller\OXCallbackController;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;

class OXCallbackControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    public function getMockMessageProducer()
    {
        $mockMessageProducer = $this->getMockObject('Oxzion\Messaging\MessageProducer');
        return $mockMessageProducer;
    }

    public function testCreatedUser()
    {
        $data = array('email' => 'bharat@goku.com', 'firstname' => 'bharat', 'username' => 'bharat', 'resetCode' => 'werwe234234324234werer234', 'orgid' => '53012471-2863-4949-afb1-e69b0891c98a');
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/ox/createuser', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('user_added_mail');
        $this->assertEquals($content['status'], 'success');
    }
    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Callback');
        $this->assertControllerName(OXCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('OXCallbackController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
}
