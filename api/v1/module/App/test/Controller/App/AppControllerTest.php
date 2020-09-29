<?php
namespace App;

use App\Controller\AppController;
use App\Controller\AppRegisterController;
use Oxzion\Service\AppService;
use Mockery;
use Oxzion\Test\ControllerTest;
use Oxzion\Utils\FileUtils;
use Oxzion\App\AppArtifactNamingStrategy;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Symfony\Component\Yaml\Yaml;
use Exception;
use AppTest\AppTestSetUpTearDownHelper;

class AppControllerTest extends ControllerTest
{
    private $setUpTearDownHelper = NULL;
    private $config = NULL;

    function __construct() {
        parent::__construct();
        $this->loadConfig();
        $this->config = $this->getApplicationConfig();
        $this->setUpTearDownHelper = new AppTestSetUpTearDownHelper($this->config);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpTearDownHelper->cleanAll();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->setUpTearDownHelper->cleanAll();
    }

    public function getDataSet()
    {
        //These tests don't need data set.
        switch($this->getName()) {
            case 'testCreateWithUserGeneratedUuid':
            case 'testCreateWithServerGeneratedUuid':
            case 'testCreateWithoutRequiredData':
                //Return empty data set to keep framework happy!
                return new YamlDataSet(dirname(__FILE__) . "/../../Dataset/EmptyDataSet.yml");;
            break;
        }

        $dataset = new YamlDataSet(dirname(__FILE__) . "/../../Dataset/Workflow.yml");
        switch($this->getName()) {
            case 'testDeployAppWithWrongUuidAndDuplicateNameInDatabase':
            case 'testDeployAppWithWrongUuidAndUniqueNameInDatabase':
            case 'testDeployAppWithWrongNameInDatabase':
            case 'testDeployAppWithNameAndNoUuidInYMLButNameandUuidInDatabase':
            case 'testDeployAppAddExtraPrivilegesInDatabaseFromYml':
            case 'testDeployAppDeleteExtraPrivilegesInDatabaseNotInYml':
                $dataset->addYamlFile(dirname(__FILE__) . "/../../Dataset/App2.yml");
            break;
        }

        return $dataset;
    }

