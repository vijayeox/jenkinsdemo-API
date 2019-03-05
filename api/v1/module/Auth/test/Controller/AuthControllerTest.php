<?php

namespace Auth;

use Auth\Controller\AuthController;
use Oxzion\Test\ControllerTest;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Authentication\Result;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;

class AuthControllerTest extends ControllerTest{
    static private $pdo = null;
    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }
    
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/token.yml");
        return $dataset;
    }

    public function testAuthentication(){
        $data = ['username' => 'bharatg', 'password' => 'password'];
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
        $data = ['username' => 'mehul', 'password' => 'password'];
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

    public function testRefreshValidUser(){
        $data = ['jwt' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1NTE3NzIyNzEsImp0aSI6IjRzNWZCQXBYcXpNNHUwRlh4ZjBnT1lRNHZBZ08zWXdIOTlPRGpJeU95TkU9IiwibmJmIjoxNTUxNzcyMjcxLCJleHAiOjE1NTE3NzIzMDEsImRhdGEiOnsidXNlcm5hbWUiOiJiaGFyYXRnIiwib3JnSWQiOiIxIn19.-qT7huPch1barkQ-SWiJ8Jqg3i4SstlHHc4Lkt-nKs2lDtkMhkka0nTdPn8D41vcbK8XkF3G9djE_L13OENr_Q', 'refresh_token' => '5940264875c7e2a6f120969.43960974'];
        $this->dispatch('/refreshtoken', 'POST', $data);
        // $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('refreshtoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(is_null($content['data']['jwt']), false);
        $this->assertEquals(is_null($content['data']['refresh_token']), false);
    }

    public function testRefreshFailInvalidUser() {
        $data = ['jwt' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1NTE3NzI3OTMsImp0aSI6IjRKZjlqT0dkNjVkNlhvQjBBRUhzb0VWd0VBOEZibkFGbjkyT3kzV09sXC9ZPSIsIm5iZiI6MTU1MTc3Mjc5MywiZXhwIjoxNTUxNzcyODIzLCJkYXRhIjp7InVzZXJuYW1lIjoicmFrc2hpdGgiLCJvcmdJZCI6IjEifX0.rYJg5Jyq2_KdXJZjuc4moYY3Zfr1NWXrWN4cemTwNqv-t0NfXIRw7OShsCQRYbCiC6K_5lSeCwDSxdyZFVSLAA', 'refresh_token' => '13273925815c7e2c7930c794.82022621'];
        $this->dispatch('/refreshtoken', 'POST', $data);
        // $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('refreshtoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Refresh Token Expired');   
        $this->assertEquals($content['data'], array());
    }

    public function testRefreshInvalidToken() {
        $data = [ 'refresh_token' => '13273925815c7e2c7930c794.82022621'];
        $this->dispatch('/refreshtoken', 'POST', $data);
        // $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('refreshtoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Invalid JWT Token');   
        $this->assertEquals($content['data'], array());
    }
}