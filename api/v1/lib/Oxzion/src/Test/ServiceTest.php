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
use Oxzion\Service\UserService;
use Oxzion\Auth\AuthSuccessListener;
use Oxzion\Auth\AuthConstants;

class ServiceTest extends TestCase
{
    protected $adminUser = 'admintest'; //TODO Need to put as global setup
    protected $adminUserId = 1;
    protected $employeeUser = 'employeetest';
    protected $employeeUserId = 2;
    protected $managerUser = 'managertest';
    protected $managerUserId = 3;
    protected $noUser = 'admin';
    protected $noUserId = 0;
    protected $testOrgId = 1;

    /**
     * @var \Zend\Mvc\ApplicationInterface
     */
    protected $application;

    /**
     * @var array
     */
    protected $applicationConfig;

    /**
     * Flag to use console router or not
     * @var bool
     */
    protected $useConsoleRequest = false;

    /**
     * Flag console used before tests
     * @var bool
     */
    protected $usedConsoleBackup;

    /**
     * Trace error when exception is throwed in application
     * @var bool
     */
    protected $traceError = true;

    protected $dbAdapter;

    /**
     * Reset the application for isolation
     */
    protected function setUp()
    {
        $_REQUEST = [];
        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['SERVER_NAME'] = "localhost";
        $_SERVER['SERVER_PORT'] = "8080";
    
        $this->usedConsoleBackup = Console::isConsole();
        $this->reset();
        $this->setupConnection();
        $tm = $this->getTransactionManager();
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
    }

    protected function loadConfig()
    {
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/autoload/global.php', include __DIR__ . '/../../../../config/autoload/local.php');
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php', $configOverrides);
        $this->setApplicationConfig($configOverrides);
    }

    //this is required to ensure that same connection is used by dbunit and zend db
    protected function setupConnection()
    {
    }

    protected function getSetUpOperation()
    {
        return Factory::INSERT();
    }

    /**
     * Restore params
     */
    protected function tearDown()
    {
        $tm = $this->getTransactionManager();
        $tm->rollback(true);
        Console::overrideIsConsole($this->usedConsoleBackup);
        // Prevent memory leak
        $this->reset();
        //cleanup required to remove the transactionManager
        $_REQUEST = [];
    }

    protected function getDbAdapter(){
        if(!$this->dbAdapter){
            $this->dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);    
        }
        
