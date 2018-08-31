<?php

namespace Oxzion\Test;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Jwt\JwtHelper;

class ControllerTest extends AbstractHttpControllerTestCase{
    protected $jwtToken = array();

	protected function getMockGatewayData($name, $modelClass){
		$originalTableGateway = $this->getApplicationServiceLocator()->get($name);
		$dbAdapter = $originalTableGateway->getAdapter();
		$table = $originalTableGateway->getTable();
		$resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new $modelClass);
                    
	    $mockTableGateway = $this->getMockObject('\Zend\Db\TableGateway\TableGateway', array($table, $dbAdapter, null, $resultSetPrototype));
	    $this->setService($name, $mockTableGateway);
	    return ['mock' => $mockTableGateway, 'resultSet' => $resultSetPrototype];
    }

    protected function getMockDbObject(){
    	$dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
    	$mockDbAdapter = $this->getMockObject('Zend\Db\Adapter\Adapter', [$dbAdapter->getDriver(), $dbAdapter->getPlatform()]);
    	$this->setService(AdapterInterface::class, $mockDbAdapter);
    	return ['mock' => $mockDbAdapter, 'dbAdapter' => $dbAdapter];
    }

    private function getJwtToken($username){
        
        
        if(!isset($this->jwtToken[$username])){
            $data = JwtHelper::getTokenPayload($username);
            $config = $this->getApplicationConfig();
            $jwtKey = $config[0]['jwtKey'];
            $jwtAlgo = $config[0]['jwtAlgo'];      
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