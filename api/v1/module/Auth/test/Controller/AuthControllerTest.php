<?php

namespace Auth;

use Auth\Controller\AuthController;
use Oxzion\Test\ControllerTest;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Authentication\Result;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Stdlib\ArrayUtils;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;

class AuthControllerTest extends ControllerTest{
    static private $pdo = null;
    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;
	public function setUp() : void{
        $configOverrides = [include __DIR__ . '/../../../../config/autoload/global.php'];
        $this->setApplicationConfig(ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php',$configOverrides));
        parent::setUp();
    }
    public function getDataSet() {
        return new DefaultDataSet();
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
    	$data = ['username' => 'rakshith', 'password' => 'password'];
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