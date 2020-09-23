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
use Mockery;
use Oxzion\Utils\UuidUtil;
    


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
        $userService = $this->getApplicationServiceLocator()->get(UserService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $userService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }


    public function testForgotPassword()
    {
        $data = ['username' => $this->adminUser];
        $this->setJsonContent(json_encode($data));
        if(enableCamel == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(),'mail')->once()->andReturn();
        }
        $this->dispatch('/user/me/forgotpassword', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('forgotPassword');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'],'admintest');
    }

    public function testForgotPasswordWrongEmail()
    {
        $data = ['username' => 'wrongemail@va.com'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/forgotpassword', 'POST', $data);
        $this->assertResponseStatusCode(404);      
        $this->setDefaultAsserts('forgotPassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'User not found with username or email for wrongemail@va.com');
    } 

    public function testResetPassword()
    {
        $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));
        $resetCode = UuidUtil::uuid();
        $query = "update ox_user set password_reset_code = '".$resetCode."', password_reset_expiry_date = '".$expiry."' where id = 50" ;
        $this->executeUpdate($query);
        $data = ['password_reset_code' => $resetCode, 
                 'new_password' => 'password',
                 'confirm_password' => 'password'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/resetpassword', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);      
        $this->setDefaultAsserts('resetPassword');
        $this->assertEquals($content['status'], 'success');
        
    }
    
    public function testResetPasswordMismatch()
    {
        $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));
        $resetCode = UuidUtil::uuid();
        $query = "update ox_user set password_reset_code = '".$resetCode."', password_reset_expiry_date = '".$expiry."' where id = 6" ;
        $this->executeUpdate($query);
        $data = ['password_reset_code' => $resetCode, 
                 'new_password' => 'password',
                 'confirm_password' => 'passwordmismatch'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/resetpassword', 'POST', $data);
        $this->assertResponseStatusCode(400);      
        $this->setDefaultAsserts('resetPassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
    }

    public function testResetPasswordInvalidResetCode()
    {
        $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));
        $resetCode = UuidUtil::uuid();
        $query = "update ox_user set password_reset_code = '".$resetCode."', password_reset_expiry_date = '".$expiry."' where id = 6" ;
        $this->executeUpdate($query);
        $data = ['password_reset_code' => UuidUtil::uuid(), 
                 'new_password' => 'password',
                 'confirm_password' => 'password'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/resetpassword', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);      
        $this->setDefaultAsserts('resetPassword');
        $this->assertEquals($content['status'], 'error');
        
    }

    public function testResetPasswordExpiredResetCode()
    {
        $expiry = date("Y-m-d H:i:s", strtotime("-1 minutes"));
        $resetCode = UuidUtil::uuid();
        $query = "update ox_user set password_reset_code = '".$resetCode."', password_reset_expiry_date = '".$expiry."' where id = 6" ;
        $this->executeUpdate($query);
        $data = ['password_reset_code' => $resetCode, 
                 'new_password' => 'password',
                 'confirm_password' => 'password'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/user/me/resetpassword', 'POST', $data);
        $this->assertResponseStatusCode(404);      
        $this->setDefaultAsserts('resetPassword');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
    }
    
}