<?php
namespace Oxzion\Service;

use Zend\Stdlib\ArrayUtils;
use Oxzion\Test\ServiceTest;
use Oxzion\Service\EmailService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\TemplateService;
use Oxzion\Transaction\TransactionManager;
use Zend\Db\Adapter\Adapter;



class UserServiceTest extends ServiceTest {

    public function setUp() : void{
        $this->loadConfig();
        // parent::setUp();
        $config = $this->getApplicationConfig();
        $this->adapter = new Adapter($config['db']);
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
    
    }

    public function tearDown() : void {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $_REQUEST = [];
    }


    private function getUserService(){
        return new UserService(
            $this->config,
            $this->adapter,
            $this->getApplicationServiceLocator()->get(\Oxzion\Model\UserTable::class),
            $this->getApplicationServiceLocator()->get(EmailService::class),
            $this->getApplicationServiceLocator()->get(TemplateService::class)
        );
    }

    public function testGetPrivileges(){
        $data = $this->getUserService()->getPrivileges(1,1);
        $this->assertEquals(isset($data), true);
        $this->assertEquals(count($data) > 0, true);
    }
}
?>