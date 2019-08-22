<?php
namespace Oxzion\AppDelegate;

use Oxzion\Test\ServiceTest;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\AppDelegate\AppDelegateService;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Exception;
use Oxzion\Transaction\TransactionManager;

class AppDelegateServiceTest extends ServiceTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        $config = $this->getApplicationConfig();
        $this->adapter = new Adapter($config['db']);
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
    }

    public function tearDown() : void
    {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $_REQUEST = [];
    }

    public function testDelegateExecute()
    {
        $data = array("Checking App Delegate","Checking1");
        $appId = 'debf3d35-a0ee-49d3-a8ac-8e480be9dac7';
        $config = $this->getApplicationConfig();
        $delegateService = new AppDelegateService($config, $this->adapter);
        $content = $delegateService->execute($appId, 'IndividualLiabilityImpl', $data);
        $this->assertEquals("Checking App Delegate", $content);
    }
}