    public function getMockProcessManager()
    {
        $mockProcessManager = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessManagerImpl');
        $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowService::class);
        $workflowService->setProcessManager($mockProcessManager);
        return $mockProcessManager;
    }

    private function getMockRestClientForScheduleService()
    {
        $taskService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\JobService::class);
        $mockRestClient = Mockery::mock('Oxzion\Utils\RestClient');
        $taskService->setRestClient($mockRestClient);
        return $mockRestClient;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $contentTypeHeader = $this->getResponseHeader('content-type')->toString();
        $contentTypeRegex = '/application\/json(;? *?charset=utf-8)?/i';
        $this->assertTrue(preg_match($contentTypeRegex, $contentTypeHeader) ? true : false);
    }

    public function testGetListOfAssignments()
    {
        $this->initAuthToken($this->adminUser);
        $product = 'Individual Professional Liability';
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/assignments?filter=[{"filter":{"filters":[{"field":"product","operator":"eq","value":"' . $product . '"}]},"skip":0,"take":10}]', 'GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class);
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('assignments');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['product'], $product);
        $this->assertEquals($content['total'], 1);
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEquals($content['data'], array());
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content['data']['app']['uuid']);
        $this->assertEquals($content['data']['app']['name'], 'SampleApp');
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbijkop', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetAppList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/a', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applist');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 10);
        $this->assertEquals($content['data'][0]['name'], 'Admin');
        $this->assertEquals($content['total'], 10);
    }

    public function testGetAppListWithQuery()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/a?filter=[{"filter":{"logic":"and","filters":[{"field":"name","operator":"startswith","value":"a"},{"field":"category","operator":"contains","value":"utilities"}]},"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applist');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['name'], 'Admin');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetAppListWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/a?filter=[{"skip":0,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applist');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['name'], 'Admin');
        $this->assertEquals($content['data'][1]['name'], 'Analytics');
        $this->assertEquals($content['total'], 10);
    }

    public function testGetAppListWithPageSize2()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/a?filter=[{"skip":2,"take":2}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applist');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['name'], 'AppBuilder');
        $this->assertEquals($content['data'][1]['name'], 'CRM');
        $this->assertEquals($content['total'], 10);
    }

    public function testCreateWithUserGeneratedUuid() {
        $this->initAuthToken($this->adminUser);
        $uuid = '11111111-1111-1111-1111-111111111111';
        $query = "SELECT id, uuid, name FROM ox_app WHERE uuid='${uuid}'";
        //Ensure there is no ox_app record matching given UUID.
        $existingRecordSet = $this->executeQueryTest($query);
        $this->assertTrue(empty($existingRecordSet));
        //Send request and create the record.
        $data = [
            'app' => [
                'name' => 'TestApp-1',
                'uuid' => $uuid,
                'description' => 'App for testing App API',
                'category' => 'EXAMPLE_CATEGORY',
                'type' => 2,
                'autostart' => true
            ]
        ];
        $this->dispatch('/app', 'POST', $data);
        //Assert response status etc.
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        //Check new record is created in the database.
        $newRecordSet = $this->executeQueryTest($query);
        $this->assertEquals(1, count($newRecordSet));
        $newRecord = $newRecordSet[0];
        $this->assertFalse(empty($newRecord));
        $this->assertEquals($uuid, $newRecord['uuid']);
        $this->assertNotEmpty($newRecord['id']);
        $this->assertEquals($data['app']['name'], $newRecord['name']);
        //Check returned data is as expected.
        $returnData = $content['data'];
        $this->assertTrue(array_key_exists('app', $returnData));
        $appData = $returnData['app'];
        $this->assertEquals($data['app']['name'], $appData['name']);
        $this->assertEquals($uuid, $appData['uuid']);
        $this->assertEquals('default_app.png', $appData['logo']);
        $this->assertEquals(2, $appData['status']);
        $this->assertEquals('', $appData['start_options']);
        //Check application descriptor is created and is as expected.
        $srcAppDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $data['app']);
        $this->assertTrue(file_exists($srcAppDir));
        $appDescriptorFilePath = $srcAppDir . DIRECTORY_SEPARATOR . AppService::APPLICATION_DESCRIPTOR_FILE_NAME;
        $yamlData = Yaml::parse(file_get_contents($appDescriptorFilePath));
        $this->assertEquals($returnData, $yamlData);
    }

    public function testCreateWithServerGeneratedUuid()
    {
        $this->initAuthToken($this->adminUser);
        $data = [
            'app' => [
                'name' => 'App1', 
                'type' => 2, 
                'category' => 'EXAMPLE_CATEGORY'
            ]
        ];
        $query = "SELECT id, uuid, name FROM ox_app WHERE name='" . $data['app']['name'] . "'";
        //Ensure there is no ox_app record matching given UUID.
        $existingRecordSet = $this->executeQueryTest($query);
        $this->assertTrue(empty($existingRecordSet));
        //Send request and create the record.
        $this->dispatch('/app', 'POST', $data);
        //Assert response status etc.
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        //Check new record is created in the database.
        $newRecordSet = $this->executeQueryTest($query);
        $this->assertEquals(1, count($newRecordSet));
        $newRecord = $newRecordSet[0];
        $this->assertFalse(empty($newRecord));
        $this->assertNotEmpty($newRecord['uuid']);
        $this->assertEquals(36, strlen($newRecord['uuid']));
        $this->assertNotEmpty($newRecord['id']);
        $this->assertEquals($data['app']['name'], $newRecord['name']);
        //Check returned data is as expected.
        $returnData = $content['data'];
        $this->assertTrue(array_key_exists('app', $returnData));
        $appData = $returnData['app'];
        $this->assertEquals($data['app']['name'], $appData['name']);
        $this->assertEquals($data['app']['type'], $appData['type']);
        $this->assertEquals($data['app']['category'], $appData['category']);
        $this->assertEquals('', $appData['description']);
        $this->assertEquals(0, $appData['isdefault']);
        $this->assertEquals('default_app.png', $appData['logo']);
        $this->assertEquals(2, $appData['status']);
        $this->assertEquals('', $appData['start_options']);
        //Check application descriptor is created and is as expected.
        $srcAppDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $appData);
        $this->assertTrue(file_exists($srcAppDir));
        $appDescriptorFilePath = $srcAppDir . DIRECTORY_SEPARATOR . AppService::APPLICATION_DESCRIPTOR_FILE_NAME;
        $yamlData = Yaml::parse(file_get_contents($appDescriptorFilePath));
        $this->assertEquals($returnData, $yamlData);
    }

    public function testCreateWithoutRequiredData()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['type' => 2, 'account_id' => 4];
        $query = "SELECT id, name FROM ox_app ORDER BY id ASC";
        //Take a snapshot of ox_app records.
        $existingRecordSet = $this->executeQueryTest($query);
        $this->dispatch('/app', 'POST', $data);
        $newRecordSet = $this->executeQueryTest($query);
        //Assert response status etc.
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation error(s).');
        $this->assertEquals($content['data']['errors']['name']['error'], 'required');
        //Take new shapshot of ox_app and ensure no deletions and additions have happened.
        $this->assertEquals($existingRecordSet, $newRecordSet);
    }

    public function testCreateWithoutAccessPermission()
    {
        $this->initAuthToken($this->employeeUser);
        $data = [
            'app' => [
                'name' => 'AccessPermissionCheckApp', 
                'type' => 2, 
                'category' => 'EXAMPLE_CATEGORY', 
                'logo' => 'app.png'
            ]
        ];
        $query = "SELECT id, name FROM ox_app ORDER BY id ASC";
        //Take a snapshot of ox_app records.
        $existingRecordSet = $this->executeQueryTest($query);
        $this->dispatch('/app', 'POST', $data);
        //Assert response status etc.
        $this->assertResponseStatusCode(401);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
        //Take new shapshot of ox_app and ensure no deletions and additions have happened.
        $newRecordSet = $this->executeQueryTest($query);
        $this->assertEquals($existingRecordSet, $newRecordSet);
    }

    public function testDeployApp()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application1.yml');
        $this->initAuthToken($this->adminUser);
        if (enableCamundaForDeployApp == 0) {
            $mockProcessManager = $this->getMockProcessManager();
            $mockProcessManager->expects('deploy')->withAnyArgs()->once()->andReturn(array('Process_1dx3jli:1eca438b-007f-11ea-a6a0-bef32963d9ff'));
            $mockProcessManager->expects('parseBPMN')->withAnyArgs()->once()->andReturn(null);
        }
        if (enableExecUtils == 0) {
            $mockBosUtils = Mockery::mock('alias:\Oxzion\Utils\ExecUtils');
            $mockBosUtils->expects('randomPassword')->withAnyArgs()->once()->andReturn('12345678');
            $mockBosUtils->expects('execCommand')->withAnyArgs()->times(3)->andReturn();
        }
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("setupjob", Mockery::any())->once()->andReturn(array('body' => '{"Success":true,"Message":"Job Scheduled Successfully!","JobId":"3a289705-763d-489a-b501-0755b9d4b64b","JobGroup":"autoRenewalJob"}'));
        }
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app']['name'];
        $YmlappUuid = $yaml['app']['uuid'];
        $query = "SELECT name from ox_app where name = '" . $appName . "'";
        $appname = $this->executeQueryTest($query);
        $query = "SELECT uuid from ox_app where name = '" . $appName . "'";
        $appUuid = $this->executeQueryTest($query);
        $appUuidCount = count($appUuid[0]);
        $appUuid = $appUuid[0]['uuid'];
        $query = "SELECT id from ox_app where uuid = '" . $appUuid . "'";
        $appId = $this->executeQueryTest($query);
        $appId = $appId[0]['id'];
        $query = "SELECT count(name),status,uuid,id from ox_account where name = '" . $yaml['org']['name'] . "'";
        $account = $this->executeQueryTest($query);
        $query = "SELECT count(id) as count from ox_app_registry where app_id = '" . $appId . "'";
        $appRegistryResult = $this->executeQueryTest($query);
        $query = "SELECT count(name) as count FROM ox_privilege WHERE app_id = '" . $appId . "'";
        $privilege = $this->executeQueryTest($query);
        $query = "SELECT count(privilege_name) as count from ox_role_privilege WHERE app_id = '" . $appId . "'";
        $rolePrivilege = $this->executeQueryTest($query);
        $query = "SELECT count(id) as count from ox_role WHERE account_id = '" . $account[0]['id'] . "'";
        $role = $this->executeQueryTest($query);
        $query = "SELECT count(role_id) as count FROM ox_role_privilege WHERE privilege_name = 'MANAGE_MY_POLICY2' and app_id = '" . $appId . "'";
        $roleprivilege1 = $this->executeQueryTest($query);
        $query = "SELECT count(role_id) as count FROM ox_role_privilege WHERE privilege_name = 'MANAGE_MY_POLICY' and app_id = '" . $appId . "'";
        $roleprivilege2 = $this->executeQueryTest($query);
        $query = "SELECT count(role_id) as count FROM ox_role_privilege WHERE privilege_name = 'MANAGE_POLICY_APPROVAL' and app_id = '" . $appId . "'";
        $roleprivilege3 = $this->executeQueryTest($query);
        $query = "SELECT count(id) as count FROM ox_form WHERE app_id = " . $appId . " and name = 'sampleFormForTests'";
        $form = $this->executeQueryTest($query);
        $query = "SELECT count(id) as count FROM ox_app_menu WHERE app_id = " . $appId;
        $menu = $this->executeQueryTest($query);
        $this->assertEquals($menu[0]['count'], 6);
        $this->assertEquals($form[0]['count'], 1);
        $this->assertEquals($roleprivilege1[0]['count'], 2);
        $this->assertEquals($roleprivilege2[0]['count'], 2);
        $this->assertEquals($roleprivilege3[0]['count'], 2);
        $this->assertEquals($role[0]['count'], 5);
        $this->assertEquals($privilege[0]['count'], 3);
        $this->assertEquals($rolePrivilege[0]['count'], 6);
        $this->assertEquals($account[0]['uuid'], $yaml['org']['uuid']);
        $this->assertEquals($appname[0]['name'], $appName);
        $this->assertEquals($appUuid, $YmlappUuid);
        $this->assertEquals($appUuidCount, 1);
        $this->assertEquals($appRegistryResult[0]['count'], 1);
        $this->assertEquals($content['status'], 'success');
        $template = $this->config['TEMPLATE_FOLDER'] . $account[0]['uuid'];
        $delegate = $this->config['DELEGATE_FOLDER'] . $appUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        $apps = $this->config['APPS_FOLDER'];
        if (enableExecUtils != 0) {
            if (file_exists($apps) && is_dir($apps)) {
                if (is_link($apps . "/$appName")) {
                    $dist = "/dist/";
                    $nodemodules = "/node_modules/";
                    $this->assertEquals(file_exists($apps . "/$appName" . $dist), true);
                    $this->assertEquals(file_exists($apps . "/$appName" . $nodemodules), true);
                }
            }
        }
        $query = "SELECT * from ox_workflow where app_id = " . $appId;
        $workflow = $this->executeQueryTest($query);
        if (enableCamundaForDeployApp == 1) {
            $this->assertEquals(count($workflow), 3);
            foreach ($workflow as $wf) {
                $this->assertNotEmpty($wf['process_id']);
            }
        }
    }

    public function testDeployAppWithFieldValidation(){
        $this->setUpTearDownHelper->setupAppDescriptor('application12.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app']['name'];
        $YmlappUuid = $yaml['app']['uuid'];
        $query = "SELECT name, uuid from ox_app where name = '" . $appName . "'";
        $appdata = $this->executeQueryTest($query);
        $this->assertEquals($appdata[0]['name'], $appName);
        $this->assertEquals($appdata[0]['uuid'], $YmlappUuid);
        $this->assertEquals($content['status'], 'success');
        $delegate = $this->config['DELEGATE_FOLDER'] . $YmlappUuid;
        $query = "SELECT uuid from ox_app where name = '" . $appName . "'";
        $appUuid = $this->executeQueryTest($query);
        $appUuidCount = count($appUuid[0]);
        $appUuid = $appUuid[0]['uuid'];
        $query = "SELECT id from ox_app where uuid = '" . $appUuid . "'";
        $appId = $this->executeQueryTest($query);
        $appId = $appId[0]['id'];
        $query = "SELECT count(id) as count FROM ox_form WHERE app_id = " . $appId;
        $form = $this->executeQueryTest($query);
        $this->assertEquals($form[0]['count'], 1);
    }

    private function unlinkFolders($appUuid, $appName, $orgUuid = null)
    {
        $file = $this->config['DELEGATE_FOLDER'] . $appUuid;
        if (is_link($file)) {
            unlink($file);
        }
        if ($orgUuid) {
            $file = $this->config['TEMPLATE_FOLDER'] . $orgUuid;
            if (is_link($file)) {
                unlink($file);
            }
        }
        $appName = str_replace(' ', '', $appName);
        $app = $this->config['APPS_FOLDER'] . $appName;
        if (is_link($app)) {
            unlink($app);
        }
        $appData = [
            'name' => $appName,
            'uuid' => $appUuid
        ];
        $appSrcDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->config, $appData);
        if (file_exists($appSrcDir)) {
            FileUtils::rmDir($appSrcDir);
        }
        $appDestDir = AppArtifactNamingStrategy::getDeployAppDirectory($this->config, $appData);
        if (file_exists($appDestDir)) {
            FileUtils::rmDir($appDestDir);
        }
    }

    public function testDeployAppWithoutOptionalFieldsInYml()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application5.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app']['name'];
        $YmlappUuid = $yaml['app']['uuid'];
        $query = "SELECT name, uuid from ox_app where name = '" . $appName . "'";
        $appdata = $this->executeQueryTest($query);
        $this->assertEquals($appdata[0]['name'], $appName);
        $this->assertEquals($appdata[0]['uuid'], $YmlappUuid);
        $this->assertEquals($content['status'], 'success');
        $delegate = $this->config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($delegate), true);
        $apps = $this->config['APPS_FOLDER'];
        if (file_exists($apps) && is_dir($apps)) {
            if (is_link($apps . "/$appName")) {
                $dist = "/dist/";
                $nodemodules = "/node_modules/";
                $this->assertEquals(file_exists($apps . "/$appName" . $dist), false);
                $this->assertEquals(file_exists($apps . "/$appName" . $nodemodules), false);
            }
        }
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app']['name'];
        try {
            FileUtils::rmDir($appname);
        } catch (Exception $e){
            
        }
        $this->unlinkFolders($YmlappUuid, $appname);
    }

    public function testDeployAppWithWrongUuidAndUniqueNameInDatabase()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application14.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        unlink(__DIR__ . '/../../sampleapp/application.yml');

        $query = 'SELECT name, uuid FROM ox_app WHERE id=(SELECT max(id) from ox_app)';
        $latestAppData = $this->executeQueryTest($query)[0];
    }

    public function testDeployAppWithWrongNameInDatabase()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application9.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app']['name'];
        $YmlappUuid = $yaml['app']['uuid'];
        $query = "SELECT name, uuid from ox_app where name = '" . $appName . "'";
        $appdata = $this->executeQueryTest($query);
        $this->assertEquals($appdata[0]['name'], $appName);
        $this->assertEquals($appdata[0]['uuid'], $YmlappUuid);
        $this->assertEquals($content['status'], 'success');
        $query = "SELECT count(name),status,uuid from ox_account where name = '" . $yaml['org']['name'] . "'";
        $account = $this->executeQueryTest($query);
        $this->assertEquals($account[0]['uuid'], $yaml['org']['uuid']);
        $template = $this->config['TEMPLATE_FOLDER'] . $account[0]['uuid'];
        $delegate = $this->config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        if (!isset($yaml['org']['uuid'])) {
            $yaml['org']['uuid'] = null;
        }
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app']['name'];
        try {
            FileUtils::rmDir($appname);
        } catch (Exception $e){
            
        }
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org']['uuid']);
    }

    public function testDeployAppWithNameAndNoUuidInYMLButNameandUuidInDatabase()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application10.yml');
        $this->initAuthToken($this->adminUser);
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("setupjob", Mockery::any())->once()->andReturn(array('body' => '{"Success":true,"Message":"Job Scheduled Successfully!","JobId":"3a289705-763d-489a-b501-0755b9d4b64b","JobGroup":"autoRenewalJob"}'));
        }
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $this->assertEquals(isset($yaml['app']['uuid']), true);
        $appName = $yaml['app']['name'];
        $YmlappUuid = $yaml['app']['uuid'];
        $query = "SELECT name, uuid from ox_app where name = '" . $appName . "'";
        $appdata = $this->executeQueryTest($query);
        $this->assertEquals($appdata[0]['name'], $appName);
        $this->assertEquals($appdata[0]['uuid'], $YmlappUuid);
        $this->assertEquals($content['status'], 'success');
        $query = "SELECT count(name),status,uuid from ox_account where name = '" . $yaml['org']['name'] . "'";
        $account = $this->executeQueryTest($query);
        $this->assertEquals($account[0]['uuid'], $yaml['org']['uuid']);
        $template = $this->config['TEMPLATE_FOLDER'] . $account[0]['uuid'];
        $delegate = $this->config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app']['name'];
        try {
            FileUtils::rmDir($appname);
        } catch (Exception $e){
            
        }
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org']['uuid']);
    }

    public function testDeployAppNoDirectory()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp1/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDeployAppNoFile()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp2/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDeployAppNoFileData()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application2.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(500);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        unlink(__DIR__ . '/../../sampleapp/application.yml');
    }

    public function testDeployAppNoAppData()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application3.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(500);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        unlink(__DIR__ . '/../../sampleapp/application.yml');
    }

    public function testDeployAppOrgDataWithoutUuidAndContactAndPreferencesInYml()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application4.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("setupjob", Mockery::any())->once()->andReturn(array('body' => '{"Success":true,"Message":"Job Scheduled Successfully!","JobId":"3a289705-763d-489a-b501-0755b9d4b64b","JobGroup":"autoRenewalJob"}'));
        }
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app']['name'];
        $YmlappUuid = $yaml['app']['uuid'];
        $this->assertNotEmpty($yaml['org']['uuid']);
        $this->assertNotEmpty($yaml['org']['contact']);
        $this->assertEquals($yaml['org']['preferences'], '{}');
        $this->assertEquals($content['status'], 'success');
        $query = "SELECT count(name),status,uuid from ox_account where name = '" . $yaml['org']['name'] . "'";
        $account = $this->executeQueryTest($query);
        $this->assertEquals($account[0]['uuid'], $yaml['org']['uuid']);
        $template = $this->config['TEMPLATE_FOLDER'] . $account[0]['uuid'];
        $delegate = $this->config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app']['name'];
        try {
            FileUtils::rmDir($appname);
        } catch (Exception $e){
            
        }
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org']['uuid']);
    }

    public function testDeployAppAddExtraPrivilegesInDatabaseFromYml()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application6.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("setupjob", Mockery::any())->once()->andReturn(array('body' => '{"Success":true,"Message":"Job Scheduled Successfully!","JobId":"3a289705-763d-489a-b501-0755b9d4b64b","JobGroup":"autoRenewalJob"}'));
        }
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app']['name'];
        $YmlappUuid = $yaml['app']['uuid'];
        $privilegearray = array_unique(array_column($yaml['privilege'], 'name'));
        $appid = "SELECT id FROM ox_app WHERE name = '" . $yaml['app']['name'] . "'";
        $idresult = $this->executeQueryTest($appid);
        $queryString = "SELECT name FROM ox_privilege WHERE app_id = '" . $idresult[0]['id'] . "'";
        $result = $this->executeQueryTest($queryString);
        $DBprivilege = array_unique(array_column($result, 'name'));
        $query = "SELECT count(name),status,uuid from ox_account where name = '" . $yaml['org']['name'] . "'";
        $account = $this->executeQueryTest($query);
        $this->assertEquals($account[0]['uuid'], $yaml['org']['uuid']);
        $this->assertEquals($privilegearray, $DBprivilege);
        $this->assertEquals($content['status'], 'success');
        $template = $this->config['TEMPLATE_FOLDER'] . $account[0]['uuid'];
        $delegate = $this->config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app']['name'];
        try {
            FileUtils::rmDir($appname);
        } catch (Exception $e){
            
        }
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org']['uuid']);
    }

    public function testDeployAppDeleteExtraPrivilegesInDatabaseNotInYml()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application6.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("setupjob", Mockery::any())->once()->andReturn(array('body' => '{"Success":true,"Message":"Job Scheduled Successfully!","JobId":"3a289705-763d-489a-b501-0755b9d4b64b","JobGroup":"autoRenewalJob"}'));
        }
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app']['name'];
        $YmlappUuid = $yaml['app']['uuid'];
        $appid = "SELECT id FROM ox_app WHERE name = '" . $yaml['app']['name'] . "'";
        $idresult = $this->executeQueryTest($appid);
        $queryString = "SELECT name FROM ox_privilege WHERE app_id = '" . $idresult[0]['id'] . "'";
        $result = $this->executeQueryTest($queryString);
        $DBprivilege = array_unique(array_column($result, 'name'));
        $list = "'" . implode("', '", $DBprivilege) . "'";
        $query = "SELECT count(name),status,uuid from ox_account where name = '" . $yaml['org']['name'] . "'";
        $account = $this->executeQueryTest($query);
        $this->assertEquals($account[0]['uuid'], $yaml['org']['uuid']);
        $this->assertNotEquals($list, 'MANAGE');
        $this->assertEquals($content['status'], 'success');
        $template = $this->config['TEMPLATE_FOLDER'] . $account[0]['uuid'];
        $delegate = $this->config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app']['name'];
        try {
            FileUtils::rmDir($appname);
        } catch (Exception $e){
            
        }
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org']['uuid']);
    }

    public function testDeployAppWithNoEntityInYml()
    {
        $this->setUpTearDownHelper->setupAppDescriptor('application7.yml');
        $this->initAuthToken($this->adminUser);
        if (enableCamundaForDeployApp == 0) {
            $mockProcessManager = $this->getMockProcessManager();
            $mockProcessManager->expects('deploy')->withAnyArgs()->once()->andReturn(array('Process_1dx3jli:1eca438b-007f-11ea-a6a0-bef32963d9ff'));
            $mockProcessManager->expects('parseBPMN')->withAnyArgs()->once()->andReturn(null);
        }
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("setupjob", Mockery::any())->once()->andReturn(array('body' => '{"Success":true,"Message":"Job Scheduled Successfully!","JobId":"3a289705-763d-489a-b501-0755b9d4b64b","JobGroup":"autoRenewalJob"}'));
        }
        $this->dispatch('/app/deployapp', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app']['name'];
        $YmlappUuid = $yaml['app']['uuid'];
        $this->assertEquals($content['status'], 'success');
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app']['name'];
        try {
            FileUtils::rmDir($appname);
        } catch (Exception $e){
            
        }
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org']['uuid']);
    }

    public function testDeployAppWithBusinessOffering()
    {
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DiveInsuranceSample';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        copy(__DIR__ . '/../../sampleapp/application15.yml', __DIR__ . '/../../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        if (enableCamundaForDeployApp == 0) {
            $mockProcessManager = $this->getMockProcessManager();
            $mockProcessManager->expects('deploy')->withAnyArgs()->once()->andReturn(array('Process_1dx3jli:1eca438b-007f-11ea-a6a0-bef32963d9ff'));
            $mockProcessManager->expects('parseBPMN')->withAnyArgs()->once()->andReturn(null);
        }
        if (enableExecUtils == 0) {
            $mockBosUtils = Mockery::mock('alias:\Oxzion\Utils\ExecUtils');
            $mockBosUtils->expects('randomPassword')->withAnyArgs()->once()->andReturn('12345678');
            $mockBosUtils->expects('execCommand')->withAnyArgs()->times(3)->andReturn();
        }
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertEquals($content['status'], 'success');
        $this->setDefaultAsserts();
        $filename = "application.yml";
        $path = $data['path'];
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app']['name'];
        $YmlappUuid = $yaml['app']['uuid'];
        $query = "SELECT * from ox_app where name = '" . $appName . "'";
        $queryResult = $this->executeQueryTest($query);
        $this->assertEquals(1, count($queryResult));
        $this->assertEquals($YmlappUuid, $queryResult[0]['uuid']);
        $appId = $queryResult[0]['id'];
        $query = "SELECT name,status,uuid,id from ox_account where name = '" . $yaml['org']['name'] . "'";
        $acctResult = $this->executeQueryTest($query);
        $this->assertEquals(1, count($acctResult));
        $this->assertEquals($yaml['org']['uuid'], $acctResult[0]['uuid']);
        $this->assertEquals($yaml['org']['name'], $acctResult[0]['name']);
        $this->assertEquals('Active', $acctResult[0]['status']);
        $query = "SELECT * from ox_app_registry where app_id = '" . $appId . "'";
        $appRegistryResult = $this->executeQueryTest($query);
        $accountId = $acctResult[0]['id'];
        $this->assertEquals(1, count($appRegistryResult));
        $this->assertEquals($accountId, $appRegistryResult[0]['account_id']);
        $query = "SELECT name, permission_allowed as permission FROM ox_privilege WHERE app_id = '" . $appId . "'";
        $privilege = $this->executeQueryTest($query);
        $this->assertEquals(3, count($privilege));
        $this->assertEquals($yaml['privilege'], $privilege);
        $query = "select * from ox_business_role where app_id = $appId";
        $businessRole = $this->executeQueryTest($query);
        $this->assertEquals(2, count($businessRole));
        $this->assertEquals($yaml['businessRole'][0]['name'], $businessRole[0]['name']);
        $this->assertEquals($yaml['businessRole'][1]['name'], $businessRole[1]['name']);
        $query = "SELECT * from ox_role WHERE business_role_id is not null OR account_id = $accountId ORDER BY name";
        $role = $this->executeQueryTest($query);
        $this->assertEquals(6, count($role));
        $this->assertEquals($yaml['role'][0]['name'], $role[1]['name']);
        $this->assertEquals(null, $role[1]['account_id']);
        $this->assertEquals($businessRole[0]['id'], $role[1]['business_role_id']);
        $this->assertEquals($role[1]['name'], $role[2]['name']);
        $this->assertEquals($accountId, $role[2]['account_id']);
        $this->assertEquals($role[1]['business_role_id'], $role[2]['business_role_id']);
        $this->assertEquals($yaml['role'][1]['name'], $role[5]['name']);
        $this->assertEquals(null, $role[5]['account_id']);
        $this->assertEquals($businessRole[1]['id'], $role[5]['business_role_id']);

        $query = "SELECT rp.* from ox_role_privilege rp 
                    inner join ox_role r on r.id = rp.role_id WHERE r.business_role_id is not null order by r.name";
        $rolePrivilege = $this->executeQueryTest($query);
        $this->assertEquals(4, count($rolePrivilege));
        $this->assertEquals($yaml['role'][0]['privileges'][0]['privilege_name'], $rolePrivilege[0]['privilege_name']);
        $this->assertEquals($yaml['role'][0]['privileges'][0]['permission'], $rolePrivilege[0]['permission']);
        $this->assertEquals($role[1]['id'], $rolePrivilege[0]['role_id']);
        $this->assertEquals($accountId, $rolePrivilege[0]['account_id']);
        $this->assertEquals($appId, $rolePrivilege[0]['app_id']);
        $this->assertEquals($yaml['role'][0]['privileges'][0]['privilege_name'], $rolePrivilege[1]['privilege_name']);
        $this->assertEquals($yaml['role'][0]['privileges'][0]['permission'], $rolePrivilege[1]['permission']);
        $this->assertEquals($role[2]['id'], $rolePrivilege[1]['role_id']);
        $this->assertEquals($accountId, $rolePrivilege[1]['account_id']);
        $this->assertEquals($appId, $rolePrivilege[1]['app_id']);
        $this->assertEquals($yaml['role'][1]['privileges'][0]['privilege_name'], $rolePrivilege[2]['privilege_name']);
        $this->assertEquals($yaml['role'][1]['privileges'][0]['permission'], $rolePrivilege[2]['permission']);
        $this->assertEquals($role[5]['id'], $rolePrivilege[2]['role_id']);
        $this->assertEquals($accountId, $rolePrivilege[2]['account_id']);
        $this->assertEquals($appId, $rolePrivilege[2]['app_id']);
        $this->assertEquals($yaml['role'][1]['privileges'][1]['privilege_name'], $rolePrivilege[3]['privilege_name']);
        $this->assertEquals($yaml['role'][1]['privileges'][1]['permission'], $rolePrivilege[3]['permission']);
        $this->assertEquals($role[5]['id'], $rolePrivilege[3]['role_id']);
        $this->assertEquals($accountId, $rolePrivilege[3]['account_id']);
        $this->assertEquals($appId, $rolePrivilege[3]['app_id']);
        $query = "select * from ox_account_business_role where account_id = $accountId";
        $accountBusinessRole = $this->executeQueryTest($query);
        $this->assertEquals(1, count($accountBusinessRole));
        $this->assertEquals($businessRole[0]['id'], $accountBusinessRole[0]['business_role_id']);
        
        $query = "select * from ox_app_entity where app_id = $appId order by name";
        $entity = $this->executeQueryTest($query);
        $this->assertEquals(2, count($entity));
        foreach ($entity as $key => $value) {
            $this->assertEquals($yaml['entity'][$key]['name'], $value['name']);
            $this->assertEquals($yaml['entity'][$key]['uuid'], $value['uuid']);
            $this->assertEquals($yaml['entity'][$key]['start_date_field'], $value['start_date_field']);
            $this->assertEquals($yaml['entity'][$key]['end_date_field'], $value['end_date_field']);
            $this->assertEquals($yaml['entity'][$key]['status_field'], $value['status_field']);
            $this->assertEquals(1, $value['created_by']);
            $this->assertEquals(date('Y-m-d'), date_create($value['date_created'])->format('Y-m-d'));
            $this->assertEquals(null, $value['modified_by']);
            $this->assertEquals(null, $value['date_modified']);
            $this->assertEquals(0, $value['override_data']);
        }
        $query = "SELECT ei.* from ox_entity_identifier ei 
                    inner join ox_app_entity e on e.id = ei.entity_id 
                    where e.app_id = $appId order by e.name";
        $entityIdentifier = $this->executeQueryTest($query);
        $this->assertEquals(2, count($entityIdentifier));
        foreach ($entityIdentifier as $key => $value) {
            $this->assertEquals($entity[$key]['id'], $value['entity_id']);
            $this->assertEquals($yaml['entity'][$key]['identifiers'][0]['identifier'], $value['identifier']);
        }
        $query = "SELECT ei.* from ox_entity_participant_role ei 
                    inner join ox_app_entity e on e.id = ei.entity_id 
                     order by e.name";
        $participantRoles = $this->executeQueryTest($query);
        $this->assertEquals(2, count($participantRoles));
        foreach ($participantRoles as $key => $value) {
            $this->assertEquals($entity[$key]['id'], $value['entity_id']);
            $this->assertEquals($businessRole[1]['id'], $value['business_role_id']);
        }
        $query = "SELECT * from ox_account_offering oo 
                    inner join ox_app_entity ae on ae.id = oo.entity_id order by ae.name";
        $acctOffering = $this->executeQueryTest($query);
        $this->assertEquals(2, count($acctOffering));
        foreach ($acctOffering as $key => $value) {
            $this->assertEquals($entity[$key]['id'], $value['entity_id']);
            $this->assertEquals($accountBusinessRole[0]['id'], $value['account_business_role_id']);
        }

        $config = $this->getApplicationConfig();
        $template = $config['TEMPLATE_FOLDER'] . $acctResult[0]['uuid'];
        $delegate = $config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        $apps = $config['APPS_FOLDER'];
        if (enableExecUtils != 0) {
            if (file_exists($apps) && is_dir($apps)) {
                if (is_link($apps . "/$appName")) {
                    $dist = "/dist/";
                    $nodemodules = "/node_modules/";
                    $this->assertEquals(file_exists($apps . "/$appName" . $dist), true);
                    $this->assertEquals(file_exists($apps . "/$appName" . $nodemodules), true);
                }
            }
        }
        
    }

    public function testDeployApplication()
    {
        $sampleAppUuidFromWorkflowYml = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        $appName = 'SampleApp';
        $appSourceDir = $this->config['EOX_APP_SOURCE_DIR'] . "${appName}_${sampleAppUuidFromWorkflowYml}";
        $appDestDir = $this->config['EOX_APP_DEPLOY_DIR'] . "${appName}_${sampleAppUuidFromWorkflowYml}";
        try {
            if (file_exists($appSourceDir)) {
                FileUtils::rmDir($appSourceDir);
                mkdir($appSourceDir);
            }
            $eoxSampleApp = dirname(__FILE__) . '/../../Dataset/SampleApp';
            FileUtils::copyDir($eoxSampleApp, $appSourceDir);
            $this->testDeployApp();
        }
        catch (Exception $e) {
            throw $e;
        }
        finally {
            try {
                if (file_exists($appSourceDir)) {
                    FileUtils::rmDir($appSourceDir);
                }
            }
            catch(Exception $e) {
                print($e);
            }
            try {
                if (file_exists($appDestDir)) {
                    FileUtils::rmDir($appDestDir);
                }
            }
            catch(Exception $e) {
                print($e);
            }
        }
    }

    public function testDeployApplicationWithoutAppInDatabase() {
        $this->initAuthToken($this->adminUser);
        $notExistingAppUuid = '11111111-1111-1111-1111-111111111111';
        $this->dispatch("/app/${notExistingAppUuid}/deploy", 'POST');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals('Entity not found.', $content['message']);
        $data = $content['data'];
        $this->assertEquals('App', $data['entity']);
        $this->assertEquals($notExistingAppUuid, $data['uuid']);
    }

    public function testDeployApplicationWithoutSourceAppDir() {
        $sampleAppUuidFromWorkflowYml = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        $appName = 'SampleApp';
        $appSourceDir = $this->config['EOX_APP_SOURCE_DIR'] . "${sampleAppUuidFromWorkflowYml}";
        //Ensure source directory does not exist.
        if (file_exists($appSourceDir)) {
            FileUtils::rmDir($appSourceDir);
        }

        $this->initAuthToken($this->adminUser);
        $this->dispatch("/app/${sampleAppUuidFromWorkflowYml}/deploy", 'POST');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Application source directory not found.');
    }

