<?php

namespace Oxzion\Test;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Jwt\JwtHelper;
use PHPUnit\DbUnit\TestCaseTrait;
use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;
use Oxzion\Transaction\TransactionManager;
use Zend\Console\Console;

abstract class MainControllerTest extends AbstractHttpControllerTestCase
{
    protected $adminUser = 'bharatg'; //TODO Need to put as global setup
    protected $adminUserId = 1;
    protected $employeeUser = 'karan';
    protected $employeeUserId = 2;
    protected $managerUser = 'rakshith';
    protected $managerUserId = 3;
    protected $noUser = 'admin';
    protected $noUserId = 0;
    protected $testOrgId = 1;


    protected $jwtToken = array();
    /**
     * @var \Zend\Mvc\ApplicationInterface
     */
    protected $application;

    /**
     * @var array
     */
    protected $applicationConfig;

    /**
     * Reset the application for isolation
     */
    protected function setUp() : void
    {
        parent::setUp();
        $this->setupConnection();
        $tm = $this->getTransactionManager();
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
    }

    //this is required to ensure that same connection is used by dbunit and zend db
    protected function setupConnection(){
    }

    /**
     * Restore params
     */
    protected function tearDown()
    {
        $tm = $this->getTransactionManager();
        $tm->rollback();
        parent::tearDown();
        $_REQUEST = [];
    }
    
    protected function getTransactionManager(){
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        return TransactionManager::getInstance($dbAdapter);
    }

    protected function loadConfig()
    {
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/autoload/global.php', include __DIR__ . '/../../../../config/autoload/local.php');
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php', $configOverrides);

        $this->setApplicationConfig($configOverrides);
    }

    protected function initAuthToken($username)
    {
        $token = $this->getJwtToken($username);
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine('Authorization', 'Bearer ' . $token);
    }

    private function getJwtToken($username)
    {

        if (!isset($this->jwtToken[$username])) {
            $data = JwtHelper::getTokenPayload(['username'=>$username,'orgid' => $this->testOrgId]);
            $config = $this->getApplicationConfig();
            $jwtKey = $config['jwtKey'];
            $jwtAlgo = $config['jwtAlgo'];
            $token = JwtHelper::generateJwtToken($data, $jwtKey, $jwtAlgo);
        } else {
            $token = $this->jwtToken[$username];
        }
        return $token;
    }

    protected function getMockObject($class, array $constructorArgs = null)
    {
        $mock = $this->getMockBuilder($class);
        if (!is_null($constructorArgs)) {
            $mock = $mock->setConstructorArgs($constructorArgs);
        } else {
            $mock->disableOriginalConstructor();
        }

        return $mock->getMock();
    }

    protected function setService($name, $obj)
    {
        $container = $this->getApplicationServiceLocator();
        $container->setAllowOverride(true);
        $container->setService($name, $obj);
    }

    protected function setJsonContent($jsonData)
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        $headers->addHeaderLine('content-type', 'application/json');
        $request->setContent($jsonData);
    }
     /**
     * Get the application config
     * @return array the application config
     */
    public function getApplicationConfig()
    {
        return $this->applicationConfig;
    }

    /**
     * Set the application config
     * @param  array                      $applicationConfig
     * @return AbstractControllerTestCase
     * @throws LogicException
     */
    public function setApplicationConfig($applicationConfig)
    {
        if (null !== $this->application && null !== $this->applicationConfig) {
            throw new LogicException(
                'Application config can not be set, the application is already built'
            );
        }

        // do not cache module config on testing environment
        if (isset($applicationConfig['module_listener_options']['config_cache_enabled'])) {
            $applicationConfig['module_listener_options']['config_cache_enabled'] = false;
        }
        $this->applicationConfig = $applicationConfig;

        return $this;
    }

    /**
     * Get the application object
     * @return \Zend\Mvc\ApplicationInterface
     */
    public function getApplication()
    {
        if ($this->application) {
            return $this->application;
        }
        $appConfig = $this->applicationConfig;
        Console::overrideIsConsole($this->getUseConsoleRequest());
        $this->application = Application::init($appConfig);

        $events = $this->application->getEventManager();
        $this->application->getServiceManager()->get('SendResponseListener')->detach($events);

        return $this->application;
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