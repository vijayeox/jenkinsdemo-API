<?php
namespace App;

use App\Controller\AppController;
use App\Controller\AppRegisterController;
use App\Model;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Test\MainControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\ArrayUtils;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Utils\FileUtils;
use Oxzion\Workflow\ProcessManager;
use Oxzion\Workflow\WorkflowFactory;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Mockery;
use Camunda\ProcessManagerImpl;
use Symfony\Component\Yaml\Yaml;

class AppControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Workflow.yml");
        if($this->getName() == 'testDeployAppWithWrongUuidInDatabase' || $this->getName() == 'testDeployAppWithWrongNameInDatabase' || $this->getName() == 'testDeployAppWithNameAndNoUuidInYMLButNameandUuidInDatabase' || $this->getName() == 'testDeployAppAddExtraPrivilegesInDBFromYML' || $this->getName() == 'testDeployAppDeleteExtraPrivilegesInDBNotInYML') {
            $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/App2.yml");
        }
        return $dataset;
    }

    public function testAppRegister()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['applist' => json_encode([["name" => "CRM","category" => "organization","options" => ["autostart" => "false","hidden" => "false" ]],["name"=>"Calculator","category" =>  "office","options" => ["autostart" =>  "false","hidden" => "false"]],["name" => "Calendar","category" =>  "collaboration","options" =>  ["autostart" => "false","hidden" => "false"]],["name" => "Chat","category" => "collaboration","options" => ["autostart" => "true","hidden" => "true"]],["name" => "FileManager","category" => "office","options" => ["autostart" => "false","hidden" => "false"]],["name" => "Mail","category" => "collaboration","options" => ["autostart" => "true","hidden" => "true"]],["name" => "MailAdmin","category" => "utilities","options" => ["autostart" => "false","hidden" => "false"]],["name" => "MyTodo","category" => "null","options" => ["autostart" => "false","hidden" => "true"]],["name" => "Textpad","category" => "office","options" => ["autostart" => "false","hidden" => "false"]]])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/register', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppRegisterController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppRegisterController');
        $this->assertMatchedRouteName('appregister');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testAppRegisterInvaliddata()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['applist' => json_encode([["name" => "","category" => "organization","options" => ["autostart" => "false","hidden" => "false" ]],["name"=>"Calculator","category" =>  "office","options" => ["autostart" =>  "false","hidden" => "false"]],["name" => "Calendar","category" =>  "collaboration","" =>  ["autostart" => "false","hidden" => "false"]],["name" => "Chat","category" => "collaboration","options" => ["autostart" => "true","hidden" => "true"]],["name" => "FileManager","category" => "office","options" => ["autostart" => "false","hidden" => "false"]],["name" => "Mail","category" => "collaboration","options" => ["autostart" => "true","hidden" => "true"]],["name" => "MailAdmin","category" => "utilities","options" => ["autostart" => "false","hidden" => "false"]],["name" => "MyTodo","category" => "null","options" => ["autostart" => "false","hidden" => "true"]],["name" => "Textpad","category" => "office","options" => ["autostart" => "false","hidden" => "false"]]])];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/register', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(AppRegisterController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppRegisterController');
        $this->assertMatchedRouteName('appregister');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEquals($content['data'], array());
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
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
        $this->assertEquals(count($content['data']), 8);
        $this->assertEquals($content['data'][0]['name'], 'Admin');
        $this->assertEquals($content['total'], 8);
    }

    public function testGetAppListWithQuery()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/a?filter=[{"filter":{"logic":"and","filters":[{"field":"name","operator":"startswith","value":"a"},{"field":"category","operator":"contains","value":"utilities"}]},"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":1}]
