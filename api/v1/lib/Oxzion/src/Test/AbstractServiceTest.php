<?php
namespace Oxzion\Test;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Zend\Console\Console;
use Zend\EventManager\StaticEventManager;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\Exception\LogicException;
use Zend\Stdlib\Parameters;
use Zend\Stdlib\ResponseInterface;
use Oxzion\Transaction\TransactionManager;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Stdlib\ArrayUtils;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\Operation\Factory;

abstract class AbstractServiceTest extends ServiceTest
{
    use TestCaseTrait;
    
    protected static $pdo = null;
    protected static $connection = null;

    abstract public function getDataSet();
    
    
    protected function getSetUpOperation()
    {
        return Factory::INSERT();
    }

    protected function setupConnection()
    {
        $this->getConnection();
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $dbAdapter->getDriver()->getConnection()->setResource(static::$pdo);
    }

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

}
