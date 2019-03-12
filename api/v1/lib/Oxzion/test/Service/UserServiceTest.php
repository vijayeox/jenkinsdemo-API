<?php
namespace Oxzion\Service;
use Zend\Stdlib\ArrayUtils;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Test\ServiceTest;
use Email\Service\EmailService;

class UserServiceTest extends ServiceTest {

    public function setUp() : void {
        $this->loadConfig();
        parent::setUp();
    }

    protected function loadConfig() {
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/autoload/global.php', include __DIR__ . '/../../../../config/autoload/local.php');
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php',$configOverrides);
        $this->setApplicationConfig($configOverrides);
    }

    private function getUserService(){
        $config = $this->getApplicationConfig();
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $table = $this->getApplicationServiceLocator()->get(\Oxzion\Model\UserTable::class);
        $email = $this->getApplicationServiceLocator()->get(EmailService::class);
        $userService = new UserService($config, $dbAdapter, $table, $email);
        return $userService;
    }
    public function testGetPrivileges(){
        $userService = $this->getUserService();
        $data = $userService->getPrivileges(1);
        $this->assertEquals(isset($data), true);
        $this->assertEquals(count($data) > 0, true);
    }
}
?>