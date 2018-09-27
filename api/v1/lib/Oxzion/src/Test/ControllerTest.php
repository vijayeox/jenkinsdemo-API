<?php

namespace Oxzion\Test;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Jwt\JwtHelper;
use PHPUnit\DbUnit\TestCaseTrait;
use Zend\Stdlib\ArrayUtils;

abstract class ControllerTest extends AbstractHttpControllerTestCase{
    use TestCaseTrait;
    protected $adminUser='bharatg'; //TODO Need to put as global setup
    protected $adminUserId=1;
    protected $employeeUser = 'karan';
    protected $employeeUserId=2;
    protected $managerUser = 'rakshith';
    protected $managerUserId=3;

    protected $jwtToken = array();
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;
    
    protected function loadConfig(){
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/autoload/global.php', include __DIR__ . '/../../../../config/autoload/local.php');
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php',$configOverrides);

        $this->setApplicationConfig($configOverrides);
    }

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                $config = $this->getApplicationConfig();
                $config = $config['db'];
                self::$pdo = new \PDO( $config['dsn'], $config['username'], $config['password'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $config['database']);
        }
        return $this->conn;
    }
    
    abstract function getDataSet();
    
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