<?php

namespace Auth;

use Auth\Controller\AuthController;
use Oxzion\Test\ControllerTest;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Authentication\Result;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\AdapterInterface;

class AuthControllerTest extends ControllerTest{

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
        $data = ['username' => $this->adminUser, 'password' => 'password'];
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
        $this->assertEquals(is_null($content['data']['refresh_token']), false);
    }

    public function testAuthenticationFail(){
        $data = ['username' => 'mehul', 'password' => 'password'];
        $this->dispatch('/auth', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('auth');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Authentication Failure - Incorrect data specified');
    }

    public function testAuthenticationRefreshTokenExpired(){
        $data = ['username' => $this->managerUser, 'password' => 'password'];
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
        $this->assertNotEquals($content['data']['refresh_token'], '6456365665c809d01693770.52543401');

    }

    public function testAuthenticationByApiKey(){
        $data = ['apikey' => '0cb6fd4c-40a5-11e9-a30d-1c1b0d785c98', 'orgid' => '1'];
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

    public function testAuthenticationFailByApiKey(){
        $data = ['apikey' => '0cb6fd4c-40a5-11e9-a30d-1c1b0d785x36', 'orgid' => '1'];
        $this->dispatch('/auth', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('auth');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');

    }

    public function testRefreshValidUser(){

        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $query="update ox_user_refresh_token set expiry_date = '".date('Y-m-d H:i:s', strtotime('+1 day', time()))."'";
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();
        $data = ['username' => $this->adminUser, 'password' => 'password'];
        $this->dispatch('/auth', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $rToken = $content['data']['refresh_token'];
        $jToken = $content['data']['jwt'];
        $this->reset();
        $data = ['jwt' => $jToken, 'refresh_token' => $rToken];
        $this->dispatch('/refreshtoken', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('refreshtoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(is_null($content['data']['jwt']), false);
        $this->assertNotEquals($content['data']['jwt'], $jToken);
        $this->assertEquals($content['data']['refresh_token'], $rToken);
    }

    public function testRefreshJwtTokenExpired() {
        $data = ['username' => $this->adminUser, 'password' => 'password'];
        $this->dispatch('/auth', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $rToken = $content['data']['refresh_token'];
        $jToken = $content['data']['jwt'];
        $this->reset();
        $data = ['jwt' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1NjA0MTgzMzgsImp0aSI6ImEyVUlTRkY4dXZMRTBVQnlMSDFxdDF1TldRUW14U29QXC81Skc2d085NEhvPSIsIm5iZiI6MTU2MDQxODMzOCwiZXhwIjoxNTYwNDE4MzQ4LCJkYXRhIjp7InVzZXJuYW1lIjoiYmhhcmF0Z3Rlc3QiLCJvcmdpZCI6IjEifX0.7rTurKwUph5WA9rB--5EVHHg3E_M0tVd5boshuuoPVwZgarCCymuqs8voA06aFuwM6I00tDmpYApG-dIA3BDzQ', 'refresh_token' => $rToken];
        $this->dispatch('/refreshtoken', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('refreshtoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $responseContent = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($responseContent['status'], 'success');
        $this->assertNotEquals($responseContent['data']['jwt'], $jToken);
        $this->assertEquals($responseContent['data']['refresh_token'], $rToken);
    }

    public function testRefreshFailRefreshTokenExpired() {
        $data = ['jwt' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1NTI1NDc4MDgsImp0aSI6IkNkNzJyeUJnOXZ2M2g0dDZOc2JFell0VGtYaVBKM3Z5YVwvbGx1aWJyTzhVPSIsIm5iZiI6MTU1MjU0NzgwOCwiZXhwIjoxNTUyNTQ3ODE1LCJkYXRhIjp7InVzZXJuYW1lIjoiYmhhcmF0ZyIsIm9yZ2lkIjoiMSJ9fQ.EQoePmb-g9xN0gME6fL8_SSDK7hzwDcmi21qQy-bW5X0RneA03sfv61btb8Q84rL3fM_Ad8UiLyVTgsFU05Pxw', 'refresh_token' => '13273925815c7e2c7930c794.82022621'];
        $this->dispatch('/refreshtoken', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('refreshtoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Refresh Token Expired');
    }

    public function testRefreshFailInvalidToken() {
        $data = ['jwt' => 'eyiJKV1QUzUxMiJ9.eyJpYXQiOjE1NTE3NzI3OTMsImp0aSI6IjRKZjlqT0dkNjVkNlhvQjBBRUhzb0VWd0VBOEZibkFGbjkyT3kzV09sXC9ZPSIsIm5iZiI6MTU1MTc3Mjc5MywiZXhwIjoxNTUxNzcyODIzLCJkYXRhIjp7InVzZXJuYW1lIjoicmFrc2hpdGgiLCJvcmdJZCI6IjEifX0.rYJg5Jyq2_KdXJZjuc4moYY3Zfr1NWXrWN4cemTwNqv-t0NfXIRw7OShsCQRYbCiC6K_5lSeCwDSxdyZFVSLAA', 'refresh_token' => '13273925815c7e2c7930c794.82022621'];
        $this->dispatch('/refreshtoken', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('refreshtoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Invalid JWT Token');
    }

    public function testRefreshNoJwtToken() {
        $data = [ 'refresh_token' => '13273925815c7e2c7930c794.82022621'];
        $this->dispatch('/refreshtoken', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('refreshtoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'JWT Token Not Found');
    }

    public function testValidateToken(){
        $data = ['username' => $this->adminUser, 'password' => 'password'];
        $this->dispatch('/auth', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $data = ['jwt' => $content['data']['jwt']];
        $this->reset();
        $this->dispatch('/validatetoken', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('validatetoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['message'], 'Token Valid');

    }

    public function testValidateTokenFail(){
        $data =[ 'jwt' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1NTE4NjQ2MTAsImp0aSI6InFwaHMxSDc4ZzBIVFVWSUlNZHZmSHBFd1hvZXJzeGdNdGRSUDRvQUN2KzQ9IiwibmJmIjoxNTUxODY0NjEwLCJleHAiOjE1NTE4NjQ3MTAsImRhdGEiOnsidXNlcm5hbWUiOiJiaGFyYXRnIiwib3JnSWQiOiIxIn19.b2OsP-ZE0LHzSb6t4NO3tMdM1OIrKJ23hsQK0iV5lsyM03UfuGwhFl8GJShlAojXtG0jD7ujNOhC2yJvVzRqPQ'];
        $this->dispatch('/validatetoken', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('validatetoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Token Expired');

    }

    public function testValidateTokenInvalid(){
        $data =[ 'jwt' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1NTE4NjUwNjksImp0aSI6IlB4ZGthaUc1eE1qYUE2MW1lSkFOZGpSWWZpVVFrdVRoN1hIemRqUWs4VkE9IiwibmJmIjoxNTUxODY1MDY5LCJleHAiOjE1NTE5MzcwNjksImRhdGEiOnsidXNlcm5hbWUiOiJiaGFyYXRnIiwiSWQiOiIxIn19.TaAN3LvL2hbMfpPgQVm4fhhBCsT_sEJ5_jyp3id0qvI6i_Pmra4dL-aYgsoDw-9bqdDr2WUfh-Xu9vFnfV-eoA'];
        $this->dispatch('/validatetoken', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('validatetoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Invalid JWT Token');

    }

    public function testValidateNoToken(){
        $data =[];
        $this->dispatch('/validatetoken', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('validatetoken');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'JWT Token Not Found');

    }

    public function testValidProfileUsername(){
        $data = ['username'=>$this->adminUser];
        $this->dispatch('/userprof', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('userprof');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'],'Bharat Gogineni');
        $this->assertContains('user/profile/',$content['data']['profileUrl']);
    }

    public function testValidProfileEmail(){
        $data = ['username'=>'bharatg@myvamla.com'];
        $this->dispatch('/userprof', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('userprof');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['username'],'Bharat Gogineni');
        $this->assertContains('user/profile/',$content['data']['profileUrl']);
    }

    public function testInvalidProfileEmail(){
        $data = ['username'=>'bharat@vma.com'];
        $this->dispatch('/userprof', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('userprof');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'],'Invalid User');
    }

    public function testInvalidProfileUsername(){
        $data = ['username'=>'bhara'];
        $this->dispatch('/userprof', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('userprof');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'],'Invalid User');
    }

    public function testSSO() {
        $data = ['uname'=>'YmhhcmF0Z3Rlc3Q='];
        $this->dispatch('/sso', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('singleSignOn');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(is_null($content['data']['jwt']), false);
        $this->assertEquals(is_null($content['data']['refresh_token']), false);
    }

    public function testSSOWithIncorrectName () {
        $data = ['uname'=>'bhara'];
        $this->dispatch('/sso', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('auth');
        $this->assertControllerName(AuthController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('singleSignOn');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'],'Something went wrong');
    }
}