        return $this->dbAdapter;
    }

    protected function setDbAdapter($dbAdapter){
        $this->dbAdapter = $dbAdapter;
    }
    /**
     * Reset the request
     *
     * @return AbstractControllerTestCase
     */
    
    protected function getTransactionManager()
    {
        $dbAdapter = $this->getDbAdapter();
        return TransactionManager::getInstance($dbAdapter);
    }

    /**
     * Get the trace error flag
     * @return bool
     */
    public function getTraceError()
    {
        return $this->traceError;
    }

    /**
     * Set the trace error flag
     * @param  bool                       $traceError
     * @return AbstractControllerTestCase
     */
    public function setTraceError($traceError)
    {
        $this->traceError = $traceError;

        return $this;
    }

    /**
     * Get the usage of the console router or not
     * @return bool $boolean
     */
    public function getUseConsoleRequest()
    {
        return $this->useConsoleRequest;
    }

    /**
     * Set the usage of the console router or not
     * @param  bool                       $boolean
     * @return AbstractControllerTestCase
     */
    public function setUseConsoleRequest($boolean)
    {
        $this->useConsoleRequest = (bool) $boolean;

        return $this;
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
     * @return ServiceTest
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

    /**
     * Reset the request
     *
     * @return AbstractControllerTestCase
     */
    public function reset($keepPersistence = false)
    {
        // force to re-create all components
        $this->application = null;
        //unset($_REQUEST[TransactionManager::CONTEXT_KEY]);
        
        // reset server data
        if (! $keepPersistence) {
            // Do not create a global session variable if it doesn't already
            // exist. Otherwise calling this function could mark tests risky,
            // as it changes global state.
            if (array_key_exists('_SESSION', $GLOBALS)) {
                $_SESSION = [];
            }
            $_COOKIE  = [];
        }

        $_GET     = [];
        $_POST    = [];
        
        // reset singleton
        if (class_exists(StaticEventManager::class)) {
            StaticEventManager::resetInstance();
        }

        return $this;
    }

    /**
     * Trigger an application event
     *
     * @param  string                                $eventName
     * @return \Zend\EventManager\ResponseCollection
     */
    public function triggerApplicationEvent($eventName)
    {
        $events = $this->getApplication()->getEventManager();
        $event  = $this->getApplication()->getMvcEvent();

        if ($eventName != MvcEvent::EVENT_ROUTE && $eventName != MvcEvent::EVENT_DISPATCH) {
            return $events->trigger($eventName, $event);
        }

        $shortCircuit = function ($r) use ($event) {
            if ($r instanceof ResponseInterface) {
                return true;
            }

            if ($event->getError()) {
                return true;
            }

            return false;
        };

        $event->setName($eventName);
        return $events->triggerEventUntil($shortCircuit, $event);
    }

    /**
     * Assert modules were loaded with the module manager
     *
     * @param array $modules
     */
    public function assertModulesLoaded(array $modules)
    {
        $moduleManager = $this->getApplicationServiceLocator()->get('ModuleManager');
        $modulesLoaded = $moduleManager->getModules();
        $list          = array_diff($modules, $modulesLoaded);
        if ($list) {
            throw new ExpectationFailedException($this->createFailureMessage(
                sprintf('Several modules are not loaded "%s"', implode(', ', $list))
            ));
        }
        $this->assertEquals(count($list), 0);
    }

    /**
     * Assert modules were not loaded with the module manager
     *
     * @param array $modules
     */
    public function assertNotModulesLoaded(array $modules)
    {
        $moduleManager = $this->getApplicationServiceLocator()->get('ModuleManager');
        $modulesLoaded = $moduleManager->getModules();
        $list          = array_intersect($modules, $modulesLoaded);
        if ($list) {
            throw new ExpectationFailedException($this->createFailureMessage(
                sprintf('Several modules WAS not loaded "%s"', implode(', ', $list))
            ));
        }
        $this->assertEquals(count($list), 0);
    }

    /**
     * Assert the application exception and message
     *
     * @param $type application exception type
     * @param $message application exception message
     */
    public function assertApplicationException($type, $message = null)
    {
        $exception = $this->getApplication()->getMvcEvent()->getParam('exception');
        if (! $exception) {
            throw new ExpectationFailedException($this->createFailureMessage(
                'Failed asserting application exception, exception not exist'
            ));
        }
        if (true === $this->traceError) {
            // set exception as null because we know and have assert the exception
            $this->getApplication()->getMvcEvent()->setParam('exception', null);
        }

        if (! method_exists($this, 'expectException')) {
            // For old PHPUnit 4
            $this->setExpectedException($type, $message);
        } else {
            $this->expectException($type);
            if (! empty($message)) {
                $this->expectExceptionMessage($message);
            }
        }

        throw $exception;
    }
    /**
     * Assert that the application route match used the given module
     *
     * @param string $module
     */
    public function assertModuleName($module)
    {
        $controllerClass = $this->getControllerFullClassName();
        $match           = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $match           = strtolower($match);
        $module          = strtolower($module);
        if ($module != $match) {
            throw new ExpectationFailedException($this->createFailureMessage(
                sprintf('Failed asserting module name "%s", actual module name is "%s"', $module, $match)
            ));
        }
        $this->assertEquals($module, $match);
    }

    protected function initAuthContext($username){
         $userService = $this->getApplicationServiceLocator()->get(UserService::class);
         $authSuccessListener = new AuthSuccessListener($userService);
         $authSuccessListener->loadUserDetails(array(AuthConstants::USERNAME => $username));
    }

    protected function executeQueryTest($query)
    {
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet->toArray();
    }

    protected function executeUpdate($query)
    {
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $statement = $dbAdapter->query($query);
        $result = $statement->execute();
        return $result;
    }
}
