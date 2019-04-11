<?php
namespace Oxzion\Service;

use Zend\Stdlib\ArrayUtils;
use Oxzion\Test\ServiceTest;
use Oxzion\Service\EmailService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\EmailTemplateService;

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
        return new UserService(
            $this->getApplicationConfig(),
            $this->getApplicationServiceLocator()->get(AdapterInterface::class),
            $this->getApplicationServiceLocator()->get(\Oxzion\Model\UserTable::class),
            $this->getApplicationServiceLocator()->get(EmailService::class),
            $this->getApplicationServiceLocator()->get(EmailTemplateService::class)
        );
    }

    public function testGetPrivileges(){
        $data = $this->getUserService()->getPrivileges(1);
        $this->assertEquals(isset($data), true);
        $this->assertEquals(count($data) > 0, true);
    }
}
?>