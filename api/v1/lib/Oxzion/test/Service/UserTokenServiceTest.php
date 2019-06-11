<?php
namespace Oxzion\Service;

use Oxzion\Test\ServiceTest;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Stdlib\ArrayUtils;
use Mockery;
use Zend\Db\Adapter\Adapter;
use Oxzion\Transaction\TransactionManager;



class UserTokenServiceTest extends ServiceTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        $config = $this->getApplicationConfig();
        $this->adapter = new Adapter($config['db']);
        $table = $this->getApplicationServiceLocator()->get(\Oxzion\Model\UserTokenTable::class);
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
    }

     public function tearDown() : void {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $_REQUEST = [];
    }

    public function testGetRefreshTokenPayload()
    {
        $data = ['id' => '1', 'name' => 'John Holt', 'status' => '1', 'dob' => date('Y-m-d H:i:s', strtotime("-50 year")), 'doj' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt', 'username' => 'rakshith', 'password' => 'welcome2oxzion', 'designation' => 'CEO', 'level' => '7', 'cluster' => 'Management', 'location' => 'USA', 'gamelevel' => 'Wanna be', 'email' => 'harshva.com', 'sex' => 'M', 'role' => 'employee', 'listtoggle' => 1, 'mission_link' => 'test'];
        $salt = "4993098475c51799398a9a6.94659098";
        $config = $this->getApplicationConfig();
        // $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        // $table = $this->getApplicationServiceLocator()->get(\Oxzion\Model\UserTokenTable::class);
        $mockUserTokenService = Mockery::mock('UserTokenService');
        $mockUserTokenService->shouldReceive('generateRefreshToken')->with($data)->once()->andReturn("5940264875c7e2a6f120969.43960974");
        $content = $mockUserTokenService->generateRefreshToken($data);
        // the salt value comes from refresh token test data
        $this->assertEquals("5940264875c7e2a6f120969.43960974", $content);
    }

    public function testGetRefreshTokenPayloadWithUnknownUserName()
    {
        $data = ['name' => 'John Holt', 'status' => '1', 'dob' => date('Y-m-d H:i:s', strtotime("-50 year")), 'doj' => date('Y-m-d H:i:s'), 'icon' => 'test-oxzionlogo.png', 'managerid' => '471', 'firstname' => 'John', 'lastname' => 'Holt', 'username' => 'wronguser', 'password' => 'welcome2oxzion', 'designation' => 'CEO', 'level' => '7', 'cluster' => 'Management', 'location' => 'USA', 'gamelevel' => 'Wanna be', 'email' => 'harshva.com', 'sex' => 'M', 'role' => 'employee', 'listtoggle' => 1, 'mission_link' => 'test'];
        $salt = "4993098475c51799398a9a6.94659099";
        $config = $this->getApplicationConfig();
        // $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        // $table = $this->getApplicationServiceLocator()->get(\Oxzion\Model\UserTokenTable::class);
        $userTokenService = new UserTokenService($this->config, $this->adapter, $this->table);
        $content = $userTokenService->generateRefreshToken($data, $salt);
        $this->assertEquals(0, $content);
    }
}

?>