//-----------------------------------------------------------------------------------------------
//IMPORTANT: This test is not implemented because it needs intrusive changes (deleting/moving) 
//to the template application.
//-----------------------------------------------------------------------------------------------
//    public function testDeployApplicationWithoutTemplateApp() {
//        $sampleAppUuidFromWorkflowYml = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
//        $this->initAuthToken($this->adminUser);
//        $this->dispatch("/app/${sampleAppUuidFromWorkflowYml}/deploy", 'POST');
//        $this->assertResponseStatusCode(406);
//        $content = (array) json_decode($this->getResponse()->getContent(), true);
//        $this->assertEquals($content['status'], 'error');
//        $this->assertEquals($content['message'], 'Template application not found.');
//    }

    private function setupAppSourceDir($ymlData) {
        $appService = $this->getApplicationServiceLocator()->get(AppService::class);
        $appService->setupOrUpdateApplicationDirectoryStructure($ymlData);
    }

    public function testUpdate()
    {
        $uuid = '1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4';
        $query = "SELECT * FROM ox_app WHERE uuid='${uuid}'";
        //Take snapshot of database record.
        $recordSetBeforeUpdate = $this->executeQueryTest($query);
        $recordBeforeUpdate = $recordSetBeforeUpdate[0];
        //Setup data and update.
        $data = [
            'app' => [
                'name' => 'Admin App',
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'Admin', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch("/app/${uuid}", 'PUT', null);
        //Assert the results.
        $this->assertResponseStatusCode(200);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->setDefaultAsserts();
        //Assert database record is updated.
        $recordSetAfterUpdate = $this->executeQueryTest($query);
        $recordAfterUpdate = $recordSetAfterUpdate[0];
        $this->assertNotEquals($recordBeforeUpdate['name'], $recordAfterUpdate['name']);
        $this->assertNotEquals($recordBeforeUpdate['category'], $recordAfterUpdate['category']);
        $this->assertNotEquals($recordBeforeUpdate['logo'], $recordAfterUpdate['logo']);
        $this->assertEquals($data['app']['name'], $recordAfterUpdate['name']);
        $this->assertEquals($data['app']['category'], $recordAfterUpdate['category']);
        $this->assertEquals($data['app']['logo'], $recordAfterUpdate['logo']);
        //Assert returned data matches.
        $returnData = $content['data'];
        $appData = $returnData['app'];
        $this->assertEquals($data['app']['name'], $appData['name']);
        $this->assertEquals($data['app']['category'], $appData['category']);
        $this->assertEquals($data['app']['logo'], $appData['logo']);
        //Check application descriptor is created and is as expected.
        $srcAppDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->getApplicationConfig(), $appData);
        $this->assertTrue(file_exists($srcAppDir));
        $appDescriptorFilePath = $srcAppDir . DIRECTORY_SEPARATOR . AppService::APPLICATION_DESCRIPTOR_FILE_NAME;
        $yamlData = Yaml::parse(file_get_contents($appDescriptorFilePath));
        $this->assertEquals($returnData, $yamlData);
    }

    public function testUpdateWithoutAccessPermission()
    {
        $uuid = '11111111-1111-1111-1111-111111111111';
        $query = "SELECT id, name FROM ox_app";
        //Take snapshot of database record.
        $recordSetBeforeUpdate = $this->executeQueryTest($query);
        //Setup data and invoke the test.
        $data = [
            'app' => [
                'name' => 'Admin App', 
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'EXAMPLE_CATEGORY', 
                'logo' => 'app.png'
            ]
        ];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch("/app/${uuid}", 'PUT', $data);
        //Run post test assertions.
        $this->assertResponseStatusCode(401);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
        //Take database record snapshot after test.
        $recordSetAfterUpdate = $this->executeQueryTest($query);
        $this->assertEquals($recordSetBeforeUpdate, $recordSetAfterUpdate);
    }

    public function testUpdateWithoutExistingAppSrcDir() {
        $uuid = '11111111-1111-1111-1111-111111111111';
        $query = "SELECT id, name FROM ox_app";
        //Take snapshot of database record.
        $recordSetBeforeUpdate = $this->executeQueryTest($query);
        //Setup data and invoke the test.
        $data = [
            'app' => [
                'name' => 'Admin App', 
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'EXAMPLE_CATEGORY', 
                'logo' => 'app.png'
            ]
        ];
        //Make sure app source directory does not exist.
        $appSrcDir = AppArtifactNamingStrategy::getSourceAppDirectory($this->getApplicationConfig(), $data['app']);
        if (file_exists($appSrcDir)) {
            FileUtils::rmDir($appSrcDir);
        }
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch("/app/${uuid}", 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Application source directory is not found.');
        $errorContext = $content['data'];
        $this->assertEquals($appSrcDir, $errorContext['directory']);
        //Take database record snapshot after test.
        $recordSetAfterUpdate = $this->executeQueryTest($query);
        $this->assertEquals($recordSetBeforeUpdate, $recordSetAfterUpdate);
    }

    public function testUpdateEntityNotFound()
    {
        $uuid = '11111111-1111-1111-1111-111111111111';
        $query = "SELECT id, name FROM ox_app";
        //Take snapshot of database record.
        $recordSetBeforeUpdate = $this->executeQueryTest($query);
        //Setup data and invoke the test.
        $data = [
            'app' => [
                'name' => 'Admin App', 
                'uuid' => $uuid,
                'type' => 2, 
                'category' => 'EXAMPLE_CATEGORY', 
                'logo' => 'app.png'
            ]
        ];
        $this->setupAppSourceDir($data);
        //Ensure entity with given UUID does not exist in the database.
        $entityRecordSet = $this->executeQueryTest("SELECT id FROM ox_app WHERE uuid='${uuid}'");
        $this->assertEmpty($entityRecordSet);
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch("/app/${uuid}", 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Entity not found.');
        $errorContext = $content['data'];
        $this->assertEquals('ox_app', $errorContext['entity']);
        $this->assertEquals($uuid, $errorContext['uuid']);
        //Take database record snapshot after test.
        $recordSetAfterUpdate = $this->executeQueryTest($query);
        $this->assertEquals($recordSetBeforeUpdate, $recordSetAfterUpdate);
    }

    public function testUpdateWithUuidMismatch()
    {
        $uuid1 = '11111111-1111-1111-1111-111111111111';
        $uuid2 = '22222222-2222-2222-2222-222222222222';
        $query = "SELECT id, name FROM ox_app";
        //Take snapshot of database record.
        $recordSetBeforeUpdate = $this->executeQueryTest($query);
        //Setup data and invoke the test.
        $data = [
            'app' => [
                'name' => 'Admin App', 
                'uuid' => $uuid1,
                'type' => 2, 
                'category' => 'EXAMPLE_CATEGORY', 
                'logo' => 'app.png'
            ]
        ];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch("/app/${uuid2}", 'PUT', null);
        $this->assertResponseStatusCode(500);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Unexpected error.');
        //Take database record snapshot after test.
        $recordSetAfterUpdate = $this->executeQueryTest($query);
        $this->assertEquals($recordSetBeforeUpdate, $recordSetAfterUpdate);
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4', 'DELETE', NULL);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/11111111-1111-1111-1111-111111111111', 'DELETE', NULL);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetListOfAssignmentsWithoutFiltersValues()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/assignments?filter=[{"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class);
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('assignments');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetListOfAssignmentsWithoutFilters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/assignments', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class);
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('assignments');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['total'], 1);
    }
}
