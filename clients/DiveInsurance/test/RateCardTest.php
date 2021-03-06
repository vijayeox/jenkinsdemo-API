<?php
use Oxzion\Test\DelegateTest;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;

class RateCardTest extends DelegateTest
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
            symlink(__DIR__.'/../data/delegate/',$path);
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

    public function testRateCardExecute()
    {
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 3);
        $data =['key' => 'instructor','product' =>'Individual Professional Liability', 'start_date' => '2019-07-01','end_date' => '2019-07-31'];
        $appId = $this->data['UUID'];
        $appName = $this->data['appName'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'RateCard', $data);
        $this->assertEquals($content['product'], $data['product']);
    }


    public function testEndorsementRateCardExecute()
    {
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 3);
        $data =['careerCoverage' => 'divemasterAssistantInstructorAssistingOnly' ,'product' =>'Individual Professional Liability', 'update_date' => '2020-06-01'];
        $appId = $this->data['UUID'];
        $appName = $this->data['appName'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'EndorsementRateCard', $data);
        $this->assertEquals($content['product'], $data['product']);
    }
}