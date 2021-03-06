<?php
use Oxzion\Test\DelegateTest;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;

class ImportTest extends DelegateTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        $this->data = array(
            "appName" => 'ox_client_app',
            'UUID' => 8765765,
            'description' => 'FirstAppOfTheClient',
        );
        $migrationFolder = __DIR__  . "/../data/migrations/";
        $this->doMigration($this->data,$migrationFolder);
        $path = __DIR__.'/../../../api/v1/data/delegate/'.$this->data['UUID'];
        if (!is_link($path)) {
            symlink(__DIR__.'/../data/delegate/', $path);
        }
        parent::setUp();
    }

    public function getDataSet()
    {
        return new DefaultDataSet();
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

    public function testImportExecute()
    {
        $orgId = 1;
        $appId = $this->data['UUID'];
        $appName = $this->data['appName'];
        $host = "oxzion.com";
        $userId = "rakshith@oxzion.com";
        $password = "sftp@rakshith";
        $data = ['stored_procedure_name' => 'ox_padi_verification', 'org_id' => $orgId, 'app_id' => $appId , 'app_name' => $appName, 'src_url'=>"http://", 'file_name' => "VB010B.csv", "host" => $host, "user_id" => $userId, "password" => $password];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'Import', $data);
        // print_r($content);exit;
        $this->assertEquals($content['app_name'], $data['app_name']);
    }


    public function testImportExecuteWithoutFileName()
    {
        $orgId = 1;
        $appId = $this->data['UUID'];
        $appName = $this->data['appName'];
        $host = "oxzion.com";
        $userId = "rakshith@oxzion.com";
        $password = "sftp@rakshith";
        $data = ['stored_procedure_name' => 'ox_padi_verification', 'org_id' => $orgId, 'app_id' => $appId, 'app_name' => $appName, 'src_url'=>"http://", "host" => $host, "user_id" => $userId, "password" => $password,  'file_name' => ""];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'Import', $data);
        $this->assertEquals($content['status'], "Error");
    }


    public function testImportExecuteWithSelectProcedureName()
    {
        $orgId = 1;
        $appId = $this->data['UUID'];
        $appName = $this->data['appName'];
        $host = "oxzion.com";
        $userId = "rakshith@oxzion.com";
        $password = "sftp@rakshith";
        $data = ['stored_procedure_name' => 'ox_test_verification', 'org_id' => $orgId, 'app_id' => $appId, 'app_name' => $appName, 'src_url'=>"http://", "host" => $host, "user_id" => $userId, "password" => $password,  'file_name' => ""];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'Import', $data);
        $this->assertEquals($content['status'], "Error");
        $this->assertEquals($content['data']['stored_procedure_name'], $data['stored_procedure_name']);
    }
}