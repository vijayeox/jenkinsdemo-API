<?php

namespace Oxzion\Test;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Jwt\JwtHelper;
use PHPUnit\DbUnit\TestCaseTrait;
use Zend\Stdlib\ArrayUtils;


abstract class ControllerTest extends MainControllerTest{
    use TestCaseTrait;
	static protected $pdo = null;
	static protected $connection = null;

    abstract function getDataSet();
	
	public function getConnection()
    {
        if (!isset(static::$pdo)) {
            $config = $this->getApplicationConfig();
            $config = $config['db'];
            static::$pdo = new \PDO($config['dsn'], $config['username'], $config['password'], array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            static::$connection = $this->createDefaultDBConnection(static::$pdo);
        }
        return static::$connection;
    }
	
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

    /**
     * Get the service manager of the application object
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getApplicationServiceLocator()
    {
        return $this->getApplication()->getServiceManager();
    }


}