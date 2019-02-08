<?php
namespace App;

use App\Controller\AppController;
use App\Model;
use Oxzion\Db\ModelTable;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;


class AppControllerTest extends ControllerTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/App.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('App');
        $this->assertControllerName(AppController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AppController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testinstallAppForOrg()
    { // Testing to create a new app
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test App', 'uuid' => '2323423423', 'description' => 'Desc', 'type' => 1, 'logo' => 'app1.png', 'date_created' => '0000-00-00 00:00:00', 'date_modified' => '0000-00-00 00:00:00'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_app'));
        $this->dispatch('/app/1/appinstall', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('appinstall');
        $content = (array)json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($content['data']['name'], 'Test App');
        $this->assertEquals($content['data']['uuid'], '2323423423');
        $this->assertEquals($content['data']['description'], "Desc");
        $this->assertEquals($content['data']['type'], 1);
        $this->assertEquals($content['data']['logo'], "app1.png");
        $this->assertEquals($content['data']['date_created'], "0000-00-00 00:00:00");
        $this->assertEquals($content['data']['date_modified'], "0000-00-00 00:00:00");
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_app'));
    }


    public function testinstallAppForOrgCheckForUniqueAppName()
    { // Testing to create a new app
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'App 1', 'uuid' => '2323423423', 'description' => 'Desc', 'type' => 1, 'logo' => 'app1.png', 'date_created' => '0000-00-00 00:00:00', 'date_modified' => '0000-00-00 00:00:00'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_app'));
        $this->dispatch('/app/1/appinstall', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('appinstall');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['data'], '0');
    }

    public function testCreateWithoutRequiredField()
    { // Testing to create a new app
        $this->initAuthToken($this->adminUser);
        $data = ['uuid' => '2323423423', 'description' => 'Desc', 'type' => 1, 'date_created' => '0000-00-00 00:00:00', 'date_modified' => '0000-00-00 00:00:00'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_app'));
        $this->dispatch('/app/1/appinstall', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('appinstall');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }
}