', 'GET');
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
        $this->dispatch('/app/a?filter=[{"skip":0,"take":2}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applist');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['name'], 'Admin');
        $this->assertEquals($content['data'][1]['name'], 'AppBuilder');
        $this->assertEquals($content['total'], 8);
    }

    public function testGetAppListWithPageSize2()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/a?filter=[{"skip":2,"take":2}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('applist');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['name'], 'CRM');
        $this->assertEquals($content['data'][1]['name'], 'MailAdmin');
        $this->assertEquals($content['total'], 8);
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'App1', 'type' => 2, 'category' => 'EXAMPLE_CATEGORY'];
        $this->dispatch('/app', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
   }

    public function testDeployApp()
    {
        copy(__DIR__.'/../sampleapp/application1.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__.'/../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path.$filename));
        $appName = $yaml['app'][0]['name'];
        $query = "SELECT name from ox_app where name = '".$appName."'";
        $appname = $this->executeQueryTest($query);
        $query = "SELECT count(uuid) as appuuidcount from ox_app where name = '".$appName."'";
        $appUuid = $this->executeQueryTest($query);
        $query = "SELECT id from ox_organization where uuid = '".$content['data']['uuid']."'";
        $orgid = $this->executeQueryTest($query);
        $query = "SELECT * from ox_role where org_id = (SELECT id from ox_organization where uuid = '".$content['data']['uuid']."')";
        $role = $this->executeQueryTest($query);
        for($x=0;$x<sizeof($role);$x++){
            $query = "SELECT count(id) from ox_role_privilege where org_id = (SELECT id from ox_organization where role_id =".$role[$x]['id']."
                AND uuid = '".$content['data']['uuid']."')";
            $rolePrivilegeResult[] = $this->executeQueryTest($query);
        }
        $select = "SELECT * FROM ox_user_role where role_id =".$role[0]['id'];
        $roleResult = $this->executeQueryTest($select);
        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='".$content['data']['uuid']."')";
        $orgResult = $this->executeQueryTest($select);
        $select = "SELECT * FROM ox_user where username ='".$content['data']['contact']['username']."'";
        $usrResult = $this->executeQueryTest($select);
        $select = "SELECT * from ox_address join ox_organization on ox_address.id = ox_organization.address_id where name = '".$content['data']['name']."'";
        $org = $this->executeQueryTest($select);
        $query = "SELECT * from ox_app_registry where org_id = (SELECT id from ox_organization where uuid = '".$content['data']['uuid']."')";
        $appResult = $this->executeQueryTest($query);
        $this->assertEquals($appname[0]['name'], $appName);
        $this->assertEquals($appUuid[0]['appuuidcount'], 1);
        $this->assertEquals(count($role), 3);
        $this->assertEquals(count($roleResult), 1);
        $this->assertEquals(count($orgResult), 1);
        $this->assertEquals($usrResult[0]['firstname'], $content['data']['contact']['firstname']);
        $this->assertEquals($usrResult[0]['lastname'], $content['data']['contact']['lastname']);
        $this->assertEquals($usrResult[0]['designation'], 'Admin');
        $this->assertEquals($rolePrivilegeResult[0][0]['count(id)'], 25);
        $this->assertEquals($rolePrivilegeResult[1][0]['count(id)'], 6);
        $this->assertEquals($rolePrivilegeResult[2][0]['count(id)'], 1);
        $this->assertEquals(isset($usrResult[0]['address_id']),true);
        $this->assertEquals($org[0]['address1'],$content['data']['address1']);
        $this->assertEquals($appResult[0]['app_id'],1);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content);
        unlink(__DIR__.'/../sampleapp/application.yml');

    }

    public function testDeployAppWithWrongUuidInDatabase()
    {
        copy(__DIR__.'/../sampleapp/application8.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        unlink(__DIR__.'/../sampleapp/application.yml');
    }

    public function testDeployAppWithWrongNameInDatabase()
    {
        copy(__DIR__.'/../sampleapp/application9.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__.'/../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path.$filename));
        $appName = $yaml['app'][0]['name'];
        $query = "SELECT name from ox_app where name = '".$appName."'";
        $app = $this->executeQueryTest($query);
        $this->assertEquals($app[0]['name'], $appName);
        $this->assertEquals($content['status'], 'success');
        unlink(__DIR__.'/../sampleapp/application.yml');
    }

    public function testDeployAppWithNameAndNoUuidInYMLButNameandUuidInDatabase()
    {
        copy(__DIR__.'/../sampleapp/application10.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__.'/../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path.$filename));
        $appName = $yaml['app'][0]['name'];
        $query = "SELECT name from ox_app where name = '".$appName."'";
        $app = $this->executeQueryTest($query);
        $this->assertEquals($app[0]['name'], $appName);
        $this->assertEquals($content['status'], 'success');
        unlink(__DIR__.'/../sampleapp/application.yml');
    }

    public function testDeployAppNoDirectory()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp1/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertNotEmpty($content);
    }

    public function testDeployAppNoFile()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp2/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertNotEmpty($content);
    }

    public function testDeployAppNoFileData()
    {
        copy(__DIR__.'/../sampleapp/application2.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertNotEmpty($content);
        unlink(__DIR__.'/../sampleapp/application.yml');
    }

    public function testDeployAppNoAppData()
    {
        copy(__DIR__.'/../sampleapp/application3.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertNotEmpty($content);
        unlink(__DIR__.'/../sampleapp/application.yml');
    }

    public function testDeployAppWithAppNameExistingAndNoUuid()
    {
        copy(__DIR__.'/../sampleapp/application1.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $query = "SELECT id from ox_organization where uuid = '".$content['data']['uuid']."'";
        $orgid = $this->executeQueryTest($query);
        $query = "SELECT * from ox_role where org_id = (SELECT id from ox_organization where uuid = '".$content['data']['uuid']."')";
        $role = $this->executeQueryTest($query);
        for($x=0;$x<sizeof($role);$x++){
            $query = "SELECT count(id) from ox_role_privilege where org_id = (SELECT id from ox_organization where role_id =".$role[$x]['id']."
                AND uuid = '".$content['data']['uuid']."')";
            $rolePrivilegeResult[] = $this->executeQueryTest($query);
        }
        $select = "SELECT * FROM ox_user_role where role_id =".$role[0]['id'];
        $roleResult = $this->executeQueryTest($select);
        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='".$content['data']['uuid']."')";
        $orgResult = $this->executeQueryTest($select);
        $select = "SELECT * FROM ox_user where username ='".$content['data']['contact']['username']."'";
        $usrResult = $this->executeQueryTest($select);
        $select = "SELECT * from ox_address join ox_organization on ox_address.id = ox_organization.address_id where name = '".$content['data']['name']."'";
        $org = $this->executeQueryTest($select);
        $query = "SELECT * from ox_app_registry where org_id = (SELECT id from ox_organization where uuid = '".$content['data']['uuid']."')";
        $appResult = $this->executeQueryTest($query);
        $this->assertEquals(count($role), 3);
        $this->assertEquals(count($roleResult), 1);
        $this->assertEquals(count($orgResult), 1);
        $this->assertEquals($usrResult[0]['firstname'], $content['data']['contact']['firstname']);
        $this->assertEquals($usrResult[0]['lastname'], $content['data']['contact']['lastname']);
        $this->assertEquals($usrResult[0]['designation'], 'Admin');
        $this->assertEquals($rolePrivilegeResult[0][0]['count(id)'], 25);
        $this->assertEquals($rolePrivilegeResult[1][0]['count(id)'], 6);
        $this->assertEquals($rolePrivilegeResult[2][0]['count(id)'], 1);
        $this->assertEquals(isset($usrResult[0]['address_id']),true);
        $this->assertEquals($org[0]['address1'],$content['data']['address1']);
        $this->assertEquals($appResult[0]['app_id'],1);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content);
        unlink(__DIR__.'/../sampleapp/application.yml');

    }

    public function testDeployAppOrgDataWithoutUuid()
    {
        copy(__DIR__.'/../sampleapp/application4.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $query = "SELECT uuid from ox_organization where name = '".$content['data']['name']."'";
        $orgUuid = $this->executeQueryTest($query);

        $query = "SELECT * from ox_role where org_id = (SELECT id from ox_organization where uuid = '".$orgUuid[0]['uuid']."')";
        $role = $this->executeQueryTest($query);
        for($x=0;$x<sizeof($role);$x++){
            $query = "SELECT count(id) from ox_role_privilege where org_id = (SELECT id from ox_organization where role_id =".$role[$x]['id']."
                AND uuid = '".$orgUuid[0]['uuid']."')";
            $rolePrivilegeResult[] = $this->executeQueryTest($query);
        }
        $select = "SELECT * FROM ox_user_role where role_id =".$role[0]['id'];
        $roleResult = $this->executeQueryTest($select);
        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='".$orgUuid[0]['uuid']."')";
        $orgResult = $this->executeQueryTest($select);
        $select = "SELECT * FROM ox_user where username ='".$content['data']['contact']['username']."'";
        $usrResult = $this->executeQueryTest($select);
        $select = "SELECT * from ox_address join ox_organization on ox_address.id = ox_organization.address_id where name = '".$content['data']['name']."'";
        $org = $this->executeQueryTest($select);
        $query = "SELECT * from ox_app_registry where org_id = (SELECT id from ox_organization where uuid = '".$orgUuid[0]['uuid']."')";
        $appResult = $this->executeQueryTest($query);
        $this->assertEquals(count($role), 3);
        $this->assertEquals(count($roleResult), 1);
        $this->assertEquals(count($orgResult), 1);
        $this->assertEquals($usrResult[0]['firstname'], $content['data']['contact']['firstname']);
        $this->assertEquals($usrResult[0]['lastname'], $content['data']['contact']['lastname']);
        $this->assertEquals($usrResult[0]['designation'], 'Admin');
        $this->assertEquals($rolePrivilegeResult[0][0]['count(id)'], 25);
        $this->assertEquals($rolePrivilegeResult[1][0]['count(id)'], 6);
        $this->assertEquals($rolePrivilegeResult[2][0]['count(id)'], 1);
        $this->assertEquals(isset($usrResult[0]['address_id']),true);
        $this->assertEquals($org[0]['address1'],$content['data']['address1']);
        $this->assertEquals($appResult[0]['app_id'],1);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content);
        unlink(__DIR__.'/../sampleapp/application.yml');
    }

    public function testDeployAppOrgDataWithoutContact()
    {
        copy(__DIR__.'/../sampleapp/application5.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $query = "SELECT uuid from ox_organization where name = '".$content['data']['name']."'";
        $orgUuid = $this->executeQueryTest($query);
        $query = "SELECT * from ox_role where org_id = (SELECT id from ox_organization where uuid = '".$orgUuid[0]['uuid']."')";
        $role = $this->executeQueryTest($query);
        for($x=0;$x<sizeof($role);$x++){
            $query = "SELECT count(id) from ox_role_privilege where org_id = (SELECT id from ox_organization where role_id =".$role[$x]['id']."
                AND uuid = '".$orgUuid[0]['uuid']."')";
            $rolePrivilegeResult[] = $this->executeQueryTest($query);
        }
        $select = "SELECT * FROM ox_user_role where role_id =".$role[0]['id'];
        $roleResult = $this->executeQueryTest($select);
        $select = "SELECT * FROM ox_user_org where org_id = (SELECT id from ox_organization where uuid ='".$orgUuid[0]['uuid']."')";
        $orgResult = $this->executeQueryTest($select);
        $select = "SELECT * FROM ox_user where username ='".$content['data']['contact']['username']."'";
        $usrResult = $this->executeQueryTest($select);
        $select = "SELECT * from ox_address join ox_organization on ox_address.id = ox_organization.address_id where name = '".$content['data']['name']."'";
        $org = $this->executeQueryTest($select);
        $query = "SELECT * from ox_app_registry where org_id = (SELECT id from ox_organization where uuid = '".$orgUuid[0]['uuid']."')";
        $appResult = $this->executeQueryTest($query);
        $this->assertEquals(count($role), 3);
        $this->assertEquals(count($roleResult), 1);
        $this->assertEquals(count($orgResult), 1);
        $this->assertEquals($usrResult[0]['firstname'], $content['data']['contact']['firstname']);
        $this->assertEquals($usrResult[0]['lastname'], $content['data']['contact']['lastname']);
        $this->assertEquals($usrResult[0]['designation'], 'Admin');
        $this->assertEquals($rolePrivilegeResult[0][0]['count(id)'], 25);
        $this->assertEquals($rolePrivilegeResult[1][0]['count(id)'], 6);
        $this->assertEquals($rolePrivilegeResult[2][0]['count(id)'], 1);
        $this->assertEquals(isset($usrResult[0]['address_id']),true);
        $this->assertEquals($org[0]['address1'],$content['data']['address1']);
        $this->assertEquals($appResult[0]['app_id'],1);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content);
        unlink(__DIR__.'/../sampleapp/application.yml');
    }

    public function testDeployAppOrgDataWithoutPreferences()
    {
        copy(__DIR__.'/../sampleapp/application6.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content);
        unlink(__DIR__.'/../sampleapp/application.yml');
    }

    public function testDeployAppAddExtraPrivilegesInDatabaseFromYml()
    {
         copy(__DIR__.'/../sampleapp/application1.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__.'/../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path.$filename));
        $privilegearray = array_unique(array_column($yaml['privilege'], 'name'));
        $appid = "SELECT id FROM ox_app WHERE name = '".$yaml['app'][0]['name']."'";
        $idresult = $this->executeQueryTest($appid);
        $queryString = "SELECT name FROM ox_privilege WHERE app_id = '".$idresult[0]['id']."'";
        $result = $this->executeQueryTest($queryString);
        $DBprivilege = array_unique(array_column($result, 'name'));
        $this->assertEquals($privilegearray, $DBprivilege);
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content);
        unlink(__DIR__.'/../sampleapp/application.yml');
    }

    public function testDeployAppDeleteExtraPrivilegesInDatabaseNotInYml()
    {
         copy(__DIR__.'/../sampleapp/application1.yml', __DIR__.'/../sampleapp/application.yml');
        $this->initAuthToken($this->adminUser);
        $data = ['path' => __DIR__.'/../sampleapp/'];
        $this->dispatch('/app/deployapp', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $filename = "application.yml";
        $path = __DIR__.'/../sampleapp/';
        $yaml = Yaml::parse(file_get_contents($path.$filename));
        $appid = "SELECT id FROM ox_app WHERE name = '".$yaml['app'][0]['name']."'";
        $idresult = $this->executeQueryTest($appid);
        $queryString = "SELECT name FROM ox_privilege WHERE app_id = '".$idresult[0]['id']."'";
        $result = $this->executeQueryTest($queryString);
        $DBprivilege = array_unique(array_column($result, 'name'));
        $list = "'" . implode( "', '", $DBprivilege) . "'";
        $this->assertNotEquals($list, 'MANAGE');
        $this->assertEquals($content['status'], 'success');
        $this->assertNotEmpty($content);
        unlink(__DIR__.'/../sampleapp/application.yml');
    }

    public function testCreateWithOutTextFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = [ 'type' => 2, 'org_id' => 4];
        $this->dispatch('/app', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
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
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdate()
    {
        $data = ['name' => 'Admin App', 'type' => 2, 'category' => 'Admin', 'logo' => 'app.png'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
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
        $content = (array)json_decode($this->getResponse()->getContent(), true);
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
        $content = (array)json_decode($this->getResponse()->getContent(), true);
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

    public function testAddToAppRegistry(){
        $data = ['app_name' => 'Admin'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/org/b0971de7-0387-48ea-8f29-5d3704d96a46/addtoappregistry', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppRegisterController::class);
        $this->assertControllerClass('AppRegisterController');
        $this->assertMatchedRouteName('addtoappregistry');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['app_name'], $data['app_name']);
    }

    public function testAddToAppRegistryDuplicated(){
        $data = ['app_name' => 'SampleApp'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/org/'.$this->testOrgUuid.'/addtoappregistry', 'POST', $data);
        $this->assertResponseStatusCode(409);
        $this->assertModuleName('App');
        $this->assertControllerName(AppRegisterController::class);
        $this->assertControllerClass('AppRegisterController');
        $this->assertMatchedRouteName('addtoappregistry');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetListOfAssignments()
    {
        $this->initAuthToken($this->adminUser);
        $workflowName = 'Test Workflow 1';
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/assignments?filter=[{"filter":{"filters":[{"field":"workflow_name","operator":"eq","value":"'.$workflowName.'"}]},"sort":[{"field":"workflow_name","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class);
        $this->assertControllerClass('AppController');
        $this->assertMatchedRouteName('assignments');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['workflow_name'], $workflowName);
        $this->assertEquals($content['total'],1);
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
