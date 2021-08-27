<?php
use Oxzion\Test\DelegateTest;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\Db\Persistence\Persistence;

class ChangeLogDelegateTest extends DelegateTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        $this->config = $this->getApplicationConfig();
        $this->data = array(
            "appName" => 'ox_client_app',
            'UUID' => 8765765,
            'description' => 'FirstAppOfTheClient',
            'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a',
        );
        $migrationFolder = __DIR__  . "/../data/migrations/";
        $this->doMigration($this->data,$migrationFolder);
        $path = __DIR__.'/../../../api/v1/data/delegate/'.$this->data['UUID'];
        if (!is_link($path)) {
            symlink(__DIR__.'/../data/delegate/',$path);
        }
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/Dataset/Workflow.yml");
        return $dataset;
    }

    public function tearDown() : void
    {
        parent::tearDown();
        $path = __DIR__.'/../../../api/v1/data/delegate/'.$this->data['UUID'];
        if (is_link($path)) {
            unlink($path);
        }
         $query = "DROP DATABASE " . $this->database;
         $statement = $this->getDbAdapter()->query($query);
         $result = $statement->execute();

    }

    public function testChangeLogDelegateExecuteWithActivityInstanceId()
    {
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        $data =['product' =>'Individual Professional Liability','activityInstanceId' => '3f6622fd-0124-11ea-a8a0-22e8105c0778'];
        $appId = $this->data['UUID'];
        $appName = $this->data['appName'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'ChangeLogDelegate', $data);  
        $this->assertEquals(8, count($content));
        $select = "SELECT distinct `type`,data_type from ox_field of inner join ox_form_field off on off.field_id = of.id";
        $statement = $this->getDbAdapter()->query($select);
        $result = $statement->execute();
        while ($result->next()) {
            $tableFieldName[] = $result->current();
        }
        $this->assertEquals(6, count($tableFieldName));
    }

    public function testChangeLogDelegateExecuteWithWorkflowInstanceId()
    {
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        $data =['product' =>'Individual Professional Liability','activityInstanceId' => null,'workflowInstanceId' => '3f20b5c5-0124-11ea-a8a0-22e8105c0790'];
        $appId = $this->data['UUID'];
        $appName = $this->data['appName'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'ChangeLogDelegate', $data);
        $this->assertEquals(2, count($content));
    }

    public function testChangeLogNotFound()
    {
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        $data =['product' =>'Individual Professional Liability','activityInstanceId' => null,'workflowInstanceId' => null];
        $appId = $this->data['UUID'];
        $appName = $this->data['appName'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'ChangeLogDelegate', $data);
        $this->assertEquals($data['product'], $content['product']);
    }
}
