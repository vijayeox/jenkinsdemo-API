<?php
namespace App;

use App\Controller\AppController;
use App\Controller\AppRegisterController;
use Mockery;
use Oxzion\Db\Migration\Migration;
use Oxzion\Test\ControllerTest;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Symfony\Component\Yaml\Yaml;

class AppControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        $config = $this->getApplicationConfig();
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../../Dataset/Workflow.yml");
        if ($this->getName() == 'testDeployAppWithWrongUuidInDatabase' || $this->getName() == 'testDeployAppWithWrongNameInDatabase' || $this->getName() == 'testDeployAppWithNameAndNoUuidInYMLButNameandUuidInDatabase' || $this->getName() == 'testDeployAppAddExtraPrivilegesInDatabaseFromYml' || $this->getName() == 'testDeployAppDeleteExtraPrivilegesInDatabaseNotInYml') {
            $dataset->addYamlFile(dirname(__FILE__) . "/../../Dataset/App2.yml");
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
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function cleanDb($appName, $appId): void
    {
        $database = Migration::getDatabaseName($appName, $appId);
        $query = "DROP DATABASE IF EXISTS " . $database;
        $statement = Migration::createAdapter($this->getApplicationConfig(), $database)->query($query);
        $result = $statement->execute();
    }

    public function testGetListOfAssignments()
    {
        $this->initAuthToken($this->adminUser);
        $product = 'Individual Professional Liability';
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/assignments?filter=[{"filter":{"filters":[{"field":"product","operator":"eq","value":"' . $product . '"}]},"skip":0,"take":10}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class);
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('assignments');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
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
        $this->assertNotEmpty($content['data'][0]['uuid']);
        $this->assertEquals($content['data'][0]['name'], 'SampleApp');
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

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'App1', 'type' => 2, 'category' => 'EXAMPLE_CATEGORY'];
        $this->dispatch('/app', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testCreateWithOutTextFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['type' => 2, 'org_id' => 4];
        $this->dispatch('/app', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testCreateAccess()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => '5c822d497f44n', 'type' => 2, 'category' => 'EXAMPLE_CATEGORY', 'logo' => 'app.png'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app', 'POST', $data);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('App');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testDeployApp()
    {
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DiveInsuranceSample';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        copy(__DIR__ . '/../../sampleapp/application1.yml', __DIR__ . '/../../sampleapp/application.yml');
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
        $appName = $yaml['app'][0]['name'];
        $YmlappUuid = $yaml['app'][0]['uuid'];
        $query = "SELECT name from ox_app where name = '" . $appName . "'";
        $appname = $this->executeQueryTest($query);
        $query = "SELECT uuid from ox_app where name = '" . $appName . "'";
        $appUuid = $this->executeQueryTest($query);
        $appUuidCount = count($appUuid[0]);
        $appUuid = $appUuid[0]['uuid'];
        $query = "SELECT id from ox_app where uuid = '" . $appUuid . "'";
        $appId = $this->executeQueryTest($query);
        $appId = $appId[0]['id'];
        $query = "SELECT count(o.name),o.status,o.uuid,o.id from ox_organization o where o.name = '" . $yaml['org'][0]['name'] . "'";
        $orgid = $this->executeQueryTest($query);
        $query = "SELECT count(id) as count from ox_app_registry where app_id = '" . $appId . "'";
        $appRegistryResult = $this->executeQueryTest($query);
        $query = "SELECT count(name) as count FROM ox_privilege WHERE app_id = '" . $appId . "'";
        $privilege = $this->executeQueryTest($query);
        $query = "SELECT count(privilege_name) as count from ox_role_privilege WHERE app_id = '" . $appId . "'";
        $rolePrivilege = $this->executeQueryTest($query);
        $query = "SELECT count(id) as count from ox_role WHERE org_id = '" . $orgid[0]['id'] . "'";
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
        $this->assertEquals($orgid[0]['uuid'], $yaml['org'][0]['uuid']);
        $this->assertEquals($appname[0]['name'], $appName);
        $this->assertEquals($appUuid, $YmlappUuid);
        $this->assertEquals($appUuidCount, 1);
        $this->assertEquals($appRegistryResult[0]['count'], 1);
        $this->assertEquals($content['status'], 'success');
        $config = $this->getApplicationConfig();
        $template = $config['TEMPLATE_FOLDER'] . $orgid[0]['uuid'];
        $delegate = $config['DELEGATE_FOLDER'] . $appUuid;
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
        $query = "SELECT * from ox_workflow where app_id = " . $appId;
        $workflow = $this->executeQueryTest($query);
        if (enableCamundaForDeployApp == 1) {
            $this->assertEquals(count($workflow), 3);
            foreach ($workflow as $wf) {
                $this->assertNotEmpty($wf['process_id']);
            }
        }
        $this->clean($path, $yaml, $appName, $YmlappUuid);   
    }
    private function clean($path, $yaml, $appName, $YmlappUuid){
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app'][0]['name'];
        FileUtils::deleteDirectoryContents($appname);
        $this->cleanDb($appName, $YmlappUuid);
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org'][0]['uuid']);
        $deletdirectoryPath = __DIR__ . '/../../../../';
        $deletenpm = $deletdirectoryPath . '.npm';
        if (file_exists($deletenpm)) {
            FileUtils::deleteDirectoryContents($deletenpm);
        }
        $deleteconfig = $deletdirectoryPath . '.config';
        if (file_exists($deleteconfig)) {
            FileUtils::deleteDirectoryContents($deleteconfig);
        }
    }
    public function testDeplayAppWithFieldValidation(){
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/Dive Insurance';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        copy(__DIR__ . '/../../sampleapp/application12.yml', __DIR__ . '/../../sampleapp/application.yml');
        $config = $this->getApplicationConfig();
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app'][0]['name'];
        $YmlappUuid = $yaml['app'][0]['uuid'];
        $query = "SELECT name, uuid from ox_app where name = '" . $appName . "'";
        $appdata = $this->executeQueryTest($query);
        $this->assertEquals($appdata[0]['name'], $appName);
        $this->assertEquals($appdata[0]['uuid'], $YmlappUuid);
        $this->assertEquals($content['status'], 'success');
        $delegate = $config['DELEGATE_FOLDER'] . $YmlappUuid;
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
        $this->clean($path, $yaml, $appName, $YmlappUuid);
    }

    public function testDeplayAppWithFieldValidationErrors(){
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/Dive Insurance';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        copy(__DIR__ . '/../../sampleapp/application13.yml', __DIR__ . '/../../sampleapp/application.yml');
        $config = $this->getApplicationConfig();
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->assertEquals('error', $content['status']);
        $this->assertEquals('Validation Errors', $content['message']);
        $errors = $content['data']['errors'];
        $this->assertEquals(2, count($errors));
        $this->assertEquals("Field padi - Value of property 'decimalLimit' is '2' expected ''", $errors[0]);
        $this->assertEquals("Field dateTime - Unexpected", $errors[1]);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app'][0]['name'];
        $YmlappUuid = $yaml['app'][0]['uuid'];
        $this->clean($path, $yaml, $appName, $YmlappUuid);
        
    }
    private function unlinkFolders($appUuid, $appName, $orgUuid = null)
    {
        $config = $this->getApplicationConfig();
        $file = $config['DELEGATE_FOLDER'] . $appUuid;
        if (is_link($file)) {
            unlink($file);
        }
        if ($orgUuid) {
            $file = $config['TEMPLATE_FOLDER'] . $orgUuid;
            if (is_link($file)) {
                unlink($file);
            }
        }
        $appName = str_replace(' ', '', $appName);
        $app = $config['APPS_FOLDER'] . $appName;
        if (is_link($app)) {
            unlink($app);
        }
    }

    public function testDeployAppWithoutOptionalFieldsInYml()
    {
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/Dive Insurance';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        copy(__DIR__ . '/../../sampleapp/application5.yml', __DIR__ . '/../../sampleapp/application.yml');
        $config = $this->getApplicationConfig();
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app'][0]['name'];
        $YmlappUuid = $yaml['app'][0]['uuid'];
        $query = "SELECT name, uuid from ox_app where name = '" . $appName . "'";
        $appdata = $this->executeQueryTest($query);
        $this->assertEquals($appdata[0]['name'], $appName);
        $this->assertEquals($appdata[0]['uuid'], $YmlappUuid);
        $this->assertEquals($content['status'], 'success');
        $delegate = $config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($delegate), true);
        $apps = $config['APPS_FOLDER'];
        if (file_exists($apps) && is_dir($apps)) {
            if (is_link($apps . "/$appName")) {
                $dist = "/dist/";
                $nodemodules = "/node_modules/";
                $this->assertEquals(file_exists($apps . "/$appName" . $dist), false);
                $this->assertEquals(file_exists($apps . "/$appName" . $nodemodules), false);
            }
        }
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app'][0]['name'];
        FileUtils::deleteDirectoryContents($appname);
        $this->cleanDb($appName, $YmlappUuid);
        $this->unlinkFolders($YmlappUuid, $appname);
    }

    public function testDeployAppWithWrongUuidInDatabase()
    {
        copy(__DIR__ . '/../../sampleapp/application8.yml', __DIR__ . '/../../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
        unlink(__DIR__ . '/../../sampleapp/application.yml');
    }

    public function testDeployAppWithWrongNameInDatabase()
    {
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/Dive Insurance';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $config = $this->getApplicationConfig();
        copy(__DIR__ . '/../../sampleapp/application9.yml', __DIR__ . '/../../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app'][0]['name'];
        $YmlappUuid = $yaml['app'][0]['uuid'];
        $query = "SELECT name, uuid from ox_app where name = '" . $appName . "'";
        $appdata = $this->executeQueryTest($query);
        $this->assertEquals($appdata[0]['name'], $appName);
        $this->assertEquals($appdata[0]['uuid'], $YmlappUuid);
        $this->assertEquals($content['status'], 'success');
        $query = "SELECT count(o.name),o.status,o.uuid from ox_organization o where o.name = '" . $yaml['org'][0]['name'] . "'";
        $orgid = $this->executeQueryTest($query);
        $this->assertEquals($orgid[0]['uuid'], $yaml['org'][0]['uuid']);
        $template = $config['TEMPLATE_FOLDER'] . $orgid[0]['uuid'];
        $delegate = $config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        if (!isset($yaml['org'][0]['uuid'])) {
            $yaml['org'][0]['uuid'] = null;
        }
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app'][0]['name'];
        FileUtils::deleteDirectoryContents($appname);
        $this->cleanDb($appName, $YmlappUuid);
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org'][0]['uuid']);
    }

    public function testDeployAppWithNameAndNoUuidInYMLButNameandUuidInDatabase()
    {
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/Dive Insurance';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $config = $this->getApplicationConfig();
        copy(__DIR__ . '/../../sampleapp/application10.yml', __DIR__ . '/../../sampleapp/application.yml');
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
        $this->assertEquals(isset($yaml['app'][0]['uuid']), true);
        $appName = $yaml['app'][0]['name'];
        $YmlappUuid = $yaml['app'][0]['uuid'];
        $query = "SELECT name, uuid from ox_app where name = '" . $appName . "'";
        $appdata = $this->executeQueryTest($query);
        $this->assertEquals($appdata[0]['name'], $appName);
        $this->assertEquals($appdata[0]['uuid'], $YmlappUuid);
        $this->assertEquals($content['status'], 'success');
        $query = "SELECT count(o.name),o.status,o.uuid from ox_organization o where o.name = '" . $yaml['org'][0]['name'] . "'";
        $orgid = $this->executeQueryTest($query);
        $this->assertEquals($orgid[0]['uuid'], $yaml['org'][0]['uuid']);
        $template = $config['TEMPLATE_FOLDER'] . $orgid[0]['uuid'];
        $delegate = $config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app'][0]['name'];
        FileUtils::deleteDirectoryContents($appname);
        $this->cleanDb($appName, $YmlappUuid);
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org'][0]['uuid']);
    }

    public function testDeployAppNoDirectory()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp1/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDeployAppNoFile()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp2/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDeployAppNoFileData()
    {
        copy(__DIR__ . '/../../sampleapp/application2.yml', __DIR__ . '/../../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        unlink(__DIR__ . '/../../sampleapp/application.yml');
    }

    public function testDeployAppNoAppData()
    {
        copy(__DIR__ . '/../../sampleapp/application3.yml', __DIR__ . '/../../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__ . '/../../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        unlink(__DIR__ . '/../../sampleapp/application.yml');
    }

    public function testDeployAppOrgDataWithoutUuidAndContactAndPreferencesInYml()
    {
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_link($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/Dive Insurance';
        if (is_link($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $config = $this->getApplicationConfig();
        copy(__DIR__ . '/../../sampleapp/application4.yml', __DIR__ . '/../../sampleapp/application.yml');
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
        $appName = $yaml['app'][0]['name'];
        $YmlappUuid = $yaml['app'][0]['uuid'];
        $this->assertNotEmpty($yaml['org'][0]['uuid']);
        $this->assertNotEmpty($yaml['org'][0]['contact']);
        $this->assertEquals($yaml['org'][0]['preferences'], '{}');
        $this->assertEquals($content['status'], 'success');
        $query = "SELECT count(o.name),o.status,o.uuid from ox_organization o where o.name = '" . $yaml['org'][0]['name'] . "'";
        $orgid = $this->executeQueryTest($query);
        $this->assertEquals($orgid[0]['uuid'], $yaml['org'][0]['uuid']);
        $template = $config['TEMPLATE_FOLDER'] . $orgid[0]['uuid'];
        $delegate = $config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app'][0]['name'];
        FileUtils::deleteDirectoryContents($appname);
        $this->cleanDb($appName, $YmlappUuid);
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org'][0]['uuid']);
    }

    public function testDeployAppAddExtraPrivilegesInDatabaseFromYml()
    {
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/Dive Insurance';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $config = $this->getApplicationConfig();
        copy(__DIR__ . '/../../sampleapp/application6.yml', __DIR__ . '/../../sampleapp/application.yml');
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
        $appName = $yaml['app'][0]['name'];
        $YmlappUuid = $yaml['app'][0]['uuid'];
        $privilegearray = array_unique(array_column($yaml['privilege'], 'name'));
        $appid = "SELECT id FROM ox_app WHERE name = '" . $yaml['app'][0]['name'] . "'";
        $idresult = $this->executeQueryTest($appid);
        $queryString = "SELECT name FROM ox_privilege WHERE app_id = '" . $idresult[0]['id'] . "'";
        $result = $this->executeQueryTest($queryString);
        $DBprivilege = array_unique(array_column($result, 'name'));
        $query = "SELECT count(o.name),o.status,o.uuid from ox_organization o where o.name = '" . $yaml['org'][0]['name'] . "'";
        $orgid = $this->executeQueryTest($query);
        $this->assertEquals($orgid[0]['uuid'], $yaml['org'][0]['uuid']);
        $this->assertEquals($privilegearray, $DBprivilege);
        $this->assertEquals($content['status'], 'success');
        $template = $config['TEMPLATE_FOLDER'] . $orgid[0]['uuid'];
        $delegate = $config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app'][0]['name'];
        FileUtils::deleteDirectoryContents($appname);
        $this->cleanDb($appName, $YmlappUuid);
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org'][0]['uuid']);
    }

    public function testDeployAppDeleteExtraPrivilegesInDatabaseNotInYml()
    {
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/Dive Insurance';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $config = $this->getApplicationConfig();
        copy(__DIR__ . '/../../sampleapp/application6.yml', __DIR__ . '/../../sampleapp/application.yml');
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
        $appName = $yaml['app'][0]['name'];
        $YmlappUuid = $yaml['app'][0]['uuid'];
        $appid = "SELECT id FROM ox_app WHERE name = '" . $yaml['app'][0]['name'] . "'";
        $idresult = $this->executeQueryTest($appid);
        $queryString = "SELECT name FROM ox_privilege WHERE app_id = '" . $idresult[0]['id'] . "'";
        $result = $this->executeQueryTest($queryString);
        $DBprivilege = array_unique(array_column($result, 'name'));
        $list = "'" . implode("', '", $DBprivilege) . "'";
        $query = "SELECT count(o.name),o.status,o.uuid from ox_organization o where o.name = '" . $yaml['org'][0]['name'] . "'";
        $orgid = $this->executeQueryTest($query);
        $this->assertEquals($orgid[0]['uuid'], $yaml['org'][0]['uuid']);
        $this->assertNotEquals($list, 'MANAGE');
        $this->assertEquals($content['status'], 'success');
        $template = $config['TEMPLATE_FOLDER'] . $orgid[0]['uuid'];
        $delegate = $config['DELEGATE_FOLDER'] . $YmlappUuid;
        $this->assertEquals(file_exists($template), true);
        $this->assertEquals(file_exists($delegate), true);
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app'][0]['name'];
        FileUtils::deleteDirectoryContents($appname);
        $this->cleanDb($appName, $YmlappUuid);
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org'][0]['uuid']);
    }

    public function testDeployAppWithNoEntityInYml()
    {
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/DummyDive';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        $directoryName = __DIR__ . '/../../sampleapp/view/apps/Dive Insurance';
        if (is_dir($directoryName)) {
            FileUtils::deleteDirectoryContents($directoryName);
        }
        copy(__DIR__ . '/../../sampleapp/application7.yml', __DIR__ . '/../../sampleapp/application.yml');
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
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__ . '/../../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path . $filename));
        $appName = $yaml['app'][0]['name'];
        $YmlappUuid = $yaml['app'][0]['uuid'];
        $this->assertEquals($content['status'], 'success');
        unlink(__DIR__ . '/../../sampleapp/application.yml');
        $appname = $path . 'view/apps/' . $yaml['app'][0]['name'];
        FileUtils::deleteDirectoryContents($appname);
        $this->cleanDb($appName, $YmlappUuid);
        $this->unlinkFolders($YmlappUuid, $appName, $yaml['org'][0]['uuid']);
    }

    public function testUpdate()
    {
        $data = ['name' => 'Admin App', 'type' => 2, 'category' => 'Admin', 'logo' => 'app.png'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateRestricted()
    {
        $data = ['name' => 'Admin App', 'type' => 2, 'category' => 'EXAMPLE_CATEGORY', 'logo' => 'app.png'];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4', 'PUT', $data);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('App');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Admin App', 'type' => 2, 'category' => 'EXAMPLE_CATEGORY', 'logo' => 'app.png'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/fc97bdf0-df6f-11e9-8a34-2a2ae2dbcce4', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/fc97bdf0-df6f-11e9-8a34-2a2ae2dbcce4', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testAddToAppRegistry()
    {
        $data = ['app_name' => 'Admin'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/org/b0971de7-0387-48ea-8f29-5d3704d96a46/addtoappregistry', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppRegisterController::class);
        $this->assertControllerClass('AppRegisterController');
        $this->assertMatchedRouteName('addtoappregistry');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['app_name'], $data['app_name']);
    }

    public function testAddToAppRegistryDuplicated()
    {
        $data = ['app_name' => 'SampleApp'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/org/' . $this->testOrgUuid . '/addtoappregistry', 'POST', $data);
        $this->assertResponseStatusCode(409);
        $this->assertModuleName('App');
        $this->assertControllerName(AppRegisterController::class);
        $this->assertControllerClass('AppRegisterController');
        $this->assertMatchedRouteName('addtoappregistry');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
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
