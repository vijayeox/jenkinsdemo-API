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
use Oxzion\Db\Migration\Migration;
use Oxzion\Db\Persistence\Persistence;

class AppDelegateServiceTest extends ServiceTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        $this->data = array(
            "appName" => 'ox_client_app',
            'UUID' => 8765765,
            'description' => 'FirstAppOfTheClient',
        );

        $config = $this->getApplicationConfig();
        $migrationObject = new Migration($config, $this->data['appName'], $this->data['UUID']);
        $this->adapter = $migrationObject->getAdapter();
        $this->persistence = new Persistence($config, $this->data['UUID'], $this->data['appName']);
        $this->persistence->setAdapter($this->adapter);
        $this->database = $migrationObject->getDatabase();
        $migrationObject->initDB($this->data);
        $dataSet = array_diff(scandir(__DIR__ . "/../../../../../../clients/Dive Insurance/data/migrations/"), array(".", ".."));
        $migrationFolder = __DIR__  . "/../../../../../../clients/Dive Insurance/data/migrations/";
        $testCase = $migrationObject->migrationSql($dataSet, $migrationFolder, $this->data);
        $tm = TransactionManager::getInstance($this->adapter);
        $path = __DIR__.'/../../../../data/delegate/'.$this->data['UUID'];
        if (!is_link($path)) {
            symlink(__DIR__.'/../../../../../../clients/Dive Insurance/data/delegate/',$path);
        }
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();               
    }

    public function tearDown() : void
    {
        $tm = TransactionManager::getInstance($this->adapter);
        $path = __DIR__.'/../../../../data/delegate/'.$this->data['UUID'];
        if (is_link($path)) {
            unlink($path);
        }
        $tm->rollback();
        $query = "DROP DATABASE " . $this->database;
        $statement = $this->adapter->query($query);
        $result = $statement->execute();
        $_REQUEST = [];
    }

    public function testDelegateExecute()
    {
        $data = array("Checking App Delegate","Checking1");
        $appId = $this->data['UUID'];
        $appName = $this->data['appName'];
        $config = $this->getApplicationConfig();
        
        $delegateService = new AppDelegateService($this->getApplicationConfig(),$this->adapter, 
                                $this->getApplicationServiceLocator()->get(\Oxzion\Document\DocumentBuilder::class));
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'IndividualLiabilityImpl', $data);
        $this->assertEquals("Checking App Delegate", $content[0]);
    }

    public function testRateCardExecute()
    {
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 3);
        $data =['product' => 'Individual Professional Liability', 'start_date' => '2019-06-30','end_date' => '2019-07-31'];
        $appId = $this->data['UUID'];
        $appName = $this->data['appName'];
        $config = $this->getApplicationConfig();
        
        $delegateService = new AppDelegateService($this->getApplicationConfig(),$this->adapter, 
                                $this->getApplicationServiceLocator()->get(\Oxzion\Document\DocumentBuilder::class));
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'RateCard', $data);
        $this->assertEquals($content[0]['product'], $data['product']);
    }
}
