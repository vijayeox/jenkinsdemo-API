<?php

namespace User;

use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Exception;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\UserService;
use User\Controller\UserController;
use User\Controller\ForgotPasswordController;
use Oxzion\Messaging\MessageProducer;

    


class ForgotPasswordControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }
    protected function setDefaultAsserts($router = "user")
    {
        $this->assertModuleName('User');
        $this->assertControllerName(ForgotPasswordController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ForgotPasswordController');
        $this->assertMatchedRouteName($router);
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/User.yml");
         return $dataset;
    }

    public function getMockMessageProducer(){
        $organizationService = $this->getApplicationServiceLocator()->get(UserService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $organizationService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }


    public function testForgotPassword()
    {
        $this->initAuthToken($this->managerUser);
        $data = ['username' => 'bharatgtest'];
        $this->setJsonContent(json_encode($data));
        if(enableCamel == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'USER_ADDED')->once()->andReturn();
        }
        $this->dispatch('/user/me/forgotpassword', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('forgotPassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->resetCode = $content['data']['password_reset_code'];
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['email'], $data['email']);
    }

    public function testForgotPasswordWrongEmail()
    {
        $this->initAuthToken($this->managerUser);
        $data = ['username' => 'wrongemail@va.com'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/forgotpassword', 'POST', $data);
        $this->assertResponseStatusCode(404);      
        $this->setDefaultAsserts('forgotPassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'The username entered does not match your profile username');
    } 

}