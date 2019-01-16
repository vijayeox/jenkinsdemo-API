<?php

namespace Oxzion\Test;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Jwt\JwtHelper;
use PHPUnit\DbUnit\TestCaseTrait;
use Zend\Stdlib\ArrayUtils;

abstract class MainControllerTest extends AbstractHttpControllerTestCase{
    protected $adminUser='bharatg'; //TODO Need to put as global setup
    protected $adminUserId=1;
    protected $employeeUser = 'karan';
    protected $employeeUserId=2;
    protected $managerUser = 'rakshith';
    protected $managerUserId=3;
    protected $testOrgId=1;

    protected $jwtToken = array();

    
    protected function loadConfig(){
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/autoload/global.php', include __DIR__ . '/../../../../config/autoload/local.php');
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php',$configOverrides);

        $this->setApplicationConfig($configOverrides);
    }

    
    private function getJwtToken($username){
              
        if(!isset($this->jwtToken[$username])){
            $data = JwtHelper::getTokenPayload($username,$this->testOrgId);
            $config = $this->getApplicationConfig();
            $jwtKey = $config['jwtKey'];
            $jwtAlgo = $config['jwtAlgo'];      
            $token = JwtHelper::generateJwtToken($data, $jwtKey, $jwtAlgo);
            $this->jwtToken[$username] = $token;
        }else{
            $token = $this->jwtToken[$username];    
        }

        return $token;
    }

    protected function initAuthToken($username){
        $token = $this->getJwtToken($username);
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine('Authorization', 'Bearer '.$token);
    }

    protected function getMockObject($class, array $constructorArgs = null){
    	$mock = $this->getMockBuilder($class);
    	if(!is_null($constructorArgs)){
    		$mock = $mock->setConstructorArgs($constructorArgs);
    	}else{
    		$mock->disableOriginalConstructor();
    	}

	    return $mock->getMock();
	}

	protected function setService($name, $obj){
		$container = $this->getApplicationServiceLocator();
	    $container->setAllowOverride(true);        
	    $container->setService($name, $obj);
	}

    protected function setJsonContent($jsonData){
    	$request = $this->getRequest();
    	$headers = $request->getHeaders();
        $headers->addHeaderLine('content-type', 'application/json');
    	$request->setContent($jsonData);
    }
}