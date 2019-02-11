<?php
namespace Oxzion\Service;

use Oxzion\Test\ServiceTest;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Stdlib\ArrayUtils;

class UserTokenServiceTest extends ServiceTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    protected function loadConfig()
    {
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/autoload/global.php', include __DIR__ . '/../../../../config/autoload/local.php');
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php', $configOverrides);
        $this->setApplicationConfig($configOverrides);
    }

    public function testGetRefreshTokenPayload()
    {
        $data = ['id' => '1', 'name' => 'John Holt', 'status' => '1', 'dob' => date('Y-m-d H:i:s', strtotime("-50 year")), 'doj' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt', 'username' => 'rakshith', 'password' => 'welcome2oxzion', 'designation' => 'CEO', 'level' => '7', 'cluster' => 'Management', 'location' => 'USA', 'gamelevel' => 'Wanna be', 'email' => 'harshva.com', 'sex' => 'M', 'role' => 'employee', 'listtoggle' => 1, 'mission_link' => 'test'];
        $salt = "4993098475c51799398a9a6.94659098";
        $config = $this->getApplicationConfig();
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $table = $this->getApplicationServiceLocator()->get(\Oxzion\Model\UserTokenTable::class);
        $userTokenService = new UserTokenService($config, $dbAdapter, $table);
        $content = $userTokenService->getRefreshTokenPayload($data, $salt);
        $this->assertEquals(1, $content);
    }

    public function testGetRefreshTokenPayloadWithUnknownUserName()
    {
        $data = ['name' => 'John Holt', 'status' => '1', 'dob' => date('Y-m-d H:i:s', strtotime("-50 year")), 'doj' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt', 'username' => 'wronguser', 'password' => 'welcome2oxzion', 'designation' => 'CEO', 'level' => '7', 'cluster' => 'Management', 'location' => 'USA', 'gamelevel' => 'Wanna be', 'email' => 'harshva.com', 'sex' => 'M', 'role' => 'employee', 'listtoggle' => 1, 'mission_link' => 'test'];
        $salt = "4993098475c51799398a9a6.94659099";
        $config = $this->getApplicationConfig();
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $table = $this->getApplicationServiceLocator()->get(\Oxzion\Model\UserTokenTable::class);
        $userTokenService = new UserTokenService($config, $dbAdapter, $table);
        $content = $userTokenService->getRefreshTokenPayload($data, $salt);
        $this->assertEquals(0, $content);
    }
}

?>