<?php
namespace Oxzion\Service;
use Zend\Stdlib\ArrayUtils;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Test\ServiceTest;
use Email\Service\EmailService;

class MailServiceTest extends ServiceTest {

    public function setUp() : void {
        $this->loadConfig();
        parent::setUp();
    }

    protected function loadConfig() {
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/autoload/global.php', include __DIR__ . '/../../../../config/autoload/local.php');
        $configOverrides = ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php',$configOverrides);
        $this->setApplicationConfig($configOverrides);
    }

    public function testTwoEmailsAreSent()
    {
        $config = $this->getApplicationConfig();
        $dbAdapter = $this->getApplicationServiceLocator()->get(AdapterInterface::class);
        $table = $this->getApplicationServiceLocator()->get(\Oxzion\Model\UserTokenTable::class);
        
        $fileMails = $this->getEmails();
        $this->assertEquals(2, count($fileMails));
    }
}
?>