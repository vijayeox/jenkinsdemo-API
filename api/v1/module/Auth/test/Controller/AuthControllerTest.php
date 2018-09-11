<?php

namespace Auth;

use Auth\Controller\AuthController;
use Oxzion\Test\ControllerTest;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Authentication\Result;
use Zend\Stdlib\ArrayUtils;


class AuthControllerTest extends ControllerTest{
	public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));
    }

    public function testAuthentication(){
    	$mockAuthAdapter = $this->getMockObject(AuthAdapter::class);
    	$authAdapter = $this->getApplicationServiceLocator()->get(AuthAdapter::class);
    	$this->setService(AuthAdapter::class, $mockAuthAdapter);
    	$data = ['username' => 'testUser', 'password' => 'password'];
    	$result = new Result(
                    Result::SUCCESS, 
                    $data['username'], 
                    ['Authenticated successfully.']);
    	$mockAuthAdapter->expects($this->once())
			    ->method('setIdentity')
			    ->with($data['username']);
		$mockAuthAdapter->expects($this->once())
			    ->method('setCredential')
			    ->with($data['password']);
		$mockAuthAdapter->expects($this->once())
                ->method('authenticate')
                ->will($this->returnValue($result));
        $this->dispatch('/auth', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('auth');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(is_null($content['data']['jwt']), false);
                
    }

    public function testAuthenticationFail(){
    	$mockAuthAdapter = $this->getMockObject(AuthAdapter::class);
    	$authAdapter = $this->getApplicationServiceLocator()->get(AuthAdapter::class);
    	$this->setService(AuthAdapter::class, $mockAuthAdapter);
    	$data = ['username' => 'testUser', 'password' => 'password'];
    	$result = new Result(
                    Result::FAILURE, 
                    $data['username'], 
                    ['Authenticated Failed.']);
    	$mockAuthAdapter->expects($this->once())
			    ->method('setIdentity')
			    ->with($data['username']);
		$mockAuthAdapter->expects($this->once())
			    ->method('setCredential')
			    ->with($data['password']);
		$mockAuthAdapter->expects($this->once())
                ->method('authenticate')
                ->will($this->returnValue($result));
        $this->dispatch('/auth', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('auth');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
                
    